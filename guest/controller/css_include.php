<?php
    $files_css = scandir(USERPATH."css");
    $files_partials = scandir(USERPATH."layouts/partials");
    $files_layouts = scandir(USERPATH."layouts");
    $files_views = scandir(USERPATH."views");
    foreach($files_css as $file){
        $file = explode('.',$file)[0];
        if($file != '.' && strlen($file) > 0){
            if(in_array($file.".php",$files_partials) || in_array($file.".php",$files_views) || in_array($file.".php",$files_layouts)){?>
                <head><link rel="stylesheet" href="<?php echo USERPATH."css/".$file.".css" ?>"></head>
            <?php }
        }
    }
?>