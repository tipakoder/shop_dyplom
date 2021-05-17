<?php

// Общие возможности

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

	$name = verify_field("Имя", $currentOptions['name'], 2, 45, "@,#,$,%,^,*,;,',`,!");
	$email = verify_field("Электронная почта", $currentOptions['email'], 4, 120, "#,$,%,^,*,;,',`,!");
	$telephone = verify_field("Телефон", $currentOptions['telephone'], 11, 12, "@,#,$,%,^,*,;,',`,!");
	$login = verify_field("Логин", $currentOptions['login'], 4, 45, "@,#,$,%,^,*,;,',`,!");
	$password = password_hash(verify_field("Пароль", $currentOptions['password'], 4, 0), PASSWORD_DEFAULT);

	if($query = dbQueryOne("SELECT id, password FROM account WHERE login = '{$login}'")){
		send_answer(["Аккаунт с введённым логином уже существует"]);
	}

	if( !dbExecute("INSERT INTO account (name, email, telephone, login, password) VALUES ('{$name}', '{$email}', '{$telephone}', '{$login}', '{$password}')") ){
		send_answer(["Неизвестная ошибка записи аккаунта в базу"]);
	}

	$sessionkey = new_auth(dbLastId());
	send_answer([], true);
}

// ---------------------- USER

function get_account(){
	global $currentUser;

	if($query = dbQueryOne("SELECT name, email, telephone, login FROM account WHERE id = '{$currentUser['id']}'")){
		send_answer(["account" => $query], true);
	}
	send_answer(["Аккаунт не найден"]);
}

function edit_account(){
	global $currentOptions, $currentUser;

	$name = verify_field("Имя", $currentOptions['name'], 2, 45, "@,#,$,%,^,*,;,',`,!");
	$email = verify_field("Электронная почта", $currentOptions['email'], 4, 120, ",#,$,%,^,*,;,',`,!");
	$telephone = verify_field("Телефон", $currentOptions['telephone'], 11, 12, "@,#,$,%,^,*,;,',`,!");
	$login = verify_field("Логин", $currentOptions['login'], 4, 45, "@,#,$,%,^,*,;,',`,!");

	$sql = "UPDATE account SET ";
	$to_change = [];
	if($name != $currentUser['name']){
		$to_change[] = "name = '{$name}'";
	}
	if($email != $currentUser['email']){
		$to_change[] = "email = '{$email}'";
	}
	if($telephone != $currentUser['telephone']){
		$to_change[] = "telephone = '{$telephone}'";
	}
	if($login != $currentUser['login']){
		$to_change[] = "login = '{$login}'";
	}
	for($i = 0; $i < count($to_change); $i++){
		$sql .= $to_change[$i];
		if($i < count($to_change)-1) $sql .= ', ';
	}

	if(dbExecute($sql)){
		send_answer([], true);
	}
	send_answer(["Ошибка применения изменений"]);
}

// ---------------------- ADMIN

function new_promocode(){
	global $currentOptions;

	$code = verify_field("Имя", $currentOptions['code'], 1, 60);
	$percent = verify_field("Процент скидки", $currentOptions['percent'], 1, 11);

	if(dbQueryOne("SELECT id FROM promocode WHERE code = '{$code}'")){
		send_answer(["Данный код уже задействован"]);
	}

	if(dbExecute("INSERT INTO promocode (code, percent) VALUES ('{$code}', '{$percent}')")){
		send_answer([], true);
	}
	send_answer(["Неизвестная ошибка добавления промокода"]);
}

function turn_promocode(){
	global $currentOptions;

	$id = verify_field("ID", $currentOptions['id'], 1, 11);

	if(!($query = dbQueryOne("SELECT id, active FROM promocode WHERE id = '{$id}'"))){
		send_answer(["Промокод с данным ID не найден"]);
	}

	$new_status = ($query['active'] == "y") ? "n" : "y";
	if(dbExecute("UPDATE promocode SET active = '{$new_status}' WHERE id = '{$id}' LIMIT 1")){
		send_answer([], true);
	}

	send_answer(["Неизвестная ошибка"]);
}

function new_delivery_service(){
	global $currentOptions;

	$name = verify_field("Кодовое имя", $currentOptions['name'], 1, 25);
	$title = verify_field("Заголовок", $currentOptions['title'], 1, 45);

	if(dbExecute("INSERT INTO delivery_service (name, title) VALUES ('{$name}', '{$title}')")){
		send_answer([], true);
	}

	send_answer(["Неизвестная ошибка добавления службы доставки"]);
}

function remove_delivery_service(){
	global $currentOptions;
	$id = verify_field("ID", $currentOptions['id'], 1, 11);

	if(dbExecute("DELETE FROM delivery_service WHERE id = '{$id}'")){
		send_answer([], true);
	}

	send_answer(["Неизвестная ошибка удаления службы доставки"]);
}

function get_categorys(){
	$categorys = dbQuery("SELECT * FROM category");
	send_answer(["categorys" => $categorys], true);
}

