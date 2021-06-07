<?php

namespace Test;

use App\Model\Promocode;
use PHPUnit\Framework\TestCase;

class PromocodeTest extends TestCase {
    protected $model;
    protected string $code = "test";
    protected int $percent = 100;

    public function setUp(): void {
        if(!defined("__ROOTDIR__")) define("__ROOTDIR__", __DIR__ . "/..");
        \Core\Utils\Answer::$test = true;
        \Core\Utils\Cookie::$test = true;
        $this->model = new \App\Model\Promocode();
        $this->model->test = true;
    }

    public function testCreate(){
        $result = $this->model->create($this->code, $this->percent);
        $this->assertArrayHasKey("type", $result);
        $this->assertStringContainsString("success", $result["type"]);
        $this->assertArrayHasKey("data", $result);
        $this->assertArrayHasKey("promocode_id", $result['data']);
        return $result['data']['promocode_id'];
    }

    /**
     * @depends testCreate
     */
    public function testTurn($promocode_id){
        $result = $this->model->turn($promocode_id);
        $this->assertArrayHasKey("type", $result);
        $this->assertStringContainsString("success", $result["type"]);
    }

    /**
     * @depends testCreate
     */
    public function testRemove($promocode_id){
        $result = $this->model->remove($promocode_id);
        $this->assertArrayHasKey("type", $result);
        $this->assertStringContainsString("success", $result["type"]);
    }

}
