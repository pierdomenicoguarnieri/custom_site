<?php
if(isset($_GET['debug']) && intval($_GET['debug'])==1){
    error_reporting(E_ALL & ~E_NOTICE);
    ini_set('display_errors', 1); 
}else{
    error_reporting(E_ERROR);
    ini_set('display_errors', 1); 
}

set_time_limit(5);
date_default_timezone_set('Europe/Rome');
define("BASEPATH", str_replace("Essentials", "", __DIR__));
define('DB_NAME', 'framework');
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PWD', '');
define('VERSION', "1.0.0");
define('DOMAIN', "http://localhost/Programmazione/PHP/Framework/custom_site/");
define('ADMINPATH', "administrator/");
define('USERPATH', "guest/");
define('GLOBALPATH', "global/");
define('ASSETSPATH', "assets/");
define('ADMINURL', DOMAIN.ADMINPATH);
define('USERURL', DOMAIN.USERPATH);
define('GLOBALURL', DOMAIN.GLOBALPATH);
define('ASSETSURL', DOMAIN.ASSETSPATH);

$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$basename = basename($_SERVER['PHP_SELF']);

$GLOBALS['thumbsTypes'] = array('100','200','400','500','800','1200');

include 'autoload.php';

DataBase::$DB_name = DB_NAME;
DataBase::inizialize();