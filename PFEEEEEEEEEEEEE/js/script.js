document.addEventListener('DOMContentLoaded', function() {
    // Gestion de la sidebar
    const menuItems = document.querySelectorAll('.menu-item');
    const contents = document.querySelectorAll('.main-content');

    menuItems.forEach(item => {
        item.addEventListener('click', function() {
            menuItems.forEach(i => i.classList.remove('active'));
            contents.forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            const contentId = this.id + '-content';
            document.getElementById(contentId).classList.add('active');
        });
    });

    // Stocker les modèles de lignes pour chaque tableau
    const tableTemplates = {};

    // Sauvegarder les modèles de lignes au chargement
    document.querySelectorAll('table').forEach(table => {
        if (table.id) {
            const firstRow = table.querySelector('tbody tr');
            if (firstRow) {
                tableTemplates[table.id] = firstRow.cloneNode(true);
            }
        }
    });

    // Fonction pour mettre à jour les numéros des lignes
    function updateRowNumbers(tableId) {
        const tbody = document.querySelector(`#${tableId} tbody`);
        if (!tbody) return;
        
        const rows = tbody.querySelectorAll('tr');
        rows.forEach((row, index) => {
            const firstCell = row.querySelector('td:first-child');
            if (firstCell) {
                firstCell.textContent = index + 1;
            }
        });
    }

    // Fonction pour créer une nouvelle ligne
    function createNewRow(tableId) {
        const template = tableTemplates[tableId];
        if (!template) return null;

        const newRow = template.cloneNode(true);
        newRow.classList.add('new-row');

        // Vider tous les champs de saisie
        newRow.querySelectorAll('input').forEach(input => {
            input.value = '';
        });

        // Réinitialiser les selects
        newRow.querySelectorAll('select').forEach(select => {
            select.selectedIndex = 0;
        });

        // Configurer le bouton de suppression
        const deleteBtn = newRow.querySelector('.btn-delete');
        if (deleteBtn) {
            deleteBtn.onclick = function(e) {
                e.preventDefault();
                newRow.remove();
                updateRowNumbers(tableId);
            };
        }

        return newRow;
    }

    // Fonction pour ajouter une ligne dans un tableau
    function addTableRow(tableId) {
        const table = document.getElementById(tableId);
        if (!table) return;

        const tbody = table.querySelector('tbody');
        if (!tbody) return;

        const newRow = createNewRow(tableId);
        if (!newRow) return;

        tbody.appendChild(newRow);
        updateRowNumbers(tableId);
    }

    // Gestionnaire pour les boutons d'ajout
    document.querySelectorAll('.btn-add').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const tableId = this.getAttribute('data-table');
            if (tableId) {
                addTableRow(tableId);
            }
        });
    });

    // Gestionnaire pour les boutons de suppression existants
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const row = this.closest('tr');
            const table = this.closest('table');
            if (row && table) {
                row.remove();
                updateRowNumbers(table.id);
            }
        });
    });

    // Initialiser les numéros pour tous les tableaux
    document.querySelectorAll('table').forEach(table => {
        if (table.id) {
            updateRowNumbers(table.id);
        }
    });

    // Gestion des modales
    const modal = document.getElementById('myModal');
    const openModalBtn = document.getElementById('openModalBtn');
    const closeModalBtn = document.querySelector('.close');

    if (modal && openModalBtn && closeModalBtn) {
        openModalBtn.onclick = function() {
            modal.style.display = "block";
        }

        closeModalBtn.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    }
}); 