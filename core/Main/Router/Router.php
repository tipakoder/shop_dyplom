<?php

namespace Core\Main\Router;
use Core\Utils\Answer;
use Core\Utils\ErrorPage;

class Router {
    protected array $routesList;
    protected string $currentUrl;
    protected string $currentMethod;
    protected array $currentOptions;

    /**
     * Конструктор Маршрутизатора.
     * @param $routesList
     */
    public function __construct($routesList){
        // Задаём список возможных страниц
        $this->routesList = $routesList;
        // Делим адресную строку по символу "?" и берём первый элемент, дабы исключить GET параметры
        $arrayExplode = explode("?", $_SERVER["REQUEST_URI"]);
        $temp_url_parts = array_shift($arrayExplode);
        // Удаляем слеш с начала и конца нашего URL (и для удалёнки добавляем приставку "shop/")
        $this->currentUrl = "shop/" . trim($temp_url_parts, "/");
        // Получаем текущий метод запроса
        $this->currentMethod = strtolower($_SERVER["REQUEST_METHOD"]);
        // На основе метода запроса получаем отправленные данные
        $this->currentOptions = (strtolower($_SERVER["REQUEST_METHOD"]) == "get") ? $_GET : $_POST;
    }

    public function getRoute(){
        // Перебираем массив возможных страниц
        foreach($this->routesList as $route){
            // Корректируем URL страницы для сравнения
            $route['url'] = trim($route["url"], "/");
            // Пытаемся получить параметры, находящиеся внутри URL
            if(isset($route['regex']) && $route['regex'] == true) preg_match("/^{$route['url']}$/", $this->currentUrl, $matches);
            // Если текущий URL совпадает с URL страницы или мы смогли получить параметры в URL и текущий метод совпадает с методом этой страницы - производим дополнительные проверки
            if( ($route['url'] == $this->currentUrl || (isset($matches) && $matches != null)) && strtolower($route["method"]) === $this->currentMethod){
                // Если у шаблона страницы задан ключ fields - проверяем отправленные параметры
                if(isset($route["fields"])){
                    foreach($route["fields"] as $field){
                        if(empty($this->currentOptions[$field])){
                            Answer::error(["Параметр '{$field}' не был отправлен"]);
                        }
                    }
                }
                // Если мы смогли получить параметры из URL - добавляем их в шаблон
                if(isset($matches) && $matches != null){
                    $route['regexOptions'] = $matches;
                }
                // Создаём и возвращаем новый объект шаблона
                return new Route($route);
            }
        }
        // Если метод не нашёл страницу, возвращаем ошибку 404
        return ErrorPage::page404();
    }
}
