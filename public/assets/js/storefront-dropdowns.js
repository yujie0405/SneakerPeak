document.querySelectorAll('[data-storefront-dropdown]').forEach(dropdown => {
    const trigger = dropdown.querySelector('[aria-controls]');
    const menu = document.getElementById(trigger.getAttribute('aria-controls'));
    let pointerInside = false;
    let openedByClick = false;
    let closeTimer;

    function setOpen(open) {
        clearTimeout(closeTimer);
        trigger.setAttribute('aria-expanded', String(open));
        menu.hidden = !open;
        if (!open) openedByClick = false;
    }

    function scheduleClose() {
        clearTimeout(closeTimer);
        closeTimer = setTimeout(() => {
            if (!pointerInside && !dropdown.contains(document.activeElement)) setOpen(false);
        }, 220);
    }

    dropdown.addEventListener('pointerenter', event => {
        if (event.pointerType !== 'mouse') return;
        pointerInside = true;
        setOpen(true);
    });
    dropdown.addEventListener('pointerleave', event => {
        if (event.pointerType !== 'mouse') return;
        pointerInside = false;
        scheduleClose();
    });
    dropdown.addEventListener('focusin', () => clearTimeout(closeTimer));
    dropdown.addEventListener('focusout', scheduleClose);

    trigger.addEventListener('click', () => {
        const open = menu.hidden || !openedByClick;
        setOpen(open);
        openedByClick = open;
    });
    document.addEventListener('keydown', event => {
        if (event.key !== 'Escape' || menu.hidden) return;
        const focusInside = dropdown.contains(document.activeElement);
        setOpen(false);
        if (focusInside) {
            event.preventDefault();
            trigger.focus();
        }
    });
    document.addEventListener('pointerdown', event => {
        if (!dropdown.contains(event.target)) setOpen(false);
    });
});
