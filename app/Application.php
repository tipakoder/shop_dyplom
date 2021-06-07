<?php

namespace App;

use Core\Main\Router\Router;
use Core\Utils\Config;
use Core\Utils\Request;

class Application {
    protected Router $router;

    /**
     * Конструктор приложения.
     */
    public function __construct(){
        // Получаем список страниц в зависимости от метода запроса
        $routesList = (strtolower($_SERVER["REQUEST_METHOD"]) == "get") ? Config::load("frontRoutes") : Config::load("apiRoutes");
        // Создаём экземпляр маршрутизатора
        $this->router = new Router($routesList);
    }

    /**
     *  Метод запуска приложения.
     */
    public function launch(){
        // Получаем текущую страницу
        $temp_route = $this->router->getRoute();
        // Запускаем страницу на исполнение
        $temp_route->launch();
    }
}