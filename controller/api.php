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

	if($query = dbQueryOne("SELECT id, password FROM account WHERE login = '{$login}' OR email = '{$email}' OR telephone = '{$telephone}'")){
		send_answer(["Аккаунт с введённым логином, эл. почтой или телефоном уже существует"]);
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

function change_password(){
	global $currentOptions, $currentUser;
	$oldpassword = verify_field("Старый пароль", $currentOptions['oldpassword'], 4, 0);
	$newpassword = password_hash(verify_field("Новый пароль", $currentOptions['newpassword'], 4, 0), PASSWORD_DEFAULT);

	if(!password_verify($oldpassword, $currentUser['password'])){
		send_answer(["Старый пароль неверен"]);
	}

	if(dbExecute("UPDATE account SET password = '{$newpassword}' WHERE id = '{$currentUser['id']}' LIMIT 1")){
		send_answer([], true);
	}
	send_answer(["Ошибка сохранения пароля"]);
}

function prepare_new_order(){
	global $currentUser;
	$deliverys = dbQuery("SELECT * FROM delivery_service");
	send_answer(["userData" => $currentUser, "deliverys" => $deliverys], true);
}

function new_order(){
	global $currentOptions, $currentUser;
	$address = verify_field("Адрес", $currentOptions['address'], 4, 120);
	$delivery_id = verify_field("Служба доставки", $currentOptions['delivery'], 1, 11);
	$promocode = verify_field("Промокод", $currentOptions['promocode'], 0, 120);
	$promocode_id = ($query = dbQueryOne("SELECT id FROM promocode WHERE code = '{$promocode}'")) ? $query['id'] : 1;
	$name = verify_field("Имя", $currentOptions['name'], 4, 120);
	$email = verify_field("Email", $currentOptions['email'], 4, 120);
	$phone = verify_field("Телефон", $currentOptions['phone'], 4, 120);
	$notes = ($currentOptions['notes'] != null) ? verify_field("Комментарий к заказу", $currentOptions['notes'], 0, 600) : "";
	$items = ($currentOptions['items']) ? json_decode($currentOptions['items'], true) : send_answer(["Предметы не обнаруженны"]);
	// Чек
	$card_number = verify_field("Номер карты", $currentOptions['card-number'], 4, 16);
	$card_date = verify_field("Срок", $currentOptions['card-date'], 4, 5);
	$card_cvc = verify_field("CVC код", $currentOptions['card-cvc'], 3, 120);
	$card_owner = verify_field("Владелец", $currentOptions['card-owner'], 4, 255);
	$total_price = 0;
	$items_name = "";
	$i = 0;
	foreach ($items as $item) {
		$total_price += intval($item['price']) * intval($item['count']);
		$items_name .= $item['name']."<b>({$item['price']}x{$item['count']})</b>";
		if(count($items)-1 > $i) $items_name .= ", ";
		$i++;
	}
	// Записываем новый заказ в базу
	if(!dbExecute("INSERT INTO orders (address, delivery_service_id, name, phone, email, promocode_id, notes) VALUES ('{$address}', '{$delivery_id}', '{$name}', '{$phone}', '{$email}', '{$promocode_id}', '{$notes}')")){
		send_answer(["Неизвестная ошибка создания нового заказа"]);
	}
	$order_id = dbLastId();
	// Добавляем предметы к заказу
	$sql_to_execute = "INSERT INTO orders_product (orders_id, product_id, count) VALUES ";
	$i = 0;
	foreach($items as $item){
		$sql_to_execute .= "('{$order_id}', '{$item['id']}', '{$item['count']}')";
		if(count($items)-1 > $i) $sql_to_execute .= ", ";
		$i++;
	}
	if(!dbExecute($sql_to_execute)){
		send_answer(["Неизвестная ошибка связи предметов с новым заказом"]);
	}
	// Добавляем чек
	if(!dbExecute("INSERT INTO orders_check (card_number, card_date, card_cvc, card_owner, orders_id, total_price) VALUES ('{$card_number}', '{$card_date}', '{$card_cvc}', '{$card_owner}', '{$order_id}', '{$total_price}')")){
		send_answer(["Неизвестная ошибка оплаты"], false);
	}
	$check_id = dbLastId();
	send_answer(["order_id" => $order_id, "check" => ["id" => $check_id, "items" => $items_name, "price" => $total_price]], true);
}

function end_order(){
	global $currentOptions, $currentUser;
	$order_id = $currentOptions['id'];

	if(dbExecute("UPDATE orders SET end = 'y' WHERE id = '{$order_id}'")){
		send_answer([], true);
	}

	send_answer(["Неизвестная ошибка"]);
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
	$min_price = verify_field("Минимальная цена", $currentOptions['min_price'], 1, 45);

	if(dbExecute("INSERT INTO delivery_service (name, title, min_price) VALUES ('{$name}', '{$title}', '{$min_price}')")){
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
	$photo = (isset($_FILES['photo']) && $_FILES['photo']["name"] != null) ? $_FILES['photo'] : null;
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
				send_answer(["Неизвестная ошибка добавления дополнительных фотографий"]);
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

function edit_product_get(){
	global $currentOptions;
	$product_id = verify_field("ID", $currentOptions['id'], 1, 12);
	$categorys = dbQuery("SELECT * FROM category");
	if($query = dbQueryOne("SELECT product.*, category.name as category, subcategory.name as subcategory, category.id as category_id, subcategory.id as subcategory_id FROM product, product_category, category, subcategory WHERE category.id = product_category.category_id AND subcategory.id = product_category.subcategory_id AND product_category.product_id = product.id AND product.id = '{$product_id}'")){
		$photos = dbQuery("SELECT * FROM product_photo WHERE product_id = '{$product_id}'");
		send_answer(["product" => $query, "categorys" => $categorys, "photos" => $photos], true);
	}
	send_answer(["Товар отсутствует"]);
}

function edit_product_process(){
	global $currentOptions;
	// ID продукта
	$product_id = verify_field("ID", $currentOptions['id'], 1, 12);
	// Наименования товара
	$name = verify_field("Наименование товара", $currentOptions['name'], 1, 120);
	// Описание товара
	$description = verify_field("Описание товара", $currentOptions['description'], 1, 6000);
	// Цена товара
	$price = verify_field("Цена товара", $currentOptions['price'], 1, 10);
	// Основное фото
	$photo = (isset($_FILES['photo']) && $_FILES['photo']["name"] != null) ? $_FILES['photo'] : null;
	// Дополнительные фото
	$additional_photos = [];
	if(isset($_FILES['photo1']) && $_FILES['photo1']["name"] != null) $additional_photos['photo1'] = $_FILES['photo1'];
	if(isset($_FILES['photo2']) && $_FILES['photo2']["name"] != null) $additional_photos['photo2'] = $_FILES['photo2'];
	if(isset($_FILES['photo3']) && $_FILES['photo3']["name"] != null) $additional_photos['photo3'] = $_FILES['photo3'];
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
	// Загружаем основную фотографию (если нужно обновить)
	if($photo != null){
		$photo_path = "/content/".time()."_product.jpg";
		if(!upload_file($photo_path, $photo)){
			send_answer(["Ошибка загрузки фотографии"]);
		}
		if(!dbExecute("UPDATE product SET photo = '{$photo_path}' WHERE id = '{$product_id}' LIMIT 1") ){
			send_answer(["Ошибка обновления фотографии в базе данных"]);
		}
	}
	// Создаём запись о товаре
	if( dbExecute("UPDATE product SET name = '{$name}', description = '{$description}', price = '{$price}' WHERE id = '{$product_id}' LIMIT 1") ){
		// Связываем товар с нужной категорией и подкатегорией
		if(!dbExecute("UPDATE product_category SET category_id = '{$category}', subcategory_id = '{$subcategory}' WHERE product_id = '{$product_id}' LIMIT 1")){
			send_answer(["Ошибка связи категории с товаром"]);
		}
		// Добавляем дополнительные фото (если есть)
		if($additional_photos != []){
			// Создаём папку товара
			$path_folder = ROOTDIR."/content/{$product_id}/";
			if(!file_exists($path_folder)) mkdir($path_folder);
			$i = 0;
			foreach ($additional_photos as $key => $photo_) {
				if($photo_["name"] == null) continue;

				$path_upload = "/content/{$product_id}/".time()."_{$i}.jpg";
				if(upload_file($path_upload, $photo_)){
					if(isset($currentOptions[$key."_id"])){
						$_photo_id = $currentOptions[$key."_id"];
						if(!dbExecute("UPDATE product_photo SET path = '{$path_upload}' WHERE id = '{$_photo_id}' LIMIT 1")){
							send_answer(["Неизвестная ошибка добавления дополнительных фотографий"]);
						}
					} else {
						if(!dbExecute("INSERT INTO product_photo (product_id, path) VALUES ('{$product_id}', '{$path_upload}')")){
							send_answer(["Неизвестная ошибка добавления дополнительных фотографий"]);
						}
					}
				} else {
					send_answer(["Ошибка загрузки дополнительной фотографии"]);
				}
				$i++;
			}
		}
		// Успех
		send_answer([], true);
	}
	send_answer(["Неизвестная ошибка"]);
}

function product_search(){
	global $currentOptions;
	$query = strtolower(verify_field("Текст запроса", $currentOptions['query'], 1, 120));
	if($query = dbQuery("SELECT product.*, category.name as category, subcategory.name as subcategory FROM product, product_category, category, subcategory WHERE category.id = product_category.category_id AND subcategory.id = product_category.subcategory_id AND product_category.product_id = product.id AND (product.name LIKE '%{$query}%' OR category.name LIKE '%{$query}%' OR subcategory.name LIKE '%{$query}%') LIMIT 6")){
		send_answer(["products" => $query], true);
	}
	send_answer(["products" => []], true);
}

function product_get(){
	global $currentOptions;
	$product_id = verify_field("ID", $currentOptions['id'], 1, 12);
	if($query = dbQueryOne("SELECT product.*, category.name as category, subcategory.name as subcategory FROM product, product_category, category, subcategory WHERE category.id = product_category.category_id AND subcategory.id = product_category.subcategory_id AND product_category.product_id = product.id AND product_id = '{$product_id}'")){
		send_answer(["product" => $query], true);
	}
	send_answer(["Товар отсутствует"]);
}