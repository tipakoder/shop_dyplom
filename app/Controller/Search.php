<?php

namespace App\Controller;

class Search extends \Core\Base\Controller {
    public function __construct(){
        $this->model = new \App\Model\Search();
    }
    public function index(){
        $this->model->index();
    }
}