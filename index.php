<?php

use Api\Root\DB;
use Api\Root\URL;

session_start();

ini_set('error_reporting', E_ALL);
ini_set('log_errors', 1);

try {
    //Подключение зависимостей src/Root
    require_once($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php');

    //Ввод настроек подключения к БД
    DB::init(DB::DB_POSGRES, 'localhost', 'postgres', 'postgres', '', DB::CHARSET_NULL);

    //Парсинг url
    $url = explode('/', $_SERVER['REQUEST_URI']);

    switch (count($url)) {
        case 2:
            switch ($url[1]) {
                // url-адрес вида "mydomain/"
                case '':
                    require_once 'pages/main/index.php';
                    break;
                // url-адрес для ajax-запроса вида "mydomain/url"
                case 'url':
                    require_once 'pages/ajax/index.php';
                    break;
                // url-адрес для переадресации вида "mydomain/hashFromDomain"
                default:
                    die(header("Location: " . URL::getURL($url[1])));
                    break;
            }
            break;

        default:
            require_once 'pages/main/index.php';
            break;
    }
} catch (Exception $e) {
    // переход на страницу 404
    require_once 'pages/empty/index.php';
}
