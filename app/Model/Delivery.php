<?php

namespace App\Model;
use Core\Utils\Answer;

class Delivery extends \Core\Base\Model {
    public function create($name, $title, $min_price){
        if($this->db->execute("INSERT INTO delivery_service (name, title, min_price) VALUES (?, ?, ?)", [$name, $title, $min_price])){
            Answer::success([]);
        }

        Answer::error(["Неизвестная ошибка добавления службы доставки"]);
    }

    public function remove($delivery_id){
        if($this->db->execute("DELETE FROM delivery_service WHERE id = ?", [$delivery_id])){
            Answer::success([]);
        }

        Answer::error(["Неизвестная ошибка удаления службы доставки"]);
    }
}