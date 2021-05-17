<?php

$currentUrl = trim(array_shift(explode("?", $_SERVER["REQUEST_URI"])), "/");
$currentMethod = strtolower($_SERVER["REQUEST_METHOD"]);
$currentOptions = (strtolower($_SERVER["REQUEST_METHOD"]) == "get") ? $_GET : $_POST;

function getRoute($routesList){
    global $currentMethod, $currentOptions, $currentUrl, $level_access, $auth;

    foreach($routesList as $route){
        $route['url'] = trim($route["url"], "/");

        if(isset($route['regex']) && $route['regex'] == true) preg_match("/^{$route['url']}$/", $currentUrl, $matches);

        if( ($route['url'] == $currentUrl || (isset($matches) && $matches != null)) && strtolower($route["method"]) === $currentMethod){
            if(isset($route["level_access"])){
                if($route["level_access"] < $level_access){
                    load_error(401, "Доступ запрещён");
                    exit;
                }
            }

            if(isset($route['auth'])){
                if($route['auth'] != $auth){
                    load_error(401, "Неверный тип аутентификации");
                    exit;
                }
            }

            if(isset($route["fields"])){
                foreach($route["fields"] as $field){
                    if(empty($currentOptions[$field])){
                        send_answer(["Параметр '{$field}' не был отправлен"], false);
                    }
                }
            }

            if(isset($matches) && $matches != null){
                $route['options'] = $matches;
            }

            return $route;
        }
    }
    return false;
}