document.addEventListener('DOMContentLoaded', function() {
    var submenuLinks = document.querySelectorAll('.has-submenu');

    submenuLinks.forEach(function(link) {
        link.addEventListener('click', function() {
            var submenu = this.nextElementSibling;
            var arrow = this.querySelector('.submenu-arrow');

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