<?php
require_once 'header.php';
if((isset($_COOKIE['ucode']) && strlen($_COOKIE['ucode']) > 0 && $_COOKIE['ucode'] != null) || (isset($_COOKIE['acode']) && strlen($_COOKIE['acode']) > 0 && $_COOKIE['acode'] != null)){
    if(isset($_COOKIE['ucode']) && strlen($_COOKIE['ucode']) > 0 && $_COOKIE['ucode'] != null){
        require_once './guest/controller/index.php';
    }elseif(isset($_COOKIE['acode']) && strlen($_COOKIE['acode']) > 0 && $_COOKIE['acode'] != null){
        require_once './administrator/controller/index.php';
    }
}else{
    require_once 'login.php';
}