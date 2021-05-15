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