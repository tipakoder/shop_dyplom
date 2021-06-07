<?php

namespace Core\Utils;

class Request {
    protected static array $route = [];

    /**
     * Получение параметров запроса, исходя из его метода
     * @return array
     */
    private static function getOptionsAll() : array{
        return (strtolower($_SERVER["REQUEST_METHOD"]) == "get") ? $_GET : $_POST;
    }

    /**
     * Получение параметра запроса по имени.
     * В случае неудачи вернёт ошибку запроса.
     * @param $name
     * @return string
     */
    public static function getOption($name) : string{
        // Получаем список параметров
        $options = self::getOptionsAll();
        // Если у теущей страницы есть параметры, ищем и в них
        if(isset(self::$route["options"])) $options = array_merge($options, self::$route["options"]);
        // Получаем параметр по ключу, если нет, выводим ошибку
        if(isset($options[$name])) return $options[$name];
        Answer::error(["Parameter '{$name}' is missing"]);
    }

    /**
     * Попытка получения запроса по имени.
     * В случае неудачи выведет false.
     * @param $name
     * @return false|string
     */
    public static function tryGetOption($name){
        // Получаем список параметров
        $options = self::getOptionsAll();
        // Если у теущей страницы есть параметры, ищем и в них
        if(isset(self::$route["options"])) $options = array_merge($options, self::$route["options"]);
        // Получаем параметр по ключу, если не получим, возвращаем false
        if(isset($options[$name])) return $options[$name];
        return false;
    }

    /**
     * Получить текущую страницу.
     * @return array
     */
    public static function getRoute() : array {
        return self::$route;
    }

    /**
     * Задать текущую страницу.
     * @param array $route
     */
    public static function setRoute(array $route): void {
        self::$route = $route;
    }
}