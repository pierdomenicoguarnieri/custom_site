<header>
    <div class="pg-left-div">
        <h1>Administaror</h1>
    </div>
    <div class="pg-right-div">
        <h2><?php echo $GLOBALS['CURRENT_USER']->nome ?></h2>
        <button>
            <a href="<?php echo DOMAIN ?>logout.php" class="pg-logout">LOGOUT</a>
        </button>
    </div>
</header>