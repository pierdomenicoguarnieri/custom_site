<?php
    $files_css = scandir(GLOBALPATH."css");
    $files_partials = scandir(GLOBALPATH."layouts/partials");
    $files_layouts = scandir(GLOBALPATH."layouts");
    $files_views = scandir(GLOBALPATH."views");
    foreach($files_css as $file){
        $file = explode('.',$file)[0];
        if($file != '.' && strlen($file) > 0){
            if(in_array($file.".php",$files_partials) || in_array($file.".php",$files_views) || in_array($file.".php",$files_layouts)){?>
                <head><link rel="stylesheet" href="<?php echo GLOBALPATH."css/".$file.".css" ?>"></head>
            <?php }
        }
    }
?>