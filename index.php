<?php

use Api\Root\DB;
use Api\Root\URL;

session_start();

ini_set('error_reporting', E_ALL);
ini_set('log_errors', 1);

try {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php');

    DB::init(DB::DB_POSGRES, 'localhost', 'postgres', 'postgres', '', DB::CHARSET_NULL);

    $url = explode('/', $_SERVER['REQUEST_URI']);

    switch (count($url)) {
        case 1:
            require_once 'pages/main/index.php';
            break;
        case 2:
            switch ($url[1]) {
                case '':
                    require_once 'pages/main/index.php';
                    break;
                case 'url':
                    require_once 'pages/ajax/index.php';
                    break;

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
    echo $e->getMessage();
    require_once 'pages/empty/index.php';
}
