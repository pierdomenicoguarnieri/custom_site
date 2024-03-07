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

    var toggleButtons = document.querySelectorAll('.pg-toggle-filter');

    toggleButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var filtersSelect = this.parentNode.querySelector('.pg-filters-select');
            filtersSelect.classList.toggle('hidden');

            // Ottieni la riga della tabella
            var tableRow = this.closest('.pg-table-head').querySelector('.pg-table-row');

            // Controlla se tutti i filtri sono nascosti
            var allHidden = true;
            var filterSelects = tableRow.querySelectorAll('.pg-filters-select');
            filterSelects.forEach(function(select) {
                if (!select.classList.contains('hidden')) {
                    allHidden = false;
                }
            });

            // Aggiungi o rimuovi la classe pg-height-increased in base allo stato dei filtri
            if (!allHidden) {
                tableRow.classList.add('pg-height-increased');
            } else {
                tableRow.classList.remove('pg-height-increased');
            }
        });
    });
});
