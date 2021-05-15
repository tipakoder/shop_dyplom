<?php

function auth(){
	global $currentOptions;

	$login = verify_field("Логин", $currentOptions['login'], 4, 45);
	$password = verify_field("Пароль", $currentOptions['password'], 4, 0);

	if( $query = dbQueryOne("SELECT id, password FROM account WHERE login = '{$login}'") ){
		if(!password_verify($password, $query['password'])){
			send_answer(["Неверный пароль"]);
		}
		$sessionkey = new_auth($query['id']);
		send_answer([], true);
	}
	send_answer(["Аккаунта с введённым логином не найдено"]);
}

function reg(){
	global $currentOptions;

	$login = verify_field("Логин", $currentOptions['login'], 4, 45, "@,#,$,%,^,*,;,',`,!");
	$password = password_hash(verify_field("Пароль", $currentOptions['password'], 4, 0), PASSWORD_DEFAULT);

	if($query = dbQueryOne("SELECT id, password FROM account WHERE login = '{$login}'")){
		send_answer(["Аккаунт с введённым логином уже существует"]);
	}

	if( !dvExeucte("INSERT INTO account (login, password) VALUES ('{$login}', '{$password}')") ){
		send_answer(["Неизвестная ошибка записи аккаунта в базу"]);
	}

	$sessionkey = new_auth(dbLastId());
	send_answer([], true);
}

