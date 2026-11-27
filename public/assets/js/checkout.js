const manualShipping = document.querySelector('#manual-shipping');
if (manualShipping) {
    const addressOptions = document.querySelectorAll('input[name="shipping_address"]');
    const manualOption = document.querySelector('input[name="shipping_address"][value="new"]');
    const fields = manualShipping.querySelectorAll('input, textarea');

    function updateShippingFields() {
        manualShipping.hidden = !manualOption.checked;
        for (const field of fields) {
            field.required = manualOption.checked && field.type !== 'checkbox';
        }
    }

    for (const option of addressOptions) {
        option.addEventListener('change', updateShippingFields);
    }
    window.addEventListener('pageshow', updateShippingFields);
    updateShippingFields();
}

const payForm = document.querySelector('#checkout-pay');
if (payForm) {
    payForm.addEventListener('submit', (event) => {
        if (payForm.hasAttribute('aria-busy')) {
            event.preventDefault();
            return;
        }
        payForm.setAttribute('aria-busy', 'true');
    });
    window.addEventListener('pageshow', () => {
        payForm.removeAttribute('aria-busy');
    });
}
