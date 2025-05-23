<?php

use Api\Root\URL;

try {
    if (!isset($_POST['url'])) {
        throw new Exception('Empty URL');
    }

    $url = trim($_POST['url']);

    if (!URL::validateUrl($url)) {
        throw new Exception('Invalid URL');
    }

    if (!URL::test($url)) {
        URL::make($url);
    }

    echo json_encode(['OK', URL::getShort()]);
} catch (Exception $e) {
    echo json_encode(['error', $e->getMessage()]);
}
