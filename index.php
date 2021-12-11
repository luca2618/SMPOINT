<?php

$request = $_SERVER['REQUEST_URI'];
echo($request);echo("<br>");
switch ($request) {
    case '' :
        require __DIR__ . '/main.php';
        break;
    case '/smkid%20point/addpoint/' :
        require __DIR__ . '/404.php';
        break;
    case '/about' :
        require __DIR__ . '/main.php';
        break;
    default:
        http_response_code(404);
        require __DIR__ . '/404.php';
        break;
}