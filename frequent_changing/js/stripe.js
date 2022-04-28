"use strict";
var stripe = Stripe(share_key);
// Create an instance of elements
var elements = stripe.elements();

var style = {
    base: {
        fontWeight: 400,
        fontFamily: 'Roboto, Open Sans, Segoe UI, sans-serif',
        fontSize: '16px',
        lineHeight: '1.4',
        color: '#666',
        backgroundColor: '#fff',
        '::placeholder': {
            color: '#888',
        },
    },
    invalid: {
        color: '#eb1c26',
    }
};

var cardElement = elements.create('cardNumber', {
    style: style
});
cardElement.mount('#card_number');

var exp = elements.create('cardExpiry', {
    'style': style
});
exp.mount('#card_expiry');

var cvc = elements.create('cardCvc', {
    'style': style
});
cvc.mount('#card_cvc');

// Validate input of the card elements
let hidden_alert = $("#hidden_alert").val();
let hidden_ok = $("#hidden_ok").val();
var resultContainer = document.getElementById('paymentResponse');
cardElement.addEventListener('change', function (event) {
    if (event.error) {
        swal({
            title: hidden_alert,
            text: event.error.message,
            confirmButtonText:hidden_ok,
            confirmButtonColor: '#3c8dbc'
        });
    } else {
        resultContainer.innerHTML = '';
    }
});

// Get payment form element
let form = document.getElementById('paymentFrm');

// Create a token when the form is submitted.
form.addEventListener('submit', function (e) {
    e.preventDefault();
    createToken();
});

// Create single-use token to charge the user
function createToken() {
    stripe.createToken(cardElement).then(function (result) {
        if (result.error) {
            // Inform the user if there was an error
            swal({
                title: hidden_alert,
                text: result.error.message,
                confirmButtonText:hidden_ok,
                confirmButtonColor: '#3c8dbc'
            });
        } else {
            // Send the token to your server
            stripeTokenHandler(result.token);
        }
    });
}

// Callback to handle the response from stripe
function stripeTokenHandler(token) {
    // Insert the token ID into the form so it gets submitted to the server
    let hiddenInput = document.createElement('input');
    hiddenInput.setAttribute('type', 'hidden');
    hiddenInput.setAttribute('name', 'stripeToken');
    hiddenInput.setAttribute('value', token.id);
    form.appendChild(hiddenInput);

    // Submit the form
    form.submit();
}
