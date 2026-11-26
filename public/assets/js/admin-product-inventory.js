function openCatalogDialog(dialog, row, restore = false) {
    if (row) {
        const {inventoryId, size, availability, quantity} = row.dataset;
        const form = dialog.querySelector('form');
        form.elements.inventory_id.value = inventoryId;
        dialog.querySelector('[data-dialog-size]').textContent = size;
        dialog.querySelector('[data-dialog-status]').textContent = availability === '1' ? 'Available' : 'Unavailable';
        dialog.querySelector('[data-dialog-quantity]').textContent = quantity;
        const status = form.elements.availability;
        if (status) {
            const noStock = Number(quantity) === 0;
            status.options[0].disabled = noStock;
            dialog.querySelector('[data-zero-stock-note]').hidden = !noStock;
            status.value = noStock ? '0' : (restore && status.dataset.posted ? status.dataset.posted : availability);
        } else {
            const qty = form.elements.quantity;
            qty.value = restore && qty.dataset.posted ? qty.dataset.posted : quantity;
        }
        if (!restore) dialog.querySelector('[data-dialog-error]')?.remove();
    }
    dialog.showModal();
}

for (const button of document.querySelectorAll('[data-open-catalog-dialog]')) {
    button.addEventListener('click', () => {
        openCatalogDialog(document.getElementById(button.dataset.openCatalogDialog), button.closest('[data-inventory-id]'));
    });
}
for (const button of document.querySelectorAll('[data-close-catalog-dialog]')) {
    button.addEventListener('click', () => button.closest('dialog').close());
}

const reopen = document.querySelector('dialog[data-reopen]');
if (reopen) {
    const row = reopen.dataset.inventoryId ? document.getElementById('inventory-' + reopen.dataset.inventoryId) : null;
    if (row || !reopen.dataset.inventoryId) openCatalogDialog(reopen, row, true);
}

const thumbnails = document.querySelectorAll('[data-product-image]');
const mainImage = document.querySelector('[data-product-main-image]');
for (const thumbnail of thumbnails) {
    thumbnail.addEventListener('click', () => {
        mainImage.src = thumbnail.dataset.productImage;
        for (const button of thumbnails) button.setAttribute('aria-pressed', String(button === thumbnail));
    });
}
