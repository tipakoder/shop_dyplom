<?php

namespace App\View;

class Search extends \Core\Base\View {
    public function index($products, $filters, $categorys, $subcategorys){
        $this->setup("search");
        $this->setPageTitle("Поиск продукции");
        $this->addOption("products", $products);
        $this->addOption("filters", $filters);
        $this->addOption("categorys", $categorys);
        $this->addOption("subcategorys", $subcategorys);
        $this->render();
    }
}