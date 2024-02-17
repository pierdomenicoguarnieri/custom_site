<?php
require_once 'header.php';
ini_set('display_errors', 1);
$email = isset($params['email']) ? $params['email'] : '';
$pwd = isset($params['pwd']) ? $params['pwd'] : '';

$q = "SELECT * FROM users WHERE email = '$email' AND pwd = '".md5($pwd)."'";
$r = mysqli_query($mysqli, $q);
if(mysqli_num_rows($r) > 0){
    $id = getResult($r, 'id');
    $isAdmin = getResult($r, 'is_admin');
    $token = bin2hex(random_bytes(16));
    $date_start = date('Y-m-d H:i:s');
    $date_end = date('Y-m-d H:i:s', strtotime($date_start.' + 6 hours'));
    $cookie_name = $isAdmin == 1 ? 'acode' : 'ucode';
    $cookie = setCustomCookie($cookie_name, $token, strtotime($date_end),'/');
    if($cookie == true){
        $qSelect = "SELECT * FROM sessions_users WHERE id_user = '$id'";
        $rSelect = mysqli_query($mysqli, $qSelect);
        if(mysqli_num_rows($rSelect)){
            $qSession = "UPDATE sessions_users SET token = '$token', session_start = '$date_start', session_end = '$date_end' WHERE id_user = '$id'";
        }else{
            $qSession = "INSERT INTO sessions_users (id_user, token, session_start, session_end) VALUES ('$id','$token', '$date_start', $date_end)";
        }
        $rSession = mysqli_query($mysqli, $qSession);
        if($rSession){
            $json->head = true;
        }else{
            $json->head = false;
            $json->errors = 'Errore durante il login. Riprovare.';
        }
    }else{
        $json->head = false;
        $json->errors = 'Errore nel salvataggio del cookie. Riprovare.';
    }
}else{
    $json->head = false;
    $json->errors = 'Credenziali errate! Email o Password non corrette.';
}

require_once 'footer.php';