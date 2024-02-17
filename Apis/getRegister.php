<?php
require_once 'header.php';

$name = isset($params['name']) ? $params['name'] : '';
$surname = isset($params['surname']) ? $params['surname'] : '';
$email = isset($params['email']) ? $params['email'] : '';
$pwd = isset($params['pwd']) ? $params['pwd'] : '';
$pwd2 = isset($params['pwd2']) ? $params['pwd2'] : '';
$isAdmin = 0;

$q = "SELECT * FROM users WHERE email = '$email'";
$r = mysqli_query($mysqli, $q);
if(mysqli_num_rows($r) == 0){
    if(strlen($name) > 0 && strlen($surname) > 0 && strlen($email) > 0 && strlen($pwd) > 0 && strlen($pwd2) > 0 && ($pwd == $pwd2)){
        $qSelect = "INSERT INTO users (name, surname, email, pwd) VALUES (?,?,?,?)";
        $rSelect = getResultPrepare($mysqli, $qSelect, 'ssss', [$name, $surname, $email, md5($pwd)]);
        if($rSelect){
            $json->head = true;
        }else{
            $json->head = false;
            $json->errors = 'Errore durante la registrazione. Riprovare.';
        }
    }else{
        $json->head = false;
        $json->errors = [];
        if (strlen($name) == 0){
            array_push($json->errors, 'Il nome è obbligatorio');
        }
        if (strlen($surname) == 0){
            array_push($json->errors, 'Il cognome è obbligatorio');
        }
        if (strlen($email) == 0){
            array_push($json->errors, 'L\'email è obbligatoria');
        }
        if (strlen($pwd) == 0){
            array_push($json->errors, 'La password è obbligatoria');
        }
        if ($pwd != $pwd2){
            array_push($json->errors, 'Le due password non coincidono');
        }
    }
}else{
    $json->head = false;
    $json->errors = 'Utente già registrato!';
}

require_once 'footer.php';