for (const form of document.querySelectorAll('[data-product-action]')) {
    form.addEventListener('submit', async event => {
        event.preventDefault();
        if (!window.confirm(form.dataset.productAction)) return;
        const button = event.submitter;
        if (button) button.disabled = true;
        try {
            const response = await fetch(form.action, {method: 'POST', body: new FormData(form)});
            if (!response.ok) throw new Error(await response.text());
            window.location.reload();
        } catch (error) {
            window.alert(error.message || 'The product status could not be changed.');
            if (button) button.disabled = false;
        }
    });
}
