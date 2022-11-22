@extends('layout.app')
@section('content')
<section>
    <div class="h-100">
        <div class="container mt-5">
            <div class="row bg-light py-4">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <div class="alert alert-success alert-checkout" id="alert-div">
                        <span id="alert-checkout-sucess"></span>
                    </div>
                    <form method="post" id="checkout-form" action="{{ route('checkout') }}">
                        @csrf
                        <div class="form-group ">
                            <input type="number" class="form-control" id="amount" name="amount" min="1" max="99999">
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Checkout</button>
                    </form>
                </div>
                <div class="col-md-3"></div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('extra-javascript')
<script>
    $(document).ready(function() {
        $("#checkout-form").validate({
            rules: {
                amount: {
                    required: true,
                    not_empty: true,
                    digits: true
                },
            },
            messages: {
                amount: {
                    required: "Your amount is required.",
                    not_empty: "Your amount is not empty.",
                    digits: "Your amount field is contain only digit."
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
        var message = localStorage.getItem("paymentSucessMessage");
        $('#alert-checkout-sucess').text(message).show();
        localStorage.clear();


    });
</script>
@endpush