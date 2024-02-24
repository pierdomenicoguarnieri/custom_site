<?php
    if(!empty($_COOKIE['acode'])){
        $path = ADMINPATH;
    }elseif(!empty($_COOKIE['ucode'])){
        $path = USERPATH;
    }else{
        $path = GLOBALPATH;
    }

    $files_css_global = scandir(ESSENTIALSPATH."Styling/styles");
    $files_css = scandir($path."css");
    $files_partials = scandir($path."layouts/partials");
    $files_layouts = scandir($path."layouts");
    $files_views = scandir($path."views");

    foreach($files_css as $file){
        $file = explode('.',$file)[0];
        if($file != '.' && strlen($file) > 0){
            if(in_array($file.".php",$files_partials) || in_array($file.".php",$files_views) || in_array($file.".php",$files_layouts)){?>
                <head><link rel="stylesheet" href="<?php echo $path."css/".$file.".css" ?>"></head>
            <?php }
        }
    }

    foreach($files_css_global as $file){
        $file = explode('.',$file)[0];
        if($file != '.' && strlen($file) > 0){?>
            <head><link rel="stylesheet" href="<?php echo ESSENTIALSPATH."Styling/styles/".$file.".css" ?>"></head>
        <?php }
    }
?>