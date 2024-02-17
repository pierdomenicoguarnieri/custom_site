<?php
require_once 'header.php';

$email = isset($params['email']) ? $params['email'] : '';
$pwd = isset($params['pwd']) ? $params['pwd'] : '';
$json->email = $email;
$json->pwd = encryptString(md5($pwd));

$q = "SELECT * FROM users WHERE email = ? AND pwd = ?";
$r = DataBase::getResultQueryPrepare($q, 'ss', [$email, encryptString(md5($pwd))]);
if(mysqli_num_rows($r) > 0){
    $id = DataBase::getResult($r, 'id');
    $isAdmin = DataBase::getResult($r, 'is_admin');
    $json->body = $isAdmin;
    $token = bin2hex(random_bytes(16));
    $date_start = date('Y-m-d H:i:s');
    $date_end = date('Y-m-d H:i:s', strtotime($date_start.' + 6 hours'));
    $cookie_name = $isAdmin == 1 ? 'acode' : 'ucode';
    $qSelect = "SELECT * FROM sessions_users WHERE id_user = '$id'";
    $rSelect = mysqli_query(DataBase::$mysqli, $qSelect);
    if(mysqli_num_rows($rSelect)){
        $qSession = "UPDATE sessions_users SET token = '$token', session_start = '$date_start', session_end = '$date_end' WHERE id_user = '$id'";
    }else{
        $qSession = "INSERT INTO sessions_users (id_user, token, session_start, session_end) VALUES ('$id','$token', '$date_start', $date_end)";
    }
    $rSession = mysqli_query(DataBase::$mysqli, $qSession);
    if($rSession){
        $json->head = true;
        $json->body = new stdClass();
        $json->body->cookie_name = $cookie_name;
        $json->body->date_end = $date_end;
        $json->body->token = $token;
    }else{
        $json->head = false;
        $json->errors = 'Errore durante il login. Riprovare.';
    }
}else{
    $json->head = false;
    $json->errors = 'Credenziali errate! Email o Password non corrette.';
}

require_once 'footer.php';