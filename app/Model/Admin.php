<?php

namespace App\Model;

class Admin extends \Core\Base\Model {
    public function __construct($db_config_name = "db") {
        parent::__construct($db_config_name);
        $this->view = new \App\View\Admin();
    }

    public function products(){
        $products = $this->db->query("SELECT * FROM product ORDER BY id DESC");
        $this->view->products($products);
    }

    public function orders(){
        $orders = $this->db->query("SELECT * FROM orders ORDER BY id DESC");
        $this->view->orders($orders);
    }

    public function promocodes(){
        $promocodes = $this->db->query("SELECT * FROM promocode");
        $this->view->promocodes($promocodes);
    }

    public function delivery(){
        $delivery = $this->db->query("SELECT * FROM delivery_service");
        $this->view->delivery($delivery);
    }
}