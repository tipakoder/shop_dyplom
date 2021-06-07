<?php

namespace App\Controller;
use Core\Utils\Request;

class Error extends \Core\Base\Controller {
    public function __construct() {
        $this->view = new \App\View\Error();
    }

    public function codePage(){
        $this->view->codePage(Request::getOption("code"), Request::getOption("text"));
    }
}