function get_subcategorys(){
	global $currentOptions;
	$category_id = verify_field("ID", $currentOptions['id'], 1, 11);
	if($query = dbQuery("SELECT * FROM subcategory WHERE category_id = '{$category_id}'")){
		send_answer(["subcategorys" => $query], true);
	}
	send_answer(["Подкатегории не найдены"]);
}

function new_product(){
	global $currentOptions;
	// Наименования товара
	$name = verify_field("Наименование товара", $currentOptions['name'], 1, 120);
	// Описание товара
	$description = verify_field("Описание товара", $currentOptions['description'], 1, 6000);
	// Цена товара
	$price = verify_field("Цена товара", $currentOptions['price'], 1, 10);
	// Основное фото
	$photo = (isset($_FILES['photo'])) ? $_FILES['photo'] : null;
	// Дополнительные фото
	$additional_photos = [];
	if(isset($_FILES['photo1']) && $_FILES['photo1']["name"] != null) $additional_photos[] = $_FILES['photo1'];
	if(isset($_FILES['photo2']) && $_FILES['photo2']["name"] != null) $additional_photos[] = $_FILES['photo2'];
	if(isset($_FILES['photo3']) && $_FILES['photo3']["name"] != null) $additional_photos[] = $_FILES['photo3'];
	// Категория товара
	$category = verify_field("Категория товара", $currentOptions['category'], 1, 12);
	// Подкатегория товара
	$subcategory = verify_field("Подкатегория товара", $currentOptions['subcategory'], 1, 12);
	// Если категория новая - создаём вместе с подкатегорией
	if($category == "new"){
		$categoryName = verify_field("Название категории товара", $currentOptions['categoryName'], 1, 120);
		if(!dbExecute("INSERT INTO category (name) VALUES ('{$categoryName}')")){
			send_answer(["Ошибка добавления категории"]);
		}
		$category = dbLastId();
		$subcategoryName = verify_field("Название категории товара", $currentOptions['subcategoryName'], 1, 60);
		if(!dbExecute("INSERT INTO subcategory (category_id, name) VALUES ('{$category}', '{$subcategoryName}')")){
			send_answer(["Ошибка добавления подкатегории"]);
		}
		$subcategory = dbLastId();
	}
	// Если категория присутствует, а подкатегория новая - создаём подкатегорию
	if($category != "new" && $subcategory == "new"){
		$subcategoryName = verify_field("Название категории товара", $currentOptions['subcategoryName'], 1, 60);
		if(!dbExecute("INSERT INTO subcategory (category_id, name) VALUES ('{$category}', '{$subcategoryName}')")){
			send_answer(["Ошибка добавления подкатегории"]);
		}
		$subcategory = dbLastId();
	}
	// Загружаем основную фотографию
	$photo_path = "/content/".time()."_product.jpg";
	if(!upload_file($photo_path, $photo)){
		send_answer(["Ошибка загрузки фотографии"]);
	}
	// Создаём запись о товаре
	if( dbExecute("INSERT INTO product (name, description, photo, price) VALUES ('{$name}', '{$description}', '{$photo_path}', '{$price}') ") ){
		$product_id = dbLastId();
		// Связываем товар с нужной категорией и подкатегорией
		if(!dbExecute("INSERT INTO product_category (product_id, category_id, subcategory_id) VALUES ('{$product_id}', '{$category}', '{$subcategory}')")){
			send_answer(["Ошибка связи категории с товаром"]);
		}
		// Добавляем дополнительные фото (если есть)
		if($additional_photos != []){
			// Создаём папку товара
			mkdir(ROOTDIR."/content/{$product_id}/");
			$sql_to_execute = "INSERT INTO product_photo (product_id, path) VALUES";
			$i = 0;
			foreach ($additional_photos as $photo_) {
				if($photo_["name"] == null) continue;

				$path_upload = "/content/{$product_id}/".time()."_{$i}.jpg";
				if(upload_file($path_upload, $photo_)){
					$sql_to_execute .= " ('{$product_id}', '{$path_upload}')";
					if($i < count($additional_photos) - 1) $sql_to_execute .= ",";
				} else {
					send_answer(["Ошибка загрузки дополнительной фотографии"]);
				}
				$i++;
			}
			if(!dbExecute($sql_to_execute)){
				send_answer([$sql_to_execute]);
				// send_answer(["Неизвестная ошибка добавления дополнительных фотографий"]);
			}
		}
		// Успех
		send_answer([], true);
	}
	send_answer(["Неизвестная ошибка"]);
}

function product_sale_off(){
	global $currentOptions;
	$product_id = verify_field("ID", $currentOptions['id'], 1, 12);
	if(dbExecute("UPDATE product SET on_sale = 'n' WHERE id = '{$product_id}' LIMIT 1")){
		send_answer([], true);
	}
	send_answer(["Неизвестная ошибка"]);
}

function product_remove(){
	global $currentOptions;
	$product_id = verify_field("ID", $currentOptions['id'], 1, 12);
	if(!removeDirectory("/content/{$product_id}/")){
		send_answer(["Неизвестная ошибка удаления директории"]);
	}
	if(dbExecute("DELETE FROM product WHERE id = '{$product_id}' LIMIT 1")){
		send_answer([], true);
	}
	send_answer(["Неизвестная ошибка"]);
}