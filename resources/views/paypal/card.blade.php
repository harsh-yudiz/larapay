@extends('layout.app')
@section('content')
<section class="payment-form">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <form method="post" id="payment-forms" action="{{ route('checkout-payment') }}">
                    @csrf
                    <h3>Payment</h3>
                    <label for="cardname">Card Number</label>
                    <div class="form-group mb-4">
                        <div class="form-control form-control-2 mb-0" id="card-number"></div>
                        <small><strong id="cardnumber-error" class="text-danger"></strong></small>
                    </div>
                    <label for="cardexpire">Card Expiry</label>
                    <div class="form-group mb-4 small-input">
                        <div class="form-control form-control-2 me-3 mb-0" id="card-expiry"></div>
                        <small><strong id="cardexpire-error" class="text-danger"></strong></small>
                        <span id="expirationmonth-errors-test" class="invalid-feedback"></span></small>
                    </div>
                    <label for="cardcvc">Card CvC</label>
                    <div class="form-group small-input mb-4">
                        <div class="stripe-card form-control form-control-2 mb-0" id="card-cvc"></div>
                        <small><strong id="cardcvc-error" class="text-danger"></strong></small>
                    </div>
                    <label for="nameoncard">Name on Card</label>
                    <input type="text" style="padding: 3px 8px; border-radius: 5px 5px" name="name_of_cards" id="name_of_card" class="stripe-card form-control form-control-2" placeholder="Name on card" style="text-transform: capitalize">
                    <span class="help-block">
                        <small><strong id="cardname-error" class="form-text"></strong></small>
                    </span>
                    <button type="submit" id="payment" class="btn" id="checkout">Pay</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
@push('extra-javascript')
<script>
    $(document).ready(function() {
        paypal.HostedFields.render({
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        "amount": {
                            "currency_code": "USD",
                            "value": 100,
                        }
                    }]
                });
            },
            fields: {
                number: {
                    selector: '#card-number',
                    placeholder: 'card number'
                },
                cvv: {
                    selector: '#card-cvc',
                    placeholder: 'CVV',
                },
                expirationDate: {
                    selector: '#card-expiry',
                    placeholder: 'mm/yyyy'
                }
            },
        }).then(function(hostedFieldsInstance) {
            var cvvLabel = document.querySelector('label[for="cardcvc"]'); // The label for your CVV field
            console.log(cvvLabel);

            hostedFieldsInstance.on('cardTypeChange', function(event) {
                // This event triggers when a change in card type is detected.
                // It triggers only from the number field.
                var cvvText;

                if (event.cards.length === 1) {
                    cvvText = event.cards[0].code.name;
                } else {
                    cvvText = 'CVV';
                }

                cvvLabel.innerHTML = cvvText;
                hostedFieldsInstance.setAttribute({
                    field: 'cvv',
                    attribute: 'placeholder',
                    value: cvvText
                });
            });
        });
    });
</script>
@endpush