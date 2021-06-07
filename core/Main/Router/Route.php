<?php

namespace Core\Main\Router;
use Core\Base\Controller;
use Core\Utils\Request;

class Route {
    protected Controller $controller;
    protected string $method_name;

    /**
     * Конструктор страницы.
     * @param $route
     */
    public function __construct($route) {
        // Создаём новый контроллер исходя из требуемого
        $className = "\\App\\Controller\\" . $route["controller"];
        $this->controller = new $className();
        // Записываем требуемый к исполнению метод
        $this->method_name = $route["function"];
        // Записываем текущую страницу в утилиту запроса
        Request::setRoute($route);
    }

    /**
     * Метод запуска страницы
     */
    public function launch() : void{
        // Выполняем требуемый метод контроллера
        $this->controller->{$this->method_name}();
        exit();
    }
}