const draftForm = document.querySelector('[data-product-draft]');
const sizeRows = draftForm.querySelector('[data-size-rows]');
const sizeTemplate = draftForm.querySelector('[data-size-template]');
const addSize = draftForm.querySelector('[data-add-size]');
const summary = draftForm.querySelector('[data-draft-summary]');
const saveProduct = draftForm.querySelector('[data-draft-save]');
const nextStep = draftForm.querySelector('[data-draft-next]');
const backStep = draftForm.querySelector('[data-draft-back]');
const progress = draftForm.querySelector('[data-draft-progress]');
const steps = Array.from(draftForm.querySelectorAll('[data-draft-step]'));
let currentStep = 0;
const maxRows = Number(draftForm.dataset.sizeLimit);
let nextRow = Math.max(0, ...Array.from(sizeRows.querySelectorAll('select'), control => Number(control.name.match(/\d+/)[0]))) + 1;

function showDraftStep(index, focusHeading = false) {
    currentStep = index;
    for (const [position, step] of steps.entries()) {
        const current = position === index;
        step.hidden = !current;
        if (current) progress.children[position].setAttribute('aria-current', 'step');
        else progress.children[position].removeAttribute('aria-current');
    }
    backStep.hidden = index === 0;
    nextStep.hidden = index === steps.length - 1;
    saveProduct.hidden = !nextStep.hidden;
    if (focusHeading) steps[index].querySelector('h2').focus();
}

progress.hidden = false;
showDraftStep(0);
draftForm.noValidate = true;

function revealDraftField(field) {
    const index = steps.findIndex(step => step.contains(field));
    if (index !== -1) showDraftStep(index);
    field.focus();
}

function validateDraftFields(fields) {
    for (const field of fields) {
        if (field.type === 'file') {
            const file = field.files[0];
            let message = '';
            if (file?.size > 2000000) message = 'Choose an image smaller than 2 MB.';
            else if (file?.type && !['image/jpeg', 'image/png', 'image/webp', 'image/gif'].includes(file.type)) {
                message = 'Choose a JPEG, PNG, WebP or GIF image.';
            }
            field.setCustomValidity(message);
        }
        if (field.willValidate && !field.validity.valid) {
            revealDraftField(field);
            field.reportValidity();
            return false;
        }
    }
    return true;
}

function advanceDraft() {
    if (validateDraftFields(steps[currentStep].querySelectorAll('input, select, textarea'))) {
        showDraftStep(currentStep + 1, true);
    }
}

nextStep.addEventListener('click', advanceDraft);
backStep.addEventListener('click', () => showDraftStep(currentStep - 1, true));

function updateSizeButtons() {
    addSize.disabled = sizeRows.children.length >= maxRows;
    for (const button of sizeRows.querySelectorAll('[data-remove-size]')) {
        button.disabled = sizeRows.children.length === 1;
    }
}

addSize.hidden = false;
updateSizeButtons();
addSize.addEventListener('click', () => {
    const row = sizeTemplate.content.firstElementChild.cloneNode(true);
    for (const control of row.querySelectorAll('[name]')) {
        control.name = control.name.replace('INDEX', nextRow);
    }
    for (const error of row.querySelectorAll('[data-draft-error]')) {
        error.dataset.draftError = error.dataset.draftError.replace('INDEX', nextRow);
    }
    nextRow += 1;
    sizeRows.append(row);
    updateSizeButtons();
    row.querySelector('select').focus();
});
sizeRows.addEventListener('click', event => {
    const remove = event.target.closest('[data-remove-size]');
    if (!remove || sizeRows.children.length === 1) return;
    const row = remove.closest('[data-size-row]');
    const remaining = row.nextElementSibling || row.previousElementSibling;
    row.remove();
    updateSizeButtons();
    remaining.querySelector('select').focus();
});

function showDraftErrors(errors) {
    const messages = draftForm.querySelectorAll('[data-draft-error]');
    let firstInvalidField = null;
    for (const message of messages) {
        const field = message.dataset.draftError;
        const error = errors[field] || '';
        message.textContent = error;
        message.hidden = !error;
        const control = draftForm.elements.namedItem(field);
        if (error && firstInvalidField === null) {
            firstInvalidField = control || message.closest('[data-draft-step]')?.querySelector('select, input');
        }
        if (!control) continue;
        message.id = 'draft-error-' + field.replace(/[^a-z0-9]+/gi, '-');
        const descriptions = (control.getAttribute('aria-describedby') || '').split(/\s+/).filter(id => id && id !== message.id);
        if (error) {
            descriptions.push(message.id);
            control.setAttribute('aria-invalid', 'true');
        } else {
            control.removeAttribute('aria-invalid');
        }
        if (descriptions.length) control.setAttribute('aria-describedby', descriptions.join(' '));
        else control.removeAttribute('aria-describedby');
    }
    summary.hidden = Object.keys(errors).length === 0;
    if (!summary.hidden) {
        summary.textContent = errors.form || 'Review the fields marked below and save again.';
        revealDraftField(firstInvalidField || summary);
    }
}

draftForm.addEventListener('submit', async event => {
    event.preventDefault();
    if (saveProduct.disabled) return;
    if (currentStep < steps.length - 1) {
        advanceDraft();
        return;
    }
    if (!validateDraftFields(Array.from(draftForm.elements))) return;
    // The live form holds the shared draft, including the selected File objects.
    const draft = new FormData(draftForm);
    saveProduct.disabled = true;
    try {
        const response = await fetch(draftForm.action, {
            method: 'POST', headers: {Accept: 'application/json'}, body: draft
        });
        if (response.status === 403) throw new Error('The session has expired. Sign in again before saving.');
        const result = await response.json();
        if (response.ok && result.location) {
            window.location.assign(result.location);
            return;
        }
        showDraftErrors(result.errors || {form: 'The product could not be saved. Try again.'});
    } catch (error) {
        showDraftErrors({form: error.message.startsWith('The session') ? error.message : 'The product could not be saved. Try again.'});
    } finally {
        saveProduct.disabled = false;
    }
});
