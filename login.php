<?php

    if(isset($_POST['login'])){
        $email = $_POST['email'];
        $pwd = $_POST['pwd'];
        $params = array('email' => $email, 'pwd' => $pwd);
        $json = json_decode(getApi('http://localhost/Programmazione/PHP/Framework/Apis/getLogin.php', $params, 'GET'));
        $errors = [];
    }

    if(isset($_POST['register'])){
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $email = $_POST['email'];
        $pwd = $_POST['pwd'];
        $pwd2 = $_POST['pwd2'];
        $params = array('name' => $name, 'surname' => $surname, 'email' => $email, 'pwd' => $pwd, 'pwd2' => $pwd2);
        $url = 'http://localhost/Programmazione/PHP/Framework/Apis/getRegister.php';
        if(isset($_REQUEST['token']) && strlen($_REQUEST['token']) > 0){
            $url .= '?tokenAdmin='.base64_encode($_REQUEST['token']);
        }
        $json = json_decode(getApi($url, $params, 'GET'));
        $errors = [];
    }
    if(isset($json) && $json->head == true){
        var_dump($_COOKIE);
    }
?>
<?php if(isset($json) && $json->head == false){
    $title = 'Errore Login!';
    if(is_array($json->errors)){
        foreach ($json->errors as $error) {
            array_push($errors,$error);
        }
    }else{
        array_push($errors,$json->errors);
    }
    require './partials/modal_errors.php';
} ?>
<div id="login-div">
    <h1>Login</h1>
    <form action="" method="POST">
        <input type="text" name="email">
        <input type="password" name="pwd">
        <button type="submit" name="login">Accedi</button>
    </form>
    <span style="color: blue;text-decoration:underline;cursor:pointer" id="register-link" onclick="showRegister(false)">Non sei registrato? Registrati!</span>
</div>

<div id="register-div" style="display: none;">
    <h1>Register</h1>
    <form action="" method="POST">
        <input type="text" name="name">
        <input type="text" name="surname">
        <input type="text" name="email">
        <input type="password" name="pwd">
        <input type="password" name="pwd2">
        <button type="submit" name="register">Registrati</button>
    </form>
    <span style="color: blue;text-decoration:underline;cursor:pointer" id="register-link" onclick="showRegister(true)">Sei già registrato? Fai il login!</span>
</div>

<script>
    function showRegister(flag){
        login = document.getElementById('login-div')
        register = document.getElementById('register-div')
        if(flag == false){
            register.style.display = 'block';
            login.style.display = 'none';
        }else{
            login.style.display = 'block';
            register.style.display = 'none';
        }
        hideModal()
    }
</script>