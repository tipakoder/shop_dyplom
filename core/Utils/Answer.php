<?php

namespace Core\Utils;

class Answer {
    private static array $place = [];
    public static array $warnings = [];
    public static bool $debug = true, $test = false;

    public static function setup($controller, $method){
        self::$place = [
            "controller" => $controller,
            "method" => $method
        ];
    }

    public static function success($data = []){
        if(!self::$test) header("Content-Type: application/json; charset=utf-8");
        $result = [
            "type" => "success",
            "warnings" => self::$warnings,
            "data" => $data
        ];

        if(!self::$test) {
            exit(json_encode($result, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE));
        } else {
            return $result;
        }
    }

    public static function error($error_msg, $additional = [], $debug_turn = true){
        if(!self::$test) header("Content-Type: application/json; charset=utf-8");
        $result = [
            "type" => "error",
            "warnings" => self::$warnings,
            "data" => [
                "messages" => $error_msg,
                "additional" => $additional
            ]
        ];

        if(self::$place !== []) $result['place'] = self::$place;
        if(self::$debug && $debug_turn) $result['debug_backtrace'] = debug_backtrace();

        if(!self::$test) {
            exit(json_encode($result, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE));
        } else {
            return $result;
        }
    }

    public static function warning($data){
        self::$warnings[] = $data;
    }
}