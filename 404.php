<?php
    require 'require.php';

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
        if(isset($json->body->cookie_name) && isset($json->body->date_end) && isset($json->body->token)){
            $cookie_name = $json->body->cookie_name;
            $date_end = $json->body->date_end;
            $token = $json->body->token;
            $cookie = setCustomCookie($cookie_name, $token, strtotime($date_end),'/','localhost');
            if($cookie == true){
                ?><script>window.location.href="<?php echo DOMAIN; ?>";</script><?php
            }
        }
    }
?>
<style>
    *, html {
        margin:0;
        padding:0;  
    }
</style>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>    
        <main style="height:100vh;width:100vw;background-color:gray;display:flex;align-items:center;justify-content:center;position:relative;flex-direction:column;">
            <div style="position:absolute;top:0;left:20px;height:50px;width:50px;display:flex;align-items:center;justify-content:center;">
                <a href="<?php echo DOMAIN ?>" style="text-decoration:none;color:black;font-size:40px;font-weight:800;">&#8592;</a>
            </div>
            <h1>404</h1>
        </main>
    </body>
</html>

<script>
    function showRegister(flag){
        login = document.getElementById('login-div')
        register = document.getElementById('register-div')
        if(flag == false){
            register.style.display = 'flex';
            login.style.display = 'none';
        }else{
            login.style.display = 'flex';
            register.style.display = 'none';
        }
        hideModal()
    }
</script>