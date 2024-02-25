<?php
    if(!empty($_COOKIE['acode'])){
        $path = ADMINPATH;
    }elseif(!empty($_COOKIE['ucode'])){
        $path = USERPATH;
    }else{
        $path = GLOBALPATH;
    }

    $files_css_global = scandir(ESSENTIALSPATH."JavaScript/javascripts");
    $files_css = scandir($path."javascript");
    $files_partials = scandir($path."layouts/partials");
    $files_layouts = scandir($path."layouts");
    $files_views = scandir($path."views");

    foreach($files_css as $file){
        $file = explode('.',$file)[0];
        if($file != '.' && strlen($file) > 0){
            if(in_array($file.".php",$files_partials) || in_array($file.".php",$files_views) || in_array($file.".php",$files_layouts)){?>
                <script type="text/javascript" src="<?php echo $path."javascript/".$file.".js" ?>"></script>
            <?php }
        }
    }

    foreach($files_css_global as $file){
        $file = explode('.',$file)[0];
        if($file != '.' && strlen($file) > 0){?>
            <script type="text/javascript" src="<?php echo ESSENTIALSPATH."JavaScript/javascripts/".$file.".js" ?>"></script>
        <?php }
    }
?>