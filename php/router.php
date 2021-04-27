<?php

$currentUrl = array_shift(explode("?", $_SERVER["REQUEST_URI"]));
$currentMethod = strtolower($_SERVER["REQUEST_METHOD"]);

function getRoute($routesList){
    global $currentMethod, $currentUrl, $level_access;

    foreach($routesList as $route){
        if($route["url"] === $currentUrl && $route["method"] === $currentMethod){
            if(isset($route["level_access"])){
                if($route["level_access"] < $level_access){
                    load_error(401, "Доступ запрещён");
                    exit;
                }
            }

            return $route;
        }
    }
    return false;
}