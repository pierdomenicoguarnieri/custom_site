<?php
$GLOBALS['CURRENT_USER'] = new Settings($_COOKIE['ucode']);
include './guest/views/index.php';
?>