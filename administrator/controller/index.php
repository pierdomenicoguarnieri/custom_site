<?php
$GLOBALS['CURRENT_USER'] = new Settings($_COOKIE['acode']);
include './administrator/views/index.php';
?>