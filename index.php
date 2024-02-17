<?php
require_once 'header.php';
if((isset($_COOKIE['ucode']) &&  strlen($_COOKIE['ucode']) > 0) || (isset($_COOKIE['acode']) &&  strlen($_COOKIE['acode']) > 0)){
    if(isset($_COOKIE['ucode']) &&  strlen($_COOKIE['ucode']) > 0){
        echo 'user logged';
    }elseif(isset($_COOKIE['acode']) &&  strlen($_COOKIE['acode']) > 0){
        echo 'admin logged';
    }
}else{
    require_once 'login.php';
}