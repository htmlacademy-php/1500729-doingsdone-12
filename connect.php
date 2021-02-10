<?php
$db = [
    'host' => '127.0.0.1',
    'login' => 'root',
    'password' => '123456',
    'base' => 'doingsdone'
];

date_default_timezone_set ('Europe/Moscow');

$link = mysqli_connect ($db['host'], $db['login'], $db['password'], $db['base']);
mysqli_set_charset ($link, "utf8");
