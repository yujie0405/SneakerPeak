function displayForm() {
    var newUserForm = document.getElementById("first-form");
    var loginForm = document.getElementById("second-form");
    var userType = document.querySelector('input[name="payment-type"]:checked').value;
    
    if (userType === "Debit/Credit-Payment") {
    newUserForm.style.display = "block";
    loginForm.style.display = "none";
    } else {
    newUserForm.style.display = "none";
    loginForm.style.display = "block";
    }
}

function formatCardNumber() {
    const input = document.getElementById('card_number');
    const value = input.value.replace(/\D/g, '');
    const formattedValue = value.replace(/^(.{4})(.{4})(.{4})(.*)$/, '$1 $2 $3 $4');
    input.value = formattedValue;
}

function formatExpiryDate() {
    const input = document.getElementById('expiry_date');
    const value = input.value.replace(/\D/g, '');
    const formattedValue = value.replace(/^(.{2})(.*)$/, '$1/$2');
    input.value = formattedValue;
}
function validateCVV() {
    const input = document.getElementById('cvv');
    const value = input.value.replace(/\D/g, '');
    const maxDigits = 3;
    if (value.length > maxDigits) {
        input.value = value.slice(0, maxDigits);
    }
}

