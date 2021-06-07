<?php

namespace Test;

use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase {
    protected $model;

    public function setUp(): void {
        if(!defined("__ROOTDIR__")) define("__ROOTDIR__", __DIR__ . "/..");
        \Core\Utils\Answer::$test = true;
        \Core\Utils\Cookie::$test = true;
        $this->model = new \App\Model\Product();
        $this->model->test = true;
    }

    public function testSearch() {
        $result = $this->model->search("бейсболка");
        $this->assertArrayHasKey("type", $result);
        $this->assertStringContainsString("success", $result["type"]);
    }

    public function testGet_category() {
        $result = $this->model->get_category();
        $this->assertArrayHasKey("type", $result);
        $this->assertStringContainsString("success", $result["type"]);
    }

    public function testGet_subcategory() {
        $result = $this->model->get_subcategory(12);
        $this->assertArrayHasKey("type", $result);
        $this->assertStringContainsString("success", $result["type"]);
    }

    public function testGet() {
        $result = $this->model->get(12);
        $this->assertArrayHasKey("type", $result);
        $this->assertStringContainsString("success", $result["type"]);
    }
}
