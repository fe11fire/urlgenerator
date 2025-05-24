<?php

use Api\Root\URL;

try {
    //принимаем и проверяем url
    if (!isset($_POST['url'])) {
        throw new Exception('Empty URL');
    }

    $url = trim($_POST['url']);

    if (!URL::validateUrl($url)) {
        throw new Exception('Invalid URL');
    }

    //проверяем наличие url в БД, при отсутствии создаем short для этого url
    if (!URL::test($url)) {
        URL::make($url);
    }

    //отправляем short, если нет ошибок
    echo json_encode(['OK', URL::getShort()]);
} catch (Exception $e) {
    //отправляем ошибку с текстом
    echo json_encode(['error', $e->getMessage()]);
}
