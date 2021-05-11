<?php

// Index page (/)
function main(){
    load_view("main", "SHOP - Главная");
}

// Cart page (/cart/)
function cart(){
    load_view("cart", "SHOP - Корзина");
}