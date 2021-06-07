<?php

namespace App\Controller;

use Core\Utils\Field;
use Core\Utils\Request;

class Order extends \Core\Base\Controller {
    public function __construct() {
        $this->model = new \App\Model\Order();
        $this->verify_auth();
    }

    public function index(){
        $order_id = (isset(Request::getRoute()['regexOptions']) && Request::getRoute()['regexOptions'][1] != null) ? Request::getRoute()['regexOptions'][1] : 0;
        $this->model->index($order_id);
    }

    public function prepare_create(){
        $this->model->prepare_create($this->temp_user);
    }

    public function create(){
        $address = (new Field("Адрес", Request::getOption("address"), [
            "type" => "text",
            "min" => 4,
            "max" => 120
        ]))->check();

        $delivery_id = (new Field("Служба доставки", Request::getOption("delivery"), [
            "type" => "text",
            "min" => 1,
            "max" => 11
        ]))->check();

        $promocode = (new Field("Промокод", Request::getOption("promocode"), [
            "type" => "text",
            "max" => 120
        ]))->check();

        $name = (new Field("Имя", Request::getOption("name"), [
            "type" => "text",
            "min" => 4,
            "max" => 120
        ]))->check();

        $email = (new Field("Email", Request::getOption("email"), [
            "type" => "text",
            "min" => 4,
            "max" => 120
        ]))->check();

        $phone = (new Field("Телефон", Request::getOption("phone"), [
            "type" => "text",
            "min" => 4,
            "max" => 120
        ]))->check();

        $notes = (new Field("Телефон", Request::tryGetOption("notes"), [
            "type" => "text",
            "max" => 600
        ]))->check();

        $items = json_decode(Request::getOption("items"), true);

        $card_number = (new Field("Номер карты", Request::getOption("card-number"), [
            "type" => "text",
            "min" => 4,
            "max" => 16
        ]))->check();

        $card_date = (new Field("Срок", Request::getOption("card-date"), [
            "type" => "text",
            "min" => 4,
            "max" => 5
        ]))->check();

        $card_cvc = (new Field("CVC код", Request::getOption("card-cvc"), [
            "type" => "text",
            "min" => 3,
            "max" => 120
        ]))->check();

        $card_owner = (new Field("Владелец", Request::getOption("card-owner"), [
            "type" => "text",
            "min" => 4,
            "max" => 255
        ]))->check();

        $this->model->create($address, $delivery_id, $promocode, $name, $email, $phone, $notes, $items, $card_number, $card_date, $card_cvc, $card_owner);
    }

    public function end(){
        $order_id = (new Field("ID", Request::getOption("id"), [
            "type" => "text",
            "min" => 1,
            "max" => 11
        ]))->check();

        $this->model->end($order_id);
    }
}