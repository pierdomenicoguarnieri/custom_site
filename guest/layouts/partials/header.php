<header style="height:80px;display:flex;justify-content:space-between;background-color:blue;">
    <div style="margin-left:15px;">
        <h1>Guest</h1>
    </div>
    <div style="display:flex;align-items:center;justify-content:space-around;width:300px;">
        <h2><?php echo $GLOBALS['CURRENT_USER']->nome ?></h2>
        <button>
            <a href="<?php echo DOMAIN ?>logout.php" style="text-decoration:none;color:black;">LOGOUT</a>
        </button>
    </div>
</header>