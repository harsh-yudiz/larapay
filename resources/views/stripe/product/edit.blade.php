@extends('layout.app')
@section('content')
<section>
    <div>
        @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
        @endif
        <form action="{{route('stripe-update-product',$product->id)}}" id="product-form" method="post" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="container">
                <div class="form-group">
                    <label for="uname"><b>Product Name</b></label>
                    <input type="text" class="form-control" placeholder="Product Name" value="{{$product->product_name}}" id="productname" name="productname" readonly></br>
                </div>

                <div class="form-group">
                    <label for="uname"><b>Description</b></label>
                    <textarea id="description" class="form-control" name="description" id="description" rows="3" cols="50">{{$product->description}}</textarea></br>
                </div>

                <div class="form-group">
                    <label for="uname"><b>Price</b></label>
                    <input type="number" class="form-control" id="price" name="price" value="{{$product->product_price}}" min="1" max="99999"></br>
                </div>

                <label for="uname"><b>Status</b></label>
                <div class="form-group form-check">
                    <input class="form-check-input" value="true" type="radio" name="status" id="status1">
                    <label class="form-check-label" for="flexRadioDefault1">
                        Activate
                    </label>
                </div>

                <div class="form-group form-check">
                    <input class="form-check-input" value="false" type="radio" name="status" id="status2">
                    <label class="form-check-label" for="flexRadioDefault2">
                        Deactivate
                    </label>
                </div>

                <div class="form-group">
                    <label for="cars"><b>Billing period:</b></label>
                    <select name="billingperiod" class="form-control" id="billingperiod" disabled>
                        <option value="">Select</option>
                        <option value="day" @if ($product->billing_period == 'day') selected @endif>Daily</option>
                        <option value="week" @if ($product->billing_period == 'week') selected @endif>Wekkly</option>
                        <option value="month" @if ($product->billing_period == 'month') selected @endif>Monthly</option>
                        <option value="year" @if ($product->billing_period == 'year') selected @endif>Yearly</option>
                    </select></br>
                </div>

                <div class="form-group">
                    <button type="submit">Update Product</button>
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
                description: {
                    required: true,
                    not_empty: true,
                },
            },
            messages: {
                description: {
                    required: "@lang('validation.required', ['attribute' => 'description'])",
                    not_empty: "@lang('validation.not_empty', ['attribute' => 'description'])",
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