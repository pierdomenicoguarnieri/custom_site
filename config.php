<?php
define('DB_NAME', 'framework');
define('DB_PATH', 'localhost');
define('DB_USER', 'root');
define('DB_PWD', '');

$mysqli = mysqli_connect(DB_PATH,DB_USER,DB_PWD,DB_NAME);