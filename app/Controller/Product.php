<?php

namespace App\Controller;
use Core\Utils\Field;
use Core\Utils\Request;

class Product extends \Core\Base\Controller {
    public function __construct() {
        $this->model = new \App\Model\Product();
    }

    public function index(){
        $product_id = (isset(Request::getRoute()['regexOptions']) && Request::getRoute()['regexOptions'][1] != null) ? Request::getRoute()['regexOptions'][1] : 0;
        $this->model->index($product_id);
    }

    public function get(){
        $id = (new Field("ID", Request::getOption("id"), [
            "type" => "text",
            "min" => 1,
            "max" => 11
        ]))->check();

        $this->model->get($id);
    }

    public function search(){
        $query = (new Field("Запрос", Request::getOption("query"), [
            "type" => "text",
            "min" => 1,
            "max" => 45
        ]))->check();

        $this->model->search($query);
    }

    public function get_category(){
        $this->verify_auth();
        $this->model->get_category();
    }

    public function get_subcategory(){
        $this->verify_auth();
        $category_id = (new Field("ID Категории", Request::getOption("id"), [
            "type" => "text",
            "min" => 1,
            "max" => 11
        ]))->check();

        $this->model->get_subcategory($category_id);
    }

    public function create(){
        $this->verify_auth();
        $name = (new Field("Имя продукта", Request::getOption("name"), [
            "type" => "text",
            "min" => 1,
            "max" => 120
        ]))->check();

        $description = (new Field("Описание продукта", Request::getOption("description"), [
            "type" => "text",
            "min" => 1,
            "max" => 6000
        ]))->check();

        $price = (new Field("Цена продукта", Request::getOption("price"), [
            "type" => "text",
            "min" => 1,
            "max" => 10
        ]))->check();

        $category = (new Field("Категория", Request::getOption("category"), [
            "type" => "text",
            "min" => 1,
            "max" => 11
        ]))->check();

        $subcategory = (new Field("Подкатегория", Request::getOption("subcategory"), [
            "type" => "text",
            "min" => 1,
            "max" => 11
        ]))->check();

        $this->model->create($name, $description, $price, $category, $subcategory);
    }

    public function edit_prepare(){
        $this->verify_auth();
        $id = (new Field("ID", Request::getOption("id"), [
            "type" => "text",
            "min" => 1,
            "max" => 11
        ]))->check();

        $this->model->edit_prepare($id);
    }

    public function edit(){
        $this->verify_auth();
        $id = (new Field("ID", Request::getOption("id"), [
            "type" => "text",
            "min" => 1,
            "max" => 11
        ]))->check();

        $name = (new Field("Имя продукта", Request::getOption("name"), [
            "type" => "text",
            "min" => 1,
            "max" => 120
        ]))->check();

        $description = (new Field("Описание продукта", Request::getOption("description"), [
            "type" => "text",
            "min" => 1,
            "max" => 6000
        ]))->check();

        $price = (new Field("Цена продукта", Request::getOption("price"), [
            "type" => "text",
            "min" => 1,
            "max" => 10
        ]))->check();

        $category = (new Field("Категория", Request::getOption("category"), [
            "type" => "text",
            "min" => 1,
            "max" => 11
        ]))->check();

        $subcategory = (new Field("Подкатегория", Request::getOption("subcategory"), [
            "type" => "text",
            "min" => 1,
            "max" => 11
        ]))->check();

        $this->model->edit($id, $name, $description, $price, $category, $subcategory);
    }

    public function sale_off(){
        $this->verify_auth();
        $product_id = (new Field("ID", Request::getOption("id"), [
            "type" => "text",
            "min" => 1,
            "max" => 11
        ]))->check();

        $this->model->sale_off($product_id);
    }

    public function remove(){
        $this->verify_auth();
        $product_id = (new Field("ID", Request::getOption("id"), [
            "type" => "text",
            "min" => 1,
            "max" => 11
        ]))->check();

        $this->model->remove($product_id);
    }
}