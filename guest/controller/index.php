<?php
$GLOBALS['CURRENT_USER'] = new Settings($_COOKIE['ucode']);
include './guest/layouts/basic_layout.php';
?>