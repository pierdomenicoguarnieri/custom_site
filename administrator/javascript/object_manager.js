document.addEventListener('DOMContentLoaded', function() {
    var btnFilters = document.querySelectorAll('.pg-filter-button');

    btnFilters.forEach(function(btn) {
        btn.addEventListener('click', function() {
            var inputWrapper = this.nextElementSibling; // Ottieni il wrapper dell'input

            // Controlla se l'input wrapper ha la classe hided
            if (inputWrapper.classList.contains('hided')) {
                // Rimuovi la classe hided
                inputWrapper.classList.remove('hided');
            } else {
                // Aggiungi la classe hided
                inputWrapper.classList.add('hided');
            }
        });
    });

    var rows = document.querySelectorAll('.clickable-row');
    rows.forEach(function(row) {
        row.addEventListener('click', function() {
            window.location.href = this.dataset.href;
        });
    });
});