<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php require_once './guest/controller/css_include.php'; ?>
        <title><?php echo '' ?></title>
    </head>
    <body>
        <div class="pg-content-wrapper">
            <?php require_once './guest/layouts/partials/header.php' ?>
            <main>
                <?php require_once './guest/layouts/partials/aside.php' ?>
                <div class="pg-page-wrapper">
                    <?php require_once './guest/views/'.$_GET['page'].'.php' ?>
                </div>
            </main>
            <?php require_once './guest/layouts/partials/footer.php' ?>
        </div>
    </body>
</html>