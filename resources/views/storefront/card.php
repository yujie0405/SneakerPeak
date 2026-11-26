


<form action="" method="post">                       
                                <br>
                                <br>
                                <h3>Card Holder Name:</h3>
                                <input type="text" name="chn" required>
                                <span id="EmptyHolderName"></span>

                                <h3>Card Number:</h3>

                                <input type="text" name="card_number" id="card_number" maxlength="19" placeholder="xxxx xxxx xxxx xxxx"   required>
                                <script>
                                    // Get the input element
                                    const cardNumberInput = document.getElementById('card_number');

                                    // Get the error message element
                                    const errorMessageElement = document.getElementById('cardNumberError');

                                    // Add an event listener for the 'input' event
                                    cardNumberInput.addEventListener('input', function() {
                                    // Remove non-numeric characters from the input value
                                    this.value = this.value.replace(/[^0-9]/g, '');

                                    // Validate the input against the card number format
                                    const cardNumberPattern = /^\d{4}\s\d{4}\s\d{4}\s\d{4}$/;
                                    const isValidCardNumber = cardNumberPattern.test(this.value);

                                    // Toggle error message visibility based on validation result
                                    errorMessageElement.style.display = isValidCardNumber ? 'none' : 'block';
                                    });
                                    </script>
                                    <p id="cardNumberError" style="color: red; display: none;">Please enter a valid card number.</p>
                                <br>
                                <br>  
                                <div class="key">
                                    <div class="date">
                                        <h3>Expiry Date:</h3>
                                        <input type="text" name="expiry_date" id="expiry_date" maxlength="5" placeholder="MM/YY" oninput="formatExpiryDate()" required>

                                    </div>
                                    <div class="cvv">
                                        <h3>CVV:</h3>
                                        <input type="number" name="cvv" id="cvv" min="0" max="999" oninput="validateCVV()" placeholder="xxx" required>
                                        <span id="ErrorCVV"></span>
                                    </div>
                                </div>
                                
                                

                                <div class="submit">
                                    <input type="submit" value="Pay Now" name="Checkout" onclick="validateCardNumber(event)">
                                    </div>

</form>                              
                           