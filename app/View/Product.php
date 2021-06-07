<?php

namespace App\View;

class Product extends \Core\Base\View {
    public function index($product){
        $this->setup("product");
        $this->setPageTitle($product['name']);
        $this->addOption("product", $product);
        $this->render();
    }
}