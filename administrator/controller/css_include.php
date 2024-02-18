<?php
    $files_css = scandir(ADMINPATH."css");
    $files_partials = scandir(ADMINPATH."layouts/partials");
    $files_layouts = scandir(ADMINPATH."layouts");
    $files_views = scandir(ADMINPATH."views");
    foreach($files_css as $file){
        $file = explode('.',$file)[0];
        if($file != '.' && strlen($file) > 0){
            if(in_array($file.".php",$files_partials) || in_array($file.".php",$files_views) || in_array($file.".php",$files_layouts)){?>
                <head><link rel="stylesheet" href="<?php echo ADMINPATH."css/".$file.".css" ?>"></head>
            <?php }
        }
    }
?>