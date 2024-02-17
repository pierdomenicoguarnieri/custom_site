<?php
require_once '../Essentials/import.php';
require_once '../Essentials/Standard.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: OPTIONS,GET,PUT,POST,DELETE');
header('Allow: OPTIONS,GET,PUT,POST,DELETE');

$params = [];
if(count($_REQUEST) > 0) {
    $params = getParams($_REQUEST);
}

$json = new stdClass();