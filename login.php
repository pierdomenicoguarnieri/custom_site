<?php
    require 'require.php';

    if(isset($_POST['login'])){
        $email = $_POST['email'];
        $pwd = $_POST['pwd'];
        $params = array('email' => $email, 'pwd' => $pwd);
        $json = json_decode(getApi(DOMAIN.'Apis/getLogin.php', $params, 'GET'));
        $errors = [];
        if(isset($json) && $json->head == true){
            if(isset($json->body->cookie_name) && isset($json->body->date_end) && isset($json->body->token)){
                $sessionOBJ = new SessionsOBJ();
                $session = $sessionOBJ->setCookie($json->body->cookie_name, $json->body->token, strtotime($json->body->date_end),'/','localhost');
                if($session === true){
                    ?><script>window.location.href="<?php echo DOMAIN; ?>";</script><?php
                }
            }
        }
    }

    if(isset($_POST['register'])){
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $email = $_POST['email'];
        $pwd = $_POST['pwd'];
        $pwd2 = $_POST['pwd2'];
        $params = array('name' => $name, 'surname' => $surname, 'email' => $email, 'pwd' => $pwd, 'pwd2' => $pwd2);
        $url = DOMAIN.'Apis/getRegister.php';
        if(isset($_REQUEST['token']) && strlen($_REQUEST['token']) > 0){
            $url .= '?tokenAdmin='.base64_encode($_REQUEST['token']);
        }
        $json = json_decode(getApi($url, $params, 'GET'));
        $errors = [];
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php require_once ESSENTIALSPATH.'Styling/css_include.php'; ?>
    </head>
    <body>    
        <main style="height:100vh;width:100vw;background-color:var(--antiflash-white);display:flex;align-items:center;justify-content:center;position:relative;flex-direction:column;">
            <?php if(isset($json) && $json->head == false){
                $title = 'Errore Login!';
                if(is_array($json->errors)){
                    foreach ($json->errors as $error) {
                        array_push($errors,$error);
                    }
                }else{
                    array_push($errors,$json->errors);
                }
                require GLOBALPATH.'layouts/partials/modal_errors.php';
            } ?>
            <div style="position:absolute;top:0;left:20px;height:50px;width:50px;display:flex;align-items:center;justify-content:center;">
                <a href="<?php echo DOMAIN; ?>" style="text-decoration:none;color:black;font-size:30px;">
                    <i class="fa fa-left-long"></i>
                </a>
            </div>
            <div id="login-div" style="display:flex;flex-direction:column;align-items:center;justify-content:center;">
                <h1 style="margin-bottom:20px;">Login</h1>
                <form action="" method="POST" style="display:flex;flex-direction:column;align-items:center;justify-content:center;margin-bottom:10px;">
                    <label for="email">Email</label>
                    <input type="text" name="email" class="form-input" placeholder="Inserisci la tua email">
                    <label for="email">Password</label>
                    <input type="password" name="pwd" class="form-input" placeholder="Inserisci la tua password">
                    <div style="width:100%;display: flex;justify-content:space-around;">
                        <button type="submit" class="btn btn-confirm btn-outline" name="login">Accedi</button>
                        <button class="btn btn-danger btn-outline" id="register-link" type="button" onclick="showRegister(false)">Registrati</button>
                    </div>
                </form>
            </div>
            
            <div id="register-div" style="display:none;flex-direction:column;align-items:center;justify-content:center;">
                <h1 style="margin-bottom:20px;">Register</h1>
                <form action="" method="POST" style="display:flex;flex-direction:column;align-items:center;justify-content:center;margin-bottom:10px;">
                    <label for="nome">Nome</label>
                    <input type="text" name="name" class="form-input" placeholder="Inserisci il tuo nome">
                    <label for="surname">Cognome</label>
                    <input type="text" name="surname" class="form-input" placeholder="Inserisci il tuo cognome">
                    <label for="email">Email</label>
                    <input type="text" name="email" class="form-input" placeholder="Inserisci la tua email">
                    <label for="pwd">Password</label>
                    <input type="password" name="pwd" class="form-input" placeholder="Inserisci la tua password">
                    <label for="pwd2">Conferma Password</label>
                    <input type="password" name="pwd2" class="form-input" placeholder="Conferma la tua password">
                    <div style="width:100%;display: flex;justify-content:space-around;">
                        <button type="submit" class="btn btn-confirm btn-outline" name="register">Registrati</button>
                        <button class="btn btn-danger btn-outline" id="register-link" type="button" onclick="showRegister(true)">Accedi</button>
                    </div>
                </form>
            </div>
        </main>
    </body>
</html>
<?php require_once ESSENTIALSPATH.'JavaScript/javascript_include.php'; ?>