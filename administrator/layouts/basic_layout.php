<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo '' ?></title>
    </head>
    <body style="margin:0;padding:0;box-sizing:border-box;">
        <div style="display:flex;flex-direction:column;height:100vh;">
            <?php require_once './administrator/layouts/partials/header.php' ?>
            <div style="height:calc(100vh - 130px);display:flex">
                <?php require_once './administrator/layouts/partials/aside.php' ?>
                <?php require_once './administrator/views/'.$_GET['page'].'.php' ?>
            </div>
            <?php require_once './administrator/layouts/partials/footer.php' ?>
        </div>
    </body>
</html>