<?php

namespace App\Controller;

use Core\Utils\Field;
use Core\Utils\Request;

class Account extends \Core\Base\Controller {
    public function __construct(){
        $this->model = new \App\Model\Account();
    }

    public function index(){
        $this->model->index();
    }

    public function auth(){
        $login = (new Field("Логин", Request::getOption("login"), [
            "type" => "text",
            "min" => 4,
            "max" => 45
        ]))->check();

        $password = (new Field("Пароль", Request::getOption("password"), [
            "type" => "text",
            "min" => 4
        ]))->check();

        $this->model->auth($login, $password);
    }

    public function reg(){
        $name = (new Field("Имя", Request::getOption("name"), [
            "type" => "text",
            "min" => 4,
            "max" => 60
        ]))->check();

        $email = (new Field("Электронная почта", Request::getOption("email"), [
            "type" => "text",
            "min" => 4,
            "max" => 120
        ]))->check();

        $telephone = (new Field("Телефон", Request::getOption("telephone"), [
            "type" => "text",
            "min" => 10,
            "max" => 12
        ]))->check();

        $login = (new Field("Логин", Request::getOption("login"), [
            "type" => "text",
            "min" => 4,
            "max" => 45
        ]))->check();

        $password = (new Field("Пароль", Request::getOption("password"), [
            "type" => "text",
            "min" => 4
        ]))->check();

        $this->model->reg($name, $email, $telephone, $login, $password);
    }

    public function edit(){
        $name = (new Field("Имя", Request::tryGetOption("name"), [
            "type" => "text",
            "min" => 4,
            "max" => 60
        ]))->check();

        $email = (new Field("Электронная почта", Request::tryGetOption("email"), [
            "type" => "text",
            "min" => 4,
            "max" => 120
        ]))->check();

        $telephone = (new Field("Телефон", Request::tryGetOption("telephone"), [
            "type" => "text",
            "min" => 10,
            "max" => 12
        ]))->check();

        $login = (new Field("Логин", Request::tryGetOption("login"), [
            "type" => "text",
            "min" => 4,
            "max" => 45
        ]))->check();

        $this->model->edit($name, $email, $telephone, $login);
    }

    public function changePassword(){
        $old_password = (new Field("Старый пароль", Request::getOption("oldpassword"), [
            "type" => "text",
            "min" => 4,
        ]))->check();

        $new_password = (new Field("Старый пароль", Request::getOption("newpassword"), [
            "type" => "text",
            "min" => 4,
        ]))->check();

        $this->model->changePassword($old_password, $new_password);
    }

    public function logout(){
        $this->model->verify_auth();
        $this->model->logout();
    }
}