<?php
$object = $_GET['object'].'OBJ';
$object_id = $_GET['id'];
$objObj = new $object;
$obj = $objObj->getView($object_id);
var_dump($obj);
?>