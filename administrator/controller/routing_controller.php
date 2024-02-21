<?php
    $files_views = scandir(ADMINPATH."views");
    if($_GET['page'] != '.' && $_GET['page'] != '..' && strlen($_GET['page']) > 0){
        if(in_array($_GET['page'].'.php',$files_views)){
            require_once ADMINPATH.'views/'.$file.'.php';
        }
    }
?>