<?php

namespace App\Model;

use Core\Utils\Answer;
use Core\Utils\ErrorPage;
use Core\Utils\Filesystem;
use Core\Utils\Request;

class Product extends \Core\Base\Model {
    public function __construct($db_config_name = "db") {
        parent::__construct($db_config_name);
        $this->view = new \App\View\Product();
    }

    public function index($product_id){
        if(!($query = $this->db->queryOne("SELECT product.*, category.name as category, category.id as category_id, subcategory.name as subcategory, subcategory.id as subcategory_id FROM product, product_category, category, subcategory WHERE category.id = product_category.category_id AND subcategory.id = product_category.subcategory_id AND product_category.product_id = product.id AND product.id = ?", [$product_id]))){
            ErrorPage::page404("Товар не найден или удалён");
        }

        $query["photos"] = $this->db->query("SELECT path FROM product_photo WHERE product_id = ?", [$product_id]);
        $this->view->index($query);
    }

    public function search($query){
        $query = "%{$query}%";
        if($query = $this->db->query("SELECT product.*, category.name as category, subcategory.name as subcategory FROM product, product_category, category, subcategory WHERE category.id = product_category.category_id AND subcategory.id = product_category.subcategory_id AND product_category.product_id = product.id AND (product.name LIKE ? OR category.name LIKE ? OR subcategory.name LIKE ?) LIMIT 6", [$query, $query, $query])){
            return Answer::success(["products" => $query]);
        }
        return Answer::success(["products" => []]);
    }

    public function get($product_id){
        if($query = $this->db->queryOne("SELECT product.*, category.name as category, subcategory.name as subcategory FROM product, product_category, category, subcategory WHERE category.id = product_category.category_id AND subcategory.id = product_category.subcategory_id AND product_category.product_id = product.id AND product_id = ?", [$product_id])){
            return Answer::success(["product" => $query]);
        }
        return Answer::error(["Товар отсутствует"]);
    }

    public function get_category(){
        $categorys = $this->db->query("SELECT * FROM category");
        return Answer::success(["categorys" => $categorys]);
    }

    public function get_subcategory($category_id){
        if($query = $this->db->query("SELECT * FROM subcategory WHERE category_id = ?", [$category_id])){
            return Answer::success(["subcategorys" => $query]);
        }
        return Answer::error(["Подкатегории не найдены"]);
    }

