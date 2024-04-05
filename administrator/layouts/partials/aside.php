<aside>
    <nav>
        <ul>
            <li>
                <span class="has-submenu">Link <span class="submenu-arrow closed"><i class="fa fa-caret-up"></i></span></span>
                <ul class="hided">
                    <li>
                        <span class="has-submenu">Link 2 <span class="submenu-arrow closed"><i class="fa fa-caret-up"></i></span></span>
                        <ul class="hided xsmall-menu">
                            <li>
                                <span>Link 3</span>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <span>Link 2</span>
                    </li>
                    <li>
                        <span>Link 2</span>
                    </li>
                    <li>
                        <span>Link 2</span>
                    </li>
                    <li>
                        <span>Link 2</span>
                    </li>
                    <li>
                        <span>Link 2</span>
                    </li>
                </ul>
            </li>
            <li class="<?php echo $_GET['objects'] == 'User' || $_GET['object'] == 'User' ? 'active' : ''?>">
                <a href="<?php echo DOMAIN.'?page=obj&objects=User' ?>">
                    <span><i class="fa fa-user"></i> Utenti</span>
                </a>
            </li>
            <li class="<?php echo $_GET['objects'] == 'Patient' || $_GET['object'] == 'Patient' ? 'active' : ''?>">
                <a href="<?php echo DOMAIN.'?page=obj&objects=Patient' ?>">
                    <span><i class="fa fa-hospital-user"></i> Pazienti</span>
                </a>
            </li>
            <li>
                <span>Link</span>
            </li>
            <li>
                <span>Link</span>
            </li>
            <li>
                <span>Link</span>
            </li>
            <li>
                <span>Link</span>
            </li>
            <li>
                <span>Link</span>
            </li>
            <li>
                <span>Link</span>
            </li>
        </ul>
    </nav>
</aside>