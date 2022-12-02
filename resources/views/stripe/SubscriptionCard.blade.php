@extends('layout.app')
@section('content')
<section class="payment-form">
    <div class="container bg-white my-3">
        <div class="row">
            <div class="card">
                <div class="card-body shadow">
                    <form method="post" id="payment-forms" action="{{ route('stripe-create-subscription') }}">
                        @csrf
                        <h3>Payment</h3>
                        <input type="hidden" id="productId" name="prodId" value="{{$productId}}">
                        <input type="hidden" id="stripe_token" name="stripe_token" value="">
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
    </div>
</section>
@endsection
@push('extra-javascript')
<script src="https://js.stripe.com/v3/"></script>
<script>
    //Stripe payment method.
    const stripe = Stripe("{{ env('STRIPE_KEY') }}");
    const elements = stripe.elements();
    const card = elements.create('cardNumber', {
        placeholder: "Card number"
    });
    card.mount('#card-number');
    const cardExpiry = elements.create('cardExpiry');
    cardExpiry.mount('#card-expiry');
    const cvc = elements.create('cardCvc');
    cvc.mount('#card-cvc');
    card.on('change', (event) => {
        (event.error) ? $('#cardnumber-error').html(event.error.message): $('#cardnumber-error').empty();
    });
    cardExpiry.on('change', (event) => {
        (event.error) ? $('#cardexpire-error').html(event.error.message): $('#cardexpire-error').empty();
    });
    cvc.on('change', (event) => {
        (event.error) ? $('#cardcvc-error').html(event.error.message): $('#cardcvc-error').empty();
    });
    const cardHolderName = $('#name_of_card');
    const form = $('#payment-forms');
    $(document).ready(function() {
        $("#payment-forms").validate({
            rules: {
                name_of_cards: {
                    required: true,
                    not_empty: true,
                    maxlength: 50,
                    minlength: 3,
                    lettersonly: true
                },
            },
            messages: {
                name_of_cards: {
                    required: "Your card name is incomplete.",
                    not_empty: "Your card name is not empty.",
                    maxlength: "Your card name max length is 50 character.",
                    minlength: "Your card name mix length is 3 character.",
                    alphaonly: "Your card name is not contain numerica or special character."
                },
            },
            errorClass: 'invalid-feedback',
            errorElement: 'strong',
            highlight: function(element) {
                $(element).siblings('label').addClass('text-danger'); // For Label
            },
            unhighlight: function(element) {
                $(element).siblings('label').removeClass('text-danger'); // For Label
            },
            errorPlacement: function(error, element) {
                if (element.attr("data-error-container")) {
                    error.appendTo(element.attr("data-error-container"));
                } else {
                    error.insertAfter(element);
                }
            }
        });
        $(document).on('click', '#payment', async function(e) {
            $(".loader").show();
            e.preventDefault();

            const {
                paymentMethod,
                error
            } = await stripe.createPaymentMethod('card', card, {
                billing_details: {
                    name: cardHolderName.value
                }
            });
            if ($('#payment-forms').valid() === false && error && Object.keys(error).length > 0) {
                $(".loader").hide();
                return false;
            } else if ($('#payment-forms').valid() === false) {
                $(".loader").hide();
                return false;
            } else if (error && Object.keys(error).length > 0) {
                $(".loader").hide();
                return false;
            } else {
                //Stripe confirm payment method call 
                stripe.createToken(card).then(function(result) {
                    if (result.token) {
                        $('#stripe_token').val(result.token.id);
                        $(".loader").hide();
                        form.submit();
                    } else {
                        toastr.error("Plesase entered card details.");
                    }
                });
            }
        });
    });
</script>
@endpush