<?php
require_once 'require.php';
if((isset($_COOKIE['ucode']) && strlen($_COOKIE['ucode']) > 0 && $_COOKIE['ucode'] != null) || (isset($_COOKIE['acode']) && strlen($_COOKIE['acode']) > 0 && $_COOKIE['acode'] != null)){
    if(isset($_COOKIE['ucode']) && strlen($_COOKIE['ucode']) > 0 && $_COOKIE['ucode'] != null){
        require_once USERPATH.'controller/index.php';
    }elseif(isset($_COOKIE['acode']) && strlen($_COOKIE['acode']) > 0 && $_COOKIE['acode'] != null){
        require_once ADMINPATH.'controller/index.php';
    }
}else{
    require_once GLOBALPATH.'controller/index.php';
}
?>