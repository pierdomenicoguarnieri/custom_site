<?php
require 'require.php';

if((isset($_COOKIE['ucode']) && strlen($_COOKIE['ucode']) > 0 && $_COOKIE['ucode'] != null) || (isset($_COOKIE['acode']) && strlen($_COOKIE['acode']) > 0 && $_COOKIE['acode'] != null)){
    $cookie_name = '';
    if(isset($_COOKIE['ucode']) && strlen($_COOKIE['ucode']) > 0 && $_COOKIE['ucode'] != null){
        $cookie_name = 'ucode';
    }elseif(isset($_COOKIE['acode']) && strlen($_COOKIE['acode']) > 0 && $_COOKIE['acode'] != null){
        $cookie_name = 'acode';
    }
    if(strlen($cookie_name) > 0){
        $sessionOBJ = new SessionsOBJ();
        $session = $sessionOBJ->unsetCookie($cookie_name);
        if($session === true){
            ?><script>window.location.href="<?php echo DOMAIN; ?>";</script><?php
        }
    }
}
?>