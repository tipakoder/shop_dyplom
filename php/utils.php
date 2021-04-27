<?php

function load_config($name){
    $path = ROOTDIR."/config/{$name}.json";

    if(file_exists($path)){
        return json_decode(file_get_contents($path), true);
    }
    return false;
}

function load_controller($route){
    require_once ROOTDIR."/controller/{$route["controller"]}.php";
    call_user_func($route["function"], []);
}

function load_view($view, $title, $params = [], $template = "template"){
    global $level_access;

    $extract = array_merge([
        "SYS_TITLE" => $title,
        "SYS_PAGE" => $view,
        "AUTH" => ($level_access < 2) ? true : false,
        "SYS_LEVELACCESS" => $level_access
    ], $params);

    extract($extract);

    require_once "view/{$template}.php";
}

function verify_field($name, $value, $min = 4, $max = 120, $forriden_symbols = ""){
    if(strlen($value) < $min && $min !== 0){
        send_answer("'{$name}' содержит мало символов (мин: {$min})");
    }
    if(strlen($value) > $max && $max !== 0){
        send_answer("'{$name}' содержит много символов (макс: {$max})");
    }
    if($forriden_symbols != ""){
        $pattern = "[{$forriden_symbols}]";
        if(preg_match_all($pattern, $value)){
            send_answer("'{$name}' содержит непопустимые символы: {$forriden_symbols}");
        }
    }

    return $value;
}

function send_answer($data = [], $type = false){
    $type = ($type) ? "success" : "error";
    header('Content-Type: application/json; charset=utf-8');
    exit(json_encode([
        "type" => $type,
        "data" => $data
    ], JSON_PRETTY_PRINT));
}

function load_error($code, $more = null){
    load_view("", "Ошибка {$code}", ["code" => $code, "more" => $more], "error");
}