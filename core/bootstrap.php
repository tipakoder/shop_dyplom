<?php
// Подгружаем все классы проекта
require_once __ROOTDIR__."/vendor/autoload.php";
// Создаём экземпляр приложения
$app = new \App\Application();
// Запускаем
$app->launch();