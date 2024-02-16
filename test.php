<?php
require 'require.php';

$q = "SELECT id FROM users WHERE name = 'Pierdomenico'";
$r = mysqli_query($mysqli, $q);
$id = getResult($r,'id');
var_dump($id);