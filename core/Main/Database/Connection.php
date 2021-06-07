<?php

namespace Core\Main\Database;
use Core\Utils\Answer;
use Core\Utils\Config;
use Core\Utils\ErrorPage;
use PDO;

class Connection {
    private PDO $pdo;

    /**
     * Конструктор подключения к базе данных.
     * @param string $config_name
     */
    public function __construct(string $config_name = "db") {
        // Загружаем конфиг подключения
        $config = Config::load($config_name);
        try{
            // Определяем дополнительные параметры PDO
            $opt = [
                // В качестве вида ответа используем ассоциативный массив
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ];
            // Создаём экземпляр объекта PDO c параметрами из конфига
            $this->pdo = new PDO($config['driver'].':host='.$config['host'].';dbname='.$config['name'].';charset='.$config['charset'], $config['user'], $config['password'], $opt);
        }catch (\PDOException $e){
            // Если произойдёт ошибка, загрузится страница ошибки
            ErrorPage::page422()->launch();
        }
    }

    /**
     * Метод выполнения запросов типа SELECT.
     * @param $sql
     * @param array $params
     * @return array
     */
    public function query($sql, array $params = []): array {
        // Подготавливаем запрос
        $sth = $this->pdo->prepare($sql);
        // Выполняем запрос
        $sth->execute($params);
        // Возвращаем массив с ответом на запрос
        return $sth->fetchAll();
    }

    /**
     * Метод выполнения запросов типа SELECT c дополнительным параметром LIMIT 1.
     * @param $sql
     * @param array $params
     * @return array
     */
    public function queryOne($sql, array $params = []) {
        // Подготавливаем запрос
        $sth = $this->pdo->prepare($sql . " LIMIT 1");
        // Выполняем запрос
        $sth->execute($params);
        // Выводим один ассоциативный массив с результатом запроса
        $result = $sth->fetchAll();
        return ($result) ? array_shift($result) : false;
    }

    /**
     * Метод выполнения запросов типа INSERT, UPDATE, DELETE.
     * @param $sql
     * @param array $params
     * @return bool
     */
    public function execute($sql, array $params = []): bool {
        // Подготавливаем запрос
        $sth = $this->pdo->prepare($sql);
        // Выполняем
        if($sth->execute($params)) return true;
        return false;
    }

    public function lastInsertId(): string {
        // Возвращаем значение последенго AI поля
        return $this->pdo->lastInsertId();
    }
}