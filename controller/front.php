<?php

// ---------------------- USER

// Index page (/)
function main(){
	$best_deals = dbQuery("SELECT name, photo, price, id FROM product ORDER BY id DESC LIMIT 8");
    load_view("main", "SHOP.d", ["best_deals" => $best_deals]);
}

// Cart page (/cart/)
function cart(){
    load_view("cart", "SHOP.d — Корзина");
}

// Favorite page (/favorite/)
function favorite(){
    load_view("favorite", "SHOP.d — Избранные");
}

function search(){
	$sql = "SELECT product.*, category.name as category, subcategory.name as subcategory FROM product, product_category, category, subcategory WHERE category.id = product_category.category_id AND subcategory.id = product_category.subcategory_id AND product_category.product_id = product.id";
	$filters = [];
	$categorys = dbQuery("SELECT * FROM category");
	$subcategorys = [];

	// Фильтры (id категории, id подкатегории, название)
	if(isset($_GET["category"]) && ($query = dbQueryOne("SELECT name FROM category WHERE id = '{$_GET["category"]}'"))) {
		$sql .= " AND category.id = '{$_GET["category"]}'";
		$category_name = $query['name'];
		$subcategorys = dbQuery("SELECT * FROM subcategory WHERE category_id = '{$_GET["category"]}'");
		$filters[] = ["title" => $category_name, "link" => "/search?category={$_GET["category"]}"];
	}
	if(isset($_GET["subcategory"]) && ($query = dbQueryOne("SELECT name FROM subcategory WHERE id = '{$_GET["subcategory"]}'"))) {
		$sql .= " AND subcategory.id = '{$_GET["subcategory"]}'";
		$subcategory_name = $query['name'];
		$filters[] = ["title" => $subcategory_name, "link" => "/search?subcategory={$_GET["subcategory"]}"];
	}
	if(isset($_GET["q"])) {
		$sql .= " AND product.name LIKE '%{$_GET["q"]}%'";
		$filters[] = ["title" => '"'.$_GET["q"].'"', "link" => "/search?q={$_GET["q"]}"];
	}

	$products = dbQuery($sql);
	load_view("search", "SHOP.d — Поиск", ["products" => $products, "filters" => $filters, "categorys" => $categorys, "subcategorys" => $subcategorys]);
}

// Product page (/product/{id}/)
function product($options){
	$product_id = (isset($options[0][1]) && $options[0][1] != null) ? $options[0][1] : 0;
	if( $query = dbQueryOne("SELECT product.*, category.name as category, subcategory.name as subcategory FROM product, product_category, category, subcategory WHERE category.id = product_category.category_id AND subcategory.id = product_category.subcategory_id AND product_category.product_id = product.id AND product.id = '{$product_id}'") ){
		$query["photos"] = dbQuery("SELECT path FROM product_photo WHERE product_id = '{$product_id}'");
		load_view("product", "SHOP.d — {$query['name']}", ["product" => $query]);
	} else {
		load_error(404, "Товар не найден");
	}
}

// User profile (/profile/)
function profile(){
	global $currentUser;
	load_view("profile", "Личный кабинет — ".$currentUser['name']);
}

// Logout (/logout/)
function logout(){
	dbExecute("UPDATE account_session SET active = 0 WHERE sessionkey = '{$_COOKIE['authsession']}' LIMIT 1");
	setcookie("authsession", null, time()-3600, "/");
	header("Location: /");
}

// ---------------------- ADMIN

// Dashboard page (/admin/)
function admin_dashboard(){
	load_view("admin/dashboard", "Панель администратора");
}

// Products page (/admin/products/)
function admin_products(){
	$products = dbQuery("SELECT * FROM product ORDER BY id DESC");
	load_view("admin/products", "Панель администратора — товары", ["products" => $products]);
}

// Orders page (/admin/orders/)
function admin_orders(){
	load_view("admin/orders", "Панель администратора — заказы");
}

// Promocodes page (/admin/promocodes/)
function admin_promocodes(){
	$promocodes = dbQuery("SELECT * FROM promocode");
	load_view("admin/promocodes", "Панель администратора — промокоды", ["promocodes" => $promocodes]);
}

// Delivery page (/admin/delivery/)
function admin_delivery(){
	$delivery = dbQuery("SELECT * FROM delivery_service");
	load_view("admin/delivery", "Панель администратора — службы доставки", ["delivery" => $delivery]);
}