<?php

require_once "init.php";

try{
    $route = false;

    if(strtolower($_SERVER["REQUEST_METHOD"]) == "get"){
        $route = getRoute(load_config("frontRoutes"));
    } else {
        $route = getRoute(load_config("apiRoutes"));
    }

    if( $route !== false ){
        load_controller($route);
    } else {
        load_error(404, "Страница не найдена");
    }
}catch(Exception $e){
    load_error(422, "Проблемы с загрузкой модулей");
}