    public function create($name, $description, $price, $category, $subcategory){
        // Основное фото
        $photo = (isset($_FILES['photo']) && $_FILES['photo']["name"] != null) ? $_FILES['photo'] : null;
        // Дополнительные фото
        $additional_photos = [];
        if(isset($_FILES['photo1']) && $_FILES['photo1']["name"] != null) $additional_photos[] = $_FILES['photo1'];
        if(isset($_FILES['photo2']) && $_FILES['photo2']["name"] != null) $additional_photos[] = $_FILES['photo2'];
        if(isset($_FILES['photo3']) && $_FILES['photo3']["name"] != null) $additional_photos[] = $_FILES['photo3'];
        // Если категория новая - создаём вместе с подкатегорией
        if($category == "new"){
            $categoryName = Request::getOption('categoryName');
            if(!$this->db->execute("INSERT INTO category (name) VALUES (?)", [$categoryName])){
                return Answer::error(["Ошибка добавления категории"]);
            }
            $category = $this->db->lastInsertId();
            $subcategoryName = Request::getOption('subcategoryName');
            if(!$this->db->execute("INSERT INTO subcategory (category_id, name) VALUES (?, ?)", [$category, $subcategoryName])){
                return Answer::error(["Ошибка добавления подкатегории"]);
            }
            $subcategory = $this->db->lastInsertId()();
        }
        // Если категория присутствует, а подкатегория новая - создаём подкатегорию
        if($category != "new" && $subcategory == "new"){
            $subcategoryName = Request::getOption('subcategoryName');
            if(!$this->db->execute("INSERT INTO subcategory (category_id, name) VALUES (?, ?)", [$category, $subcategoryName])){
                return Answer::error(["Ошибка добавления подкатегории"]);
            }
            $subcategory = $this->db->lastInsertId()();
        }
        // Загружаем основную фотографию
        $photo_path = "/app/content/".time()."_product.jpg";
        if(!Filesystem::uploadFile($photo_path, $photo)){
            return Answer::error(["Ошибка загрузки фотографии"]);
        }
        // Создаём запись о товаре
        if( $this->db->execute("INSERT INTO product (name, description, photo, price) VALUES (?, ?, ?, ?) ", [$name, $description, $photo_path, $price]) ){
            $product_id = $this->db->lastInsertId();
            // Связываем товар с нужной категорией и подкатегорией
            if(!$this->db->execute("INSERT INTO product_category (product_id, category_id, subcategory_id) VALUES (?, ?, ?)", [$product_id, $category, $subcategory])){
                return Answer::error(["Ошибка связи категории с товаром"]);
            }
            // Добавляем дополнительные фото (если есть)
            if($additional_photos != []){
                // Создаём папку товара
                mkdir(__ROOTDIR__."/app/content/{$product_id}/");
                $sql_to_execute = "INSERT INTO product_photo (product_id, path) VALUES";
                $sql_options = [];
                $i = 0;
                foreach ($additional_photos as $photo_) {
                    if($photo_["name"] == null) continue;

                    $path_upload = "/app/content/{$product_id}/".time()."_{$i}.jpg";
                    if(Filesystem::uploadFile($path_upload, $photo_)){
                        $sql_to_execute .= " (?, ?)";
                        $sql_options[] = $product_id;
                        $sql_options[] = $path_upload;
                        if($i < count($additional_photos) - 1) $sql_to_execute .= ",";
                    } else {
                        return Answer::error(["Ошибка загрузки дополнительной фотографии"]);
                    }
                    $i++;
                }
                if(!$this->db->execute($sql_to_execute, $sql_options)){
                    return Answer::error(["Неизвестная ошибка добавления дополнительных фотографий"]);
                }
            }
            // Успех
            return Answer::success([]);
        }
        return Answer::error(["Неизвестная ошибка"]);
    }

    public function edit_prepare($product_id){
        $categorys = $this->db->query("SELECT * FROM category");
        if(!($query = $this->db->queryOne("SELECT product.*, category.name as category, subcategory.name as subcategory, category.id as category_id, subcategory.id as subcategory_id FROM product, product_category, category, subcategory WHERE category.id = product_category.category_id AND subcategory.id = product_category.subcategory_id AND product_category.product_id = product.id AND product.id = ?", [$product_id]))){
            return Answer::error(["Товар отсутствует"]);
        }
        $photos = $this->db->query("SELECT * FROM product_photo WHERE product_id = ?", [$product_id]);
        return Answer::success(["product" => $query, "categorys" => $categorys, "photos" => $photos]);
    }

