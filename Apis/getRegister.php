<?php
require_once 'header.php';

$name = isset($params['name']) ? $params['name'] : '';
$surname = isset($params['surname']) ? $params['surname'] : '';
$email = isset($params['email']) ? $params['email'] : '';
$pwd = isset($params['pwd']) ? $params['pwd'] : '';
$pwd2 = isset($params['pwd2']) ? $params['pwd2'] : '';
$tokenAdmin = isset($params['tokenAdmin']) ? $params['tokenAdmin'] : '';


if(strlen($name) > 0 && strlen($surname) > 0 && strlen($email) > 0 && strlen($pwd) > 0 && strlen($pwd2) > 0 && ($pwd == $pwd2)){
    $qSelect = "SELECT * FROM users WHERE email = '$email'";
    $rSelect = mysqli_query($mysqli, $qSelect);
    if(mysqli_num_rows($rSelect) == 0){
        $isAdmin = 0;
        $qSelectToken = "SELECT * FROM admin_tokens WHERE CONVERT(token USING utf8mb4) = '$tokenAdmin' AND used = 0";
        $rSelectToken = mysqli_query($mysqli, $qSelectToken);
        if(mysqli_num_rows($rSelectToken) > 0){
            $isAdmin = 1;
        }
        $qInsert = "INSERT INTO users (name, surname, email, pwd, is_admin) VALUES (?,?,?,?,?)";
        $rInsert = getResultPrepare($mysqli, $qInsert, 'ssssi', [$name, $surname, $email, md5($pwd), $isAdmin]);
        if($rInsert){
            $json->head = true;
            if(mysqli_num_rows($rSelectToken) > 0){
                $qUpdateToken = "UPDATE admin_tokens SET used = 1 WHERE CONVERT(token USING utf8mb4) = '$tokenAdmin' AND used = 0";
                mysqli_query($mysqli, $qUpdateToken);
            }
        }else{
            $json->head = false;
            $json->errors = 'Errore durante la registrazione. Riprovare.';
        }
    }else{
        $json->head = false;
        $json->errors = 'Utente già registrato!';
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

require_once 'footer.php';