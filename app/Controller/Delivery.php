<?php

namespace App\Controller;

use Core\Utils\Field;
use Core\Utils\Request;

class Delivery extends \Core\Base\Controller {
    public function __construct() {
        $this->model = new \App\Model\Delivery();
        $this->verify_auth();
    }

    public function create(){
        $name = (new Field("Кодовое имя доставки", Request::getOption("name"), [
            "type" => "text",
            "min" => 1,
            "max" => 25
        ]))->check();

        $title = (new Field("Имя доставки", Request::getOption("title"), [
            "type" => "text",
            "min" => 1,
            "max" => 45
        ]))->check();

        $min_price = (new Field("Минимальная ставка", Request::getOption("min_price"), [
            "type" => "text",
            "min" => 1,
            "max" => 45
        ]))->check();

        $this->model->create($name, $title, $min_price);
    }

    public function remove(){
        $delivery_id = (new Field("ID", Request::getOption("id"), [
            "type" => "text",
            "min" => 1,
            "max" => 11
        ]))->check();

        $this->model->remove($delivery_id);
    }
}