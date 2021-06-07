<?php

namespace Core\Base;
use Core\Main\Database\Connection;
use Core\Utils\Answer;

abstract class Model {
    protected Connection $db;
    protected View $view;
    public bool $test = false;

    public function __construct($db_config_name = "db") {
        $this->db = new Connection($db_config_name);
    }
}