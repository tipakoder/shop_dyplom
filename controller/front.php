<?php

// Index page (/)
function main(){
    load_view("main", "SHOP.d");
}

// Cart page (/cart/)
function cart(){
    load_view("cart", "SHOP.d - Корзина");
}

// Favorite page (/favorite/)
function favorite(){
    load_view("favorite", "SHOP.d - Избранные");
}

// Product page (/product/{id}/)
function product($options){
	if(isset($options[1])) $product_id = $options[1];
	
}