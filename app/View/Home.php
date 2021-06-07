<?php

namespace App\View;

class Home extends \Core\Base\View {
    public function index($products){
        $this->setup("main");
        $this->setPageTitle("Интернет-магазин");
        $this->addOption("products", $products);
        $this->render();
    }

    public function cart(){
        $this->setup("cart");
        $this->setPageTitle("Корзина");
        $this->render();
    }

    public function favorite(){
        $this->setup("favorite");
        $this->setPageTitle("Избранные продукты");
        $this->render();
    }
}