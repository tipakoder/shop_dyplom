<?php

namespace App\Model;

class Home extends \Core\Base\Model {
    public function __construct($db_config_name = "db") {
        parent::__construct($db_config_name);
        $this->view = new \App\View\Home();
    }

    public function index(){
        $products = $this->db->query("SELECT name, photo, price, id FROM product ORDER BY id DESC LIMIT 8");
        $this->view->index($products);
    }
}