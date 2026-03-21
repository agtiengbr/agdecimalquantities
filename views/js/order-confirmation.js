document.addEventListener('DOMContentLoaded', function() {
    const originalTable = document.querySelector('#order-items .order-confirmation-table');
    const newTable = document.querySelector('#order-items-agdecimalquantities .order-confirmation-table');

    if (originalTable && newTable) {
        // Remove existing order-line rows from the original table
        const originalRows = originalTable.querySelectorAll('.order-line.row');
        originalRows.forEach(row => row.remove());

        // Append new order-line rows from the module table
        const newRows = newTable.querySelectorAll('.order-line.row');
        newRows.forEach(row => originalTable.prepend(row));

        originalTable.style.display = 'block';
    }
});
