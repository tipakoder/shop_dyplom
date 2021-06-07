<?php

namespace Core\Utils;
use Core\Main\Router\Route;

class ErrorPage {
    /**
     * Метод формирования шаблона ошибки
     * @param integer $code
     * @param string $text
     * @return array
     */
    private static function make_page(int $code, string $text = "") : array{
        return [
            "controller" => "Error",
            "function" => "codePage",
            "options" => [
                "code" => $code,
                "text" => $text
            ]
        ];
    }

    /**
     * Шаблонная ошибка запрещённого доступа (401 ошибка)
     * @param string $text
     * @return Route
     */
    public static function page401(string $text = "") : Route{
        return new Route(self::make_page(401, ($text != "") ? $text : "Доступ запрещён"));
        exit();
    }

    /**
     * Шаблонная ошибка отсутствия страницы (404 ошибка)
     * @param string $text
     * @return Route
     */
    public static function page404(string $text = "") : Route{
        return new Route(self::make_page(404, ($text != "") ? $text : "Страница не найдена"));
        exit();
    }

    /**
     * Шаблонная ошибка внутренних модулей (422 ошибка)
     * @param string $text
     * @return Route
     */
    public static function page422(string $text = "") : Route{
        return new Route(self::make_page(422, ($text != "") ? $text : "Ошибка загрузка модулей"));
        exit();
    }

    /**
     * Шаблонная ошибка внутренних модулей (422 ошибка)
     * @param string $text
     * @return Route
     */
    public static function page424(string $text = "") : Route{
        return new Route(self::make_page(424, ($text != "") ? $text : "Невыполненная зависимость"));
        exit();
    }
}