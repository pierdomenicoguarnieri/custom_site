<?php
require_once 'header.php';

$email = isset($params['email']) ? $params['email'] : '';
$pwd = isset($params['pwd']) ? $params['pwd'] : '';

$q = "SELECT * FROM users WHERE email = ? AND pwd = ?";
$r = DataBase::getResultQueryPrepare($q, 'ss', [$email, md5($pwd)]);
if(mysqli_num_rows($r) > 0){
    $id = DataBase::getResult($r, 'id');
    $isAdmin = intval(DataBase::getResult($r, 'is_admin'));
    $cookie_name = $isAdmin == 1 ? 'acode' : 'ucode';
    $sessionOBJ = new SessionsOBJ();
    $session = $sessionOBJ->set(['id_user' => $id]);
    $json->head = true;
    $json->body = new stdClass();
    $json->body->cookie_name = $cookie_name;
    $json->body->date_end = $session['session_end'];
    $json->body->token = $session['token'];
}else{
    $json->head = false;
    $json->errors = 'Credenziali errate! Email o Password non corrette.';
}

require_once 'footer.php';