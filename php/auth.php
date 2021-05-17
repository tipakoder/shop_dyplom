<?php

$level_access = 2;
$currentUser = null;
$auth = false;

if(isset($_COOKIE["authsession"])){
    if( $query = dbQueryOne("SELECT account.* FROM account, account_session WHERE account_session.sessionkey = '{$_COOKIE['authsession']}' AND account.id = account_session.account_id") ){
        $level_access = ($query["type"] === "user") ? 1 : 0;
        $currentUser = $query;
        $auth = true;
    } else {
        setcookie("authsession", null, time()-3600, "/");
    }
}

function create_session($account_id){
    return hash("sha256", $account_id.time());
}

function new_auth($account_id){
	$sessionkey = create_session($account_id);
	$time = time();
	$ip = $_SERVER["REMOTE_ADDR"];

	if( dbExecute("INSERT INTO account_session (account_id, sessionkey, timestamp, ip) VALUES ('{$account_id}', '{$sessionkey}', '{$time}', '{$ip}')") ){
		setcookie("authsession", $sessionkey, time()+3600*48*90*24, "/");
		return $sessionkey;
	} else {
		send_answer(["Неизвестная ошибка регистрации новой сессии"]);
	}
}