    public function edit($product_id, $name, $description, $price, $category, $subcategory){
        // Основное фото
        $photo = (isset($_FILES['photo']) && $_FILES['photo']["name"] != null) ? $_FILES['photo'] : null;
        // Дополнительные фото
        $additional_photos = [];
        if(isset($_FILES['photo1']) && $_FILES['photo1']["name"] != null) $additional_photos['photo1'] = $_FILES['photo1'];
        if(isset($_FILES['photo2']) && $_FILES['photo2']["name"] != null) $additional_photos['photo2'] = $_FILES['photo2'];
        if(isset($_FILES['photo3']) && $_FILES['photo3']["name"] != null) $additional_photos['photo3'] = $_FILES['photo3'];
        // Если категория новая - создаём вместе с подкатегорией
        if($category == "new"){
            $categoryName = Request::getOption('categoryName');
            if(!$this->db->execute("INSERT INTO category (name) VALUES (?)", [$categoryName])){
                return Answer::error(["Ошибка добавления категории"]);
            }
            $category = $this->db->lastInsertId();
            $subcategoryName = Request::getOption('subcategoryName');
            if(!$this->db->execute("INSERT INTO subcategory (category_id, name) VALUES (?, ?)", [$category, $subcategoryName])){
                return Answer::error(["Ошибка добавления подкатегории"]);
            }
            $subcategory = $this->db->lastInsertId();
        }
        // Если категория присутствует, а подкатегория новая - создаём подкатегорию
        if($category != "new" && $subcategory == "new"){
            $subcategoryName = Request::getOption('subcategoryName');
            if(!$this->db->execute("INSERT INTO subcategory (category_id, name) VALUES (?, ?)", [$category, $subcategoryName])){
                return Answer::error(["Ошибка добавления подкатегории"]);
            }
            $subcategory = $this->db->lastInsertId();
        }
        // Загружаем основную фотографию (если нужно обновить)
        if($photo != null){
            $photo_path = "/app/content/".time()."_product.jpg";
            if(!Filesystem::uploadFile($photo_path, $photo)){
                return Answer::error(["Ошибка загрузки фотографии"]);
            }
            if(!$this->db->execute("UPDATE product SET photo = '{$photo_path}' WHERE id = ? LIMIT 1", [$product_id]) ){
                return Answer::error(["Ошибка обновления фотографии в базе данных"]);
            }
        }
        // Создаём запись о товаре
        if( $this->db->execute("UPDATE product SET name = ?, description = ?, price = ? WHERE id = ? LIMIT 1", [$name, $description, $price, $product_id]) ){
            // Связываем товар с нужной категорией и подкатегорией
            if(!$this->db->execute("UPDATE product_category SET category_id = ?, subcategory_id = ? WHERE product_id = ? LIMIT 1", [$category, $subcategory, $product_id])){
                return Answer::error(["Ошибка связи категории с товаром"]);
            }
            // Добавляем дополнительные фото (если есть)
            if($additional_photos != []){
                // Создаём папку товара
                $path_folder = __ROOTDIR__."/app/content/{$product_id}/";
                if(!file_exists($path_folder)) mkdir($path_folder);
                $i = 0;
                foreach ($additional_photos as $key => $photo_) {
                    if($photo_["name"] == null) continue;

                    $path_upload = "/app/content/{$product_id}/".time()."_{$i}.jpg";
                    if(Filesystem::uploadFile($path_upload, $photo_)){
                        if(Request::getOption($key."_id")){
                            $_photo_id = Request::getOption($key."_id");
                            if(!$this->db->execute("UPDATE product_photo SET path = ? WHERE id = ? LIMIT 1", [$path_upload, $_photo_id])){
                                return Answer::error(["Неизвестная ошибка добавления дополнительных фотографий"]);
                            }
                        } else {
                            if(!$this->db->execute("INSERT INTO product_photo (product_id, path) VALUES (?, ?)", [$product_id, $path_upload])){
                                return Answer::error(["Неизвестная ошибка добавления дополнительных фотографий"]);
                            }
                        }
                    } else {
                        return Answer::error(["Ошибка загрузки дополнительной фотографии"]);
                    }
                    $i++;
                }
            }
            // Успех
            return Answer::success();
        }
        return Answer::error(["Неизвестная ошибка"]);
    }

    function sale_off($product_id){
        if($this->db->execute("UPDATE product SET on_sale = 'n' WHERE id = ? LIMIT 1", [$product_id])){
            return Answer::success([]);
        }
        return Answer::error(["Неизвестная ошибка"]);
    }

    function remove($product_id){
        if(!Filesystem::removeDirectory("/app/content/{$product_id}/") && is_dir("/app/content/{$product_id}/")){
            return Answer::error(["Неизвестная ошибка удаления директории"]);
        }
        if($this->db->execute("DELETE FROM product WHERE id = ? LIMIT 1", [$product_id])){
            return Answer::success([]);
        }
        return Answer::error(["Неизвестная ошибка"]);
    }

}