<?php
if(strlen($_GET['page']) == 0){
    $_GET['page'] = 'index';
}
include GLOBALPATH.'layouts/basic_layout.php';
?>