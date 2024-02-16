<?php
require_once 'header.php';

$name = isset($params['name']) ? $params['name'] : '';
$surname = isset($params['surname']) ? $params['surname'] : '';
$email = isset($params['email']) ? $params['email'] : '';
$pwd = isset($params['pwd']) ? $params['pwd'] : '';
$pwd2 = isset($params['pwd2']) ? $params['pwd2'] : '';

$q = "SELECT * FROM users WHERE email = '$email'";
$r = mysqli_query($mysqli, $q);
if(mysqli_num_rows($r) == 0){
    if(){
        
    }
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
        $cookie_name = $isAdmin == 1 ? 'acode' : 'ucode';
        setcookie($cookie_name, $token, strtotime($date_end));
    }else{
        $json->head = false;
        $json->errors = 'Errore durante il login. Riprovare.';
    }
}else{
    $json->head = false;
    $json->errors = 'Utente già registrato!';
}

require_once 'footer.php';