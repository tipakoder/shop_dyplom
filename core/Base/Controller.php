<?php

namespace Core\Base;

use App\Model\Account;
use Core\Utils\Answer;
use Core\Utils\ErrorPage;

abstract class Controller{
    protected Model $model;
    protected View $view;
    protected array $temp_user;

    public function verify_auth(){
        $accountModel = new Account();
        $this->temp_user = $accountModel->verify_auth();
    }
}