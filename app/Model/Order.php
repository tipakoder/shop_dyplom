<?php

namespace App\Model;

use Core\Utils\Answer;
use Core\Utils\ErrorPage;

class Order extends \Core\Base\Model {
    public function __construct($db_config_name = "db") {
        parent::__construct($db_config_name);
    }

    public function index($order_id){
        $this->view = new \App\View\Order();
        if($query = $this->db->queryOne("SELECT * FROM orders WHERE id = ?", [$order_id])){
            $promocode = $this->db->queryOne("SELECT code, percent FROM promocode WHERE id = ? AND active='y'", [$query['promocode_id']]);
            $items = $this->db->query("SELECT product.*, orders_product.count FROM orders_product, product WHERE orders_product.orders_id = ? AND product.id = orders_product.product_id", [$order_id]);
            $this->view->index($query, $items, $promocode);
            exit;
        }
        ErrorPage::page404("Заказ не найден")->launch();
    }

    public function prepare_create($temp_user){
        $deliverys = $this->db->query("SELECT * FROM delivery_service");
        Answer::success(["userData" => $temp_user, "deliverys" => $deliverys]);
    }

    public function create($address, $delivery_id, $promocode, $name, $email, $phone, $notes, $items, $card_number, $card_date, $card_cvc, $card_owner){
        $promocode_id = ($query = $this->db->queryOne("SELECT id FROM promocode WHERE code = ?", [$promocode])) ? $query['id'] : 1;
        $total_price = 0;
        $items_name = "";
        $i = 0;
        foreach ($items as $item) {
            $total_price += intval($item['price']) * intval($item['count']);
            $items_name .= $item['name']."<b>({$item['price']}x{$item['count']})</b>";
            if(count($items)-1 > $i) $items_name .= ", ";
            $i++;
        }
        // Записываем новый заказ в базу
        if(!$this->db->execute("INSERT INTO orders (address, delivery_service_id, name, phone, email, promocode_id, notes, paid) VALUES (?, ?, ?, ?, ?, ?, ?, ?)", [$address, $delivery_id, $name, $phone, $email, $promocode_id, $notes, "y"])){
            Answer::error(["Неизвестная ошибка создания нового заказа"]);
        }
        $order_id = $this->db->lastInsertId();
        // Добавляем предметы к заказу
        $sql_to_execute = "INSERT INTO orders_product (orders_id, product_id, count) VALUES ";
        $sql_options = [];
        $i = 0;
        foreach($items as $item){
            $sql_to_execute .= "(?, ?, ?)";
            $sql_options[] = $order_id;
            $sql_options[] = $item['id'];
            $sql_options[] = $item['count'];
            if(count($items)-1 > $i) $sql_to_execute .= ", ";
            $i++;
        }
        if(!$this->db->execute($sql_to_execute, $sql_options)){
            Answer::error(["Неизвестная ошибка связи предметов с новым заказом"]);
        }
        // Добавляем чек
        if(!$this->db->execute("INSERT INTO orders_check (card_number, card_date, card_cvc, card_owner, orders_id, total_price) VALUES (?, ?, ?, ?, ?, ?)", [$card_number, $card_date, $card_cvc, $card_owner, $order_id, $total_price])){
            Answer::error(["Неизвестная ошибка оплаты"]);
        }
        $check_id = $this->db->lastInsertId();
        Answer::success(["order_id" => $order_id, "check" => ["id" => $check_id, "items" => $items_name, "price" => $total_price]]);
    }

    public function end($order_id){
        if($this->db->execute("UPDATE orders SET end = 'y' WHERE id = ?", [$order_id])){
            Answer::success();
        }

        Answer::error(["Неизвестная ошибка"]);
    }
}