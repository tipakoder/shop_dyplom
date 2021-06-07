<?php


namespace App\Controller;


class Home extends \Core\Base\Controller {
    public function __construct(){
        $this->model = new \App\Model\Home();
        $this->view = new \App\View\Home();
    }

    public function index(){
        $this->model->index();
    }

    function cart(){
        $this->view->cart();
    }

    function favorite(){
        $this->view->favorite();
    }
}