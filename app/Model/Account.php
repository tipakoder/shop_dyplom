<?php

namespace App\Model;

use Core\Utils\Answer;
use Core\Utils\Cookie;
use Core\Utils\ErrorPage;
use Core\Utils\Request;

class Account extends \Core\Base\Model {
    protected array $temp_user = [];
    protected string $session_key = "";
    protected int $level_access = 1;
    protected bool $is_auth = false;

    public function __construct($db_config_name = "db") {
        parent::__construct($db_config_name);
        $this->view = new \App\View\Account();
    }

    /**
     * Получение индикатора авторизации.
     * @return boolean
     */
    public function isAuth(): bool {
        return $this->is_auth;
    }

    /**
     * Получение уровня доступа.
     * @return int
     */
    public function getLevelAccess(): int {
        return $this->level_access;
    }

    public function verify_auth($cookie = null): array {
        // Если мы уже проверяли авторизацию - выводим полученного пользователя
        if($this->session_key != "") $cookie = $this->session_key;

        if( $cookie == null && !($cookie = Cookie::get("auth_session")) ){
            ErrorPage::page401("Для начала авторизуйтесь")->launch();
        }

        $this->session_key = $cookie;
        // Пороизводим поиск аккаунта в базе данных
        if($query = $this->db->queryOne("SELECT account.* FROM account, account_session WHERE account_session.sessionkey = ? AND account.id = account_session.account_id", [$cookie]) ){
            // Объявляем служебные переменные
            $this->level_access = ($query["type"] === "user") ? 1 : 0;
            if($this->level_access === 0){
                Cookie::set("auth_level_access", $this->level_access) ;
            } else if(Cookie::get("auth_level_access")) {
                Cookie::remove("auth_level_access");
            }
            $this->is_auth = true;
            $this->temp_user = $query;
        } else {
            // Если не нашли, удаляем куки-файл и выполняем ошибку 401
            Cookie::remove("auth_session");
            Cookie::remove("auth_level_access");
            ErrorPage::page401("Сессия недействительна")->launch();
        }
        // Выполняем дополнительные проверки
        if(!$this->test && Request::getRoute()["level_access"] != null && intval(Request::getRoute()["level_access"]) < $this->level_access){
            ErrorPage::page401("Доступ запрещён")->launch();
        }
        if(!$this->test && Request::getRoute()["auth"] != null && Request::getRoute()["auth"] != $this->is_auth){
            ErrorPage::page401("Неверный тип авторизации")->launch();
        }
        // Возвращаем текущего пользователя
        return $this->temp_user;
    }

    public function create_session($account_id){
        // Ключ сессии - случайный хэш
        $session_key = hash("sha256", $account_id.time());
        // Время создания сессии в unix формате
        $time = time();
        // IP с которым была создана сессия
        $ip = (isset($_SERVER["REMOTE_ADDR"])) ? $_SERVER["REMOTE_ADDR"] : "127.0.0.1";
        // Записываем всё это в таблицу с сессиями и связываем с переданным в переменную $account_id ID
        if( $this->db->execute("INSERT INTO account_session (account_id, sessionkey, timestamp, ip) VALUES (?, ?, ?, ?)", [$account_id, $session_key, $time, $ip]) ){
            Cookie::set("auth_session", $session_key, Cookie::YEAR);
            return $session_key;
        } else {
            return Answer::error(["Неизвестная ошибка регистрации новой сессии"]);
        }
    }

    public function auth($login, $password){
        // Ищем аккаунта с введённым логином
        if( !($query = $this->db->queryOne("SELECT id, password FROM account WHERE login = ?", [$login])) ){
            return Answer::error(["Аккаунта с введённым логином не найдено"]);
        }
        // Проверяем, верный ли пароль
        if(!password_verify($password, $query['password'])){
            return Answer::error(["Неверный пароль"]);
        }
        // Создаём сессию
        $session_key = $this->create_session($query['id']);
        $this->verify_auth($session_key);
        // Отправляем успешный ответ
        return Answer::success(["session_key" => $session_key]);
    }

