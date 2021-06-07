<?php

namespace App\View;

class Admin extends \Core\Base\View {
    public function dashboard(){
        $this->setup("admin/dashboard");
        $this->setPageTitle("Панель администратора");
        $this->render();
    }

    public function products($products){
        $this->setup("admin/products");
        $this->setPageTitle("Панель администратора — продукция");
        $this->addOption("products", $products);
        $this->render();
    }

    public function orders($orders){
        $this->setup("admin/orders");
        $this->setPageTitle("Панель администратора — заказы");
        $this->addOption("orders", $orders);
        $this->render();
    }

    public function promocodes($promocodes){
        $this->setup("admin/promocodes");
        $this->setPageTitle("Панель администратора — промокоды");
        $this->addOption("promocodes", $promocodes);
        $this->render();
    }

    public function delivery($delivery){
        $this->setup("admin/delivery");
        $this->setPageTitle("Панель администратора — службы доставки");
        $this->addOption("delivery", $delivery);
        $this->render();
    }
}