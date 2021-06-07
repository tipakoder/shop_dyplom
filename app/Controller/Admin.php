<?php

namespace App\Controller;

class Admin extends \Core\Base\Controller {
    public function __construct(){
        $this->view = new \App\View\Admin();
        $this->model = new \App\Model\Admin();
        $this->verify_auth();
    }

    public function dashboard(){
        $this->view->dashboard();
    }

    public function products(){
        $this->model->products();
    }

    public function orders(){
        $this->model->orders();
    }

    public function promocodes(){
        $this->model->promocodes();
    }

    public function delivery(){
        $this->model->delivery();
    }
}