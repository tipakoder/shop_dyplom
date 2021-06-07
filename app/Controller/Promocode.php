<?php

namespace App\Controller;

use Core\Utils\Field;
use Core\Utils\Request;

class Promocode extends \Core\Base\Controller {
    public function __construct() {
        $this->model = new \App\Model\Promocode();
        $this->verify_auth();
    }

    public function create(){
        $code = (new Field("Код скидки", Request::getOption("code"), [
            "type" => "text",
            "min" => 1,
            "max" => 60
        ]))->check();

        $percent = (new Field("Процент скидки", Request::getOption("percent"), [
            "type" => "text",
            "min" => 1,
            "max" => 11
        ]))->check();

        $this->model->create($code, $percent);
    }

    public function turn(){
        $promocode_id = (new Field("ID", Request::getOption("id"), [
            "type" => "text",
            "min" => 1,
            "max" => 11
        ]))->check();

        $this->model->turn($promocode_id);
    }
}