    public function reg($name, $email, $telephone, $login, $password){
        $password = password_hash($password, PASSWORD_DEFAULT);
        // Проверяем, если в БД аккаунт с введённым логином, эл. почтой, телефоном
        if($this->db->queryOne("SELECT id, password FROM account WHERE login = ? OR email = ? OR telephone = ?", [$login, $email, $telephone])){
            return Answer::error(["Аккаунт с введённым логином, эл. почтой или телефоном уже существует"]);
        }
        // Записываем новый аккаунт в базу
        if( !$this->db->execute("INSERT INTO account (name, email, telephone, login, password) VALUES (?, ?, ?, ?, ?)", [$name, $email, $telephone, $login, $password]) ){
            return Answer::error(["Неизвестная ошибка записи аккаунта в базу"]);
        }
        // Создаём сессию
        $session_key = $this->create_session($this->db->lastInsertId());
        // Отправляем успешный ответ
        return Answer::success(["session_key" => $session_key]);
    }

    public function remove(): array {
        // Проверяем авторизацию
        $this->verify_auth();
        if(!$this->db->execute("DELETE FROM account WHERE id = ?", [$this->temp_user['id']])){
            return Answer::error(["Неизвестная ошибка"]);
        }
        return Answer::success([]);
    }

    public function get(){
        // Проверяем авторизацию
        $this->verify_auth();
        return Answer::success(["account" => $this->temp_user]);
    }

    public function edit($name, $email, $telephone, $login){
        // Проверяем авторизацию
        $this->verify_auth();
        // Создаём нужные переменные
        $sql = "UPDATE account SET ";
        $sql_to_change = [];
        $sql_array = [];
        // Если какие-либо поля аккаунта изменились - добавляем в запрос
        if($name != $this->temp_user['name'] && $name != null){
            $sql_to_change[] = "name = ?";
            $sql_array[] = $name;
        }
        if($email != $this->temp_user['email'] && $email != null){
            $sql_to_change[] = "email = ?";
            $sql_array[] = $email;
        }
        if($telephone != $this->temp_user['telephone'] && $telephone != null){
            $sql_to_change[] = "telephone = ?";
            $sql_array[] = $telephone;
        }
        if($login != $this->temp_user['login'] && $login != null){
            $sql_to_change[] = "login = ?";
            $sql_array[] = $login;
        }
        // Объединяем SQL и SQL изменений
        for($i = 0; $i < count($sql_to_change); $i++){
            $sql .= $sql_to_change[$i];
            if($i < count($sql_to_change)-1) $sql .= ', ';
        }
        // Выполняем запрос
        if(!$this->db->execute($sql, $sql_array)){
            return Answer::error(["Ошибка применения изменений"]);
        }
        return Answer::success([]);
    }

    public function changePassword($old_password, $new_password){
        // Проверяем авторизацию
        $this->verify_auth();
        // Проверяем, верен ли старый пароль
        if(!password_verify($old_password, $this->temp_user["password"])){
            return Answer::error(["Старый пароль неверен"]);
        }
        $new_password = password_hash($new_password, PASSWORD_DEFAULT);
        // Меняем пароль
        if(!$this->db->execute("UPDATE account SET password = ? WHERE id = ? LIMIT 1", [$new_password, $this->temp_user['id']])){
            return Answer::error(["Ошибка сохранения пароля"]);
        }
        return Answer::success([]);
    }

    public function logout(){
        $this->db->execute("UPDATE account_session SET active = 0 WHERE sessionkey = ? LIMIT 1", [Cookie::get("auth_session")]);
        Cookie::remove("auth_session");
        Cookie::remove("auth_level_access");
        header("Location: /");
    }

    public function index(){
        $account = $this->verify_auth();
        $orders = $this->db->query("SELECT * FROM orders WHERE phone = ? ORDER BY id DESC", [$this->temp_user['telephone']]);
        foreach ($orders as $key => $order) {
            $fullprice = 0;
            $orders_name = "";
            $orders_items = $this->db->query("SELECT product.name, product.price, orders_product.count FROM orders_product, product WHERE orders_product.orders_id = ? AND product.id = orders_product.product_id", [$order['id']]);
            if($orders_items){
                for($i = 0; $i < count($orders_items); $i++){
                    $fullprice += $orders_items[$i]['price'] * $orders_items[$i]['count'];
                    $orders_name .= $orders_items[$i]['name'];
                    if(count($orders_items)-1 > $i) $orders_name .= ", ";
                }
                $orders[$key]['price'] = $fullprice;
                $orders[$key]['items'] = $orders_name;
            }
        }
        $this->view->index($account, $orders);
    }
}