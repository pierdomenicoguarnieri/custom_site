<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php require_once ESSENTIALSPATH.'Styling/css_include.php'; ?>
        <title><?php echo '' ?></title>
    </head>
    <body>
        <div class="pg-content-wrapper">
            <?php require_once USERPATH.'layouts/partials/header.php' ?>
            <main>
                <?php require_once USERPATH.'layouts/partials/aside.php' ?>
                <div class="pg-page-wrapper">
                    <?php require_once ESSENTIALSPATH.'Routing/routing_controller.php' ?>
                </div>
            </main>
        </div>
    </body>
</html>
<?php require_once ESSENTIALSPATH.'JavaScript/javascript_include.php'; ?>