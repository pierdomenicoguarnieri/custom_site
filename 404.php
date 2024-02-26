<?php
    require 'require.php';
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