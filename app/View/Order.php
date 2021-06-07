<?php

namespace App\View;

class Order extends \Core\Base\View {
    public function index($order, $items, $promocode){
        $this->setup("order");
        $this->setPageTitle("Заказ #{$order['id']}");
        $this->addOption("order", $order);
        $this->addOption("items", $items);
        $this->addOption("promocode", $promocode);
        $this->render();
    }
}