@extends('layout.app')
@section('content')
<section>
    <div>
        @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
        @endif
        <form action="{{route('stripe-store-product')}}" id="product-form" method="post" enctype="multipart/form-data">
            @csrf
            <div class="container">
                <div class="form-group">
                    <label for="uname"><b>Product Name</b></label>
                    <input type="text" class="form-control" placeholder="Product Name" id="productname" name="productname"></br>
                </div>

                <div class="form-group">
                    <label for="uname"><b>Description</b></label>
                    <textarea id="description" class="form-control" name="description" id="description" rows="3" cols="50"></textarea></br>
                </div>

                <div class="form-group">
                    <label for="uname"><b>Price</b></label>
                    <input type="number" class="form-control" id="price" name="price" min="1" max="99999"></br>
                </div>

                <div class="form-group">
                    <label for="cars"><b>Billing period:</b></label>
                    <select name="billingperiod" class="form-control" id="billingperiod">
                        <option value="">Select</option>
                        <option value="day">Daily</option>
                        <option value="week">Wekkly</option>
                        <option value="month">Monthly</option>
                        <option value="year">Yearly</option>
                    </select></br>
                </div>
                <div class="form-group">
                    <label for="cars"><b>Currency:</b></label>
                    <select name="currency" class="form-control" id="currency">
                        <option value="">Select</option>
                        @foreach ($currencys->pluck('currency') as $currency)
                        <option value="{{$currency}}">{{$currency}}</option>
                        @endforeach
                    </select></br>
                </div>
                


                <div class="form-group">
                    <button type="submit">Create Product</button>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection
@push('extra-javascript')
<script>
    $(document).ready(function() {
        $("#product-form").validate({
            rules: {
                productname: {
                    required: true,
                    not_empty: true,
                },
                description: {
                    required: true,
                    not_empty: true,
                },
                price: {
                    required: true,
                    not_empty: true,
                },
                productimage: {
                    extension: "jpg,jpeg,png",
                },
                billingperiod: {
                    required: true,
                    not_empty: true,
                },
            },
            messages: {
                productname: {
                    required: "@lang('validation.required', ['attribute' => 'productname'])",
                    not_empty: "@lang('validation.not_empty', ['attribute' => 'productname'])",
                },
                description: {
                    required: "@lang('validation.required', ['attribute' => 'description'])",
                    not_empty: "@lang('validation.not_empty', ['attribute' => 'description'])",
                },
                price: {
                    required: "@lang('validation.required', ['attribute' => 'price'])",
                    not_empty: "@lang('validation.not_empty', ['attribute' => 'price'])",
                    digits: "@lang('validation.digits', ['attribute' => 'price'])"
                },
                productimage: {
                    extension: "@lang('validation.extension', ['attribute' => 'productimage'])",
                },
                billingperiod: {
                    required: "@lang('validation.required', ['attribute' => 'billingperiod'])",
                    not_empty: "@lang('validation.not_empty', ['attribute' => 'billingperiod'])",
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