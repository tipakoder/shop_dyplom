<?php

namespace App\Model;

class Search extends \Core\Base\Model {
    public function __construct($db_config_name = "db") {
        parent::__construct($db_config_name);
        $this->view = new \App\View\Search();
    }

    public function index(){
        $sql = "SELECT product.*, category.name as category, subcategory.name as subcategory FROM product, product_category, category, subcategory WHERE category.id = product_category.category_id AND subcategory.id = product_category.subcategory_id AND product_category.product_id = product.id";
        $sql_options = [];
        $filters = [];
        $categorys = $this->db->query("SELECT * FROM category");
        $subcategorys = [];

        // Фильтры (id категории, id подкатегории, название)
        if(isset($_GET["category"]) && ($query = $this->db->queryOne("SELECT name FROM category WHERE id = ?", [$_GET["category"]]))) {
            $sql .= " AND category.id = ?";
            $sql_options[] = $_GET["category"];
            $category_name = $query['name'];
            $subcategorys = $this->db->query("SELECT * FROM subcategory WHERE category_id = ?", [$_GET["category"]]);
            $filters[] = ["title" => $category_name, "link" => "/search?category={$_GET["category"]}"];
        }
        if(isset($_GET["subcategory"]) && ($query = $this->db->queryOne("SELECT name FROM subcategory WHERE id = ?", [$_GET["subcategory"]]))) {
            $sql .= " AND subcategory.id = ?";
            $sql_options[] = $_GET["subcategory"];
            $subcategory_name = $query['name'];
            $filters[] = ["title" => $subcategory_name, "link" => "/search?subcategory={$_GET["subcategory"]}"];
        }
        if(isset($_GET["q"])) {
            $sql .= " AND product.name LIKE ?";
            $sql_options[] = "%{$_GET["q"]}%";
            $filters[] = ["title" => '"'.$_GET["q"].'"', "link" => "/search?q={$_GET["q"]}"];
        }
        $products = $this->db->query($sql, $sql_options);
        $this->view->index($products, $filters, $categorys, $subcategorys);
    }
}