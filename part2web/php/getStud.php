<?php


$method = $_SERVER['REQUEST_METHOD'];

if ($method != 'GET') {
    echo '{
        "code":"404",
        "message":"This api only processes get requests"
    }';
    die;
}
echo file_get_contents("../out.txt");