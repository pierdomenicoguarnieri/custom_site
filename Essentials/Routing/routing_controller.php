<?php
    if(!empty($_COOKIE['acode'])){
        $path = ADMINPATH;
    }elseif(!empty($_COOKIE['ucode'])){
        $path = USERPATH;
    }else{
        $path = GLOBALPATH;
    }

    $files_views = scandir($path."views");
    $flag = false;
    $page = isset($_GET['page']) ? $_GET['page'] : 'index';

    require_once ESSENTIALSPATH.'Routing/custom_routes.php';

    if($page != '.' && $page != '..' && strlen($page) > 0){
        if(in_array($page.'.php',$files_views)){
            require_once $path.'views/'.$page.'.php';
            $flag = true;
        }
    }

    if(!$flag){ ?>
        <script>window.location.href="<?php echo DOMAIN.'404.php' ?>"</script>
    <?php }
?>