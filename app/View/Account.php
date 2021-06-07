<?php

namespace App\View;

class Account extends \Core\Base\View {
    public function index($account, $orders){
        $this->setup("profile");
        $this->setPageTitle("{$account['name']}");
        $this->addOption("account", $account);
        $this->addOption("orders", $orders);
        $this->render();
    }
}