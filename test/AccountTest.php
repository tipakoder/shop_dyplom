<?php

namespace Test;

use App\Controller\Account;
use PHPUnit\Framework\TestCase;

class AccountTest extends TestCase {
    protected $model;
    protected string $login = "unit";
    protected string $password = "unit";
    protected string $email = "test@phpunit.php";
    protected string $telephone = "00000000000";
    protected string $name = "test";
    protected string $session_key;

    public function setUp(): void {
        if(!defined("__ROOTDIR__")) define("__ROOTDIR__", __DIR__ . "/..");
        \Core\Utils\Answer::$test = true;
        \Core\Utils\Cookie::$test = true;
        $this->model = new \App\Model\Account();
        $this->model->test = true;
    }

    public function testReg() {
        $result = $this->model->reg($this->name, $this->email, $this->telephone, $this->login, $this->password);
        $this->assertArrayHasKey("type", $result);
        $this->assertStringContainsString("success", $result["type"]);
        $this->assertArrayHasKey("data", $result);
        $this->assertArrayHasKey("session_key", $result['data']);
    }

    public function testAuth() {
        $result = $this->model->auth($this->login, $this->password);
        $this->assertArrayHasKey("type", $result);
        $this->assertStringContainsString("success", $result["type"]);
        $this->assertArrayHasKey("data", $result);
        $this->assertArrayHasKey("session_key", $result['data']);
        return $result['data']['session_key'];
    }

    /**
     * @depends testAuth
     */
    public function testVerifyAuth($session_key){
        $result = $this->model->verify_auth($session_key);
        $this->assertArrayHasKey("id", $result);
        $this->assertArrayHasKey("name", $result);
        $this->assertArrayHasKey("email", $result);
        $this->assertArrayHasKey("telephone", $result);
        $this->assertArrayHasKey("login", $result);
        $this->assertArrayHasKey("password", $result);
        return $this->model;
    }

    /**
     * @depends testVerifyAuth
     */
    public function testEdit($model) {
        $this->name = $this->name."edited";
        $model->edit($this->name, $this->email, $this->telephone, $this->login);
        $result = $model->verify_auth();
        $this->assertArrayHasKey("name", $result);
        $this->assertStringContainsString($this->name, $result['name']);
    }

    /**
     * @depends testVerifyAuth
     */
    public function testChangePassword($model) {
        $old_password = $this->password;
        $this->password = $this->password."edited";
        $result = $model->changePassword($old_password, $this->password);
        $this->assertArrayHasKey("type", $result);
        $this->assertStringContainsString("success", $result["type"]);
        $result = $this->model->auth($this->login, $this->password);
        $this->assertArrayHasKey("type", $result);
        $this->assertStringContainsString("success", $result["type"]);
        $this->assertArrayHasKey("data", $result);
        $this->assertArrayHasKey("session_key", $result['data']);
        return $result['data']['session_key'];
    }

    /**
     * @depends testVerifyAuth
     */
    public function testRemove($model) {
        $result = $model->remove();
        $this->assertArrayHasKey("type", $result);
        $this->assertStringContainsString("success", $result["type"]);
    }
}
