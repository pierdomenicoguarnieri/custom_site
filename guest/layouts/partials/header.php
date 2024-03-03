<header>
    <div class="pg-left-div">
        <a href="<?php echo DOMAIN ?>">
            <img src="<?php echo ASSETSPATH.'img/logo.png' ?>" alt="">
        </a>
    </div>
    <div class="pg-right-div">
        <h2><?php echo $GLOBALS['CURRENT_USER']->nome ?></h2>
        <button class="btn btn-danger">
            <a href="<?php echo DOMAIN ?>logout.php">LOGOUT</a>
        </button>
    </div>
</header>