<?php

namespace Core\Utils;

class Cookie {
    // Константы отвечающие за время
    public const HOUR = 3600;
    public const DAY = self::HOUR * 24;
    public const WEEK = self::DAY * 7;
    public const MONTH = self::WEEK * 4;
    public const YEAR = self::MONTH * 12;
    // Включено ли тестирование
    public static $test = false;

    /**
     * Получение COOKIE.
     * @param $name
     * @return null|mixed
     */
    public static function get($name){
        return (isset($_COOKIE[$name])) ? $_COOKIE[$name] : null;
    }

    /**
     * Создание COOKIE.
     * @param $name
     * @param $data
     * @param float|int $time
     */
    public static function set($name, $data, $time = self::YEAR){
        if(!self::$test){
            setcookie($name, $data, time() + $time, "/");
        } else {
            return $data;
        }
    }

    /**
     * Удаление COOKIE.
     * @param $name
     */
    public static function remove($name){
        if(!self::$test) {
            setcookie($name, null, time() + self::HOUR * -1, "/");
        } else {
            return true;
        }
    }
}