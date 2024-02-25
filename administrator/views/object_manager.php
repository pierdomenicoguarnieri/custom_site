<?php
$objects = explode(",", $_GET["objects"]);
$objectsOBJ = array();
for ($i = 0; $i < count($objects); $i++) {
    $n = $objects[$i] . "OBJ";
    $objectsOBJ[strtoupper($objects[$i])] = new $n;
}
?>