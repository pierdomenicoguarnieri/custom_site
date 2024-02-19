<aside>
    <nav>
        <ul>
            <li>
                <span class="has-submenu">Link <span class="submenu-arrow closed">&#62;</span></span>
                <ul class="hided medium-menu">
                    <li>
                        <span class="has-submenu">Link 2 <span class="submenu-arrow closed">&#62;</span></span>
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
            <li>
                <span>Link</span>
            </li>
        </ul>
    </nav>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var submenuLinks = document.querySelectorAll('.has-submenu');

        submenuLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                var submenu = this.nextElementSibling; // Accedi all'elemento successivo, che è il menu di sotto livello
                var arrow = this.querySelector('.submenu-arrow'); // Cerca la freccia all'interno dello <span>

                if (submenu && arrow) {
                    if (submenu.classList.contains('visible')) {
                        submenu.classList.remove('visible');
                        submenu.classList.add('hided');
                        arrow.classList.remove('open');
                        arrow.classList.add('closed');
                    } else {
                        submenu.classList.remove('hided');
                        submenu.classList.add('visible');
                        arrow.classList.remove('closed');
                        arrow.classList.add('open');
                    }
                }
            });
        });
    });
</script>