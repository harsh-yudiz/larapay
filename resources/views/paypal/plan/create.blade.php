@extends('layout.app')
@section('content')
<section>
    <div class="container bg-white my-3">
        @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
        @endif
        <div class="card">
            <form action="{{route('paypal-store-plan')}}" id="product-form" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="productid" value="{{$productId}}" />
                <div class="form-group">
                    <label for="uname"><b>Plan Name</b></label>
                    <input type="text" class="form-control" placeholder="Plan Name" id="planname" name="planname"></br>
                </div>

                <div class="form-group">
                    <label for="uname"><b>Description</b></label>
                    <textarea id="description" class="form-control" name="description" id="description" rows="3" cols="50"></textarea></br>
                </div>

                <div class="form-group">
                    <label for="uname"><b>Tax</b></label>
                    <input type="number" class="form-control" id="tax" name="tax" min="1" max="100"></br>
                </div>

                <div class="form-group">
                    <label for="cars"><b>Plan Type:</b></label>
                    <select name="billingperiod" class="form-control" id="billingperiod">
                        <option value="">Select</option>
                        <option value="FIXED">FIXED</option>
                        <option value="INFINITE">INFINITE</option>
                    </select></br>
                </div>

                <div id="row">
                    <h4><b>Plan Definition</b></h4>
                    <div class="form-group">
                        <label for="uname"><b>Product Definition Name</b></label>
                        <input type="text" class="form-control" placeholder="Product Definition Name" id="productdefinitionname" name="productdefinitionname[]"></br>
                    </div>

                    <div class="form-group">
                        <label for="uname"><b>Plan Price</b></label>
                        <input type="number" class="form-control" id="planprice" name="planprice[]" min="1" max="99999"></br>
                    </div>

                    <div class="form-group">
                        <label for="cars"><b>Plan Type:</b></label>
                        <select name="billingperiod[]" class="form-control" id="plantype">
                            <option value="">Select</option>
                            <option value="TRIAL">TRIAL</option>
                            <option value="REGULAR">REGULAR</option>
                        </select></br>
                    </div>

                    <div class="form-group">
                        <label for="cars"><b>Plan Frequently:</b></label>
                        <select name="planfrequency[]" class="form-control" id="planfrequency">
                            <option value="">Select</option>
                            <option value="WEEK">WEEK</option>
                            <option value="DAY">DAY</option>
                            <option value="MONTH">MONTH</option>
                            <option value="YEAR">YEAR</option>
                        </select></br>
                    </div>
                </div>

                <div id="newinput"></div>

                <button id="rowAdder" type="button" class="btn btn-dark">
                    <span class="bi bi-plus-square-dotted">
                    </span> ADD
                </button>
                <div class="form-group">
                    <button type="submit">Create Plan</button>
                </div>
            </form>
        </div>
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

        $("#rowAdder").click(function() {
            newRowAdd =
                '<div id="row">' +
                '<h4><b>Plan Definition</b></h4>' +
                '<div class="input-group-prepend">' +
                '<button class="btn btn-danger" id="DeleteRow" type="button">' +
                '<i class="bi bi-trash"></i> Delete</button> </div>' +
                '<div class="form-group">' +
                '<label for="uname"><b>Product Definition Name</b></label>' +
                '<input type="text" class="form-control" placeholder="Product Definition Name" id="productdefinitionname" name="productdefinitionname[]"></br>' +
                '</div>' +
                ' <div class="form-group">' +
                '<label for="uname"><b>Plan Price</b></label>' +
                '<input type="number" class="form-control" id="planprice" name="planprice[]" min="1" max="99999"></br>' +
                '</div>' +
                '<div class="form-group">' +
                '<label for="cars"><b>Plan Type:</b></label>' +
                '<select name="billingperiod[]" class="form-control" id="plantype">' +
                '<option value="">Select</option>' +
                '<option value="TRIAL">TRIAL</option>' +
                '<option value="REGULAR">REGULAR</option>' +
                '</select></br>' +
                '</div>' +
                '<div class="form-group">' +
                '<label for="cars"><b>Plan Frequently:</b></label>' +
                '<select name="planfrequency[]" class="form-control" id="planfrequency">' +
                '<option value="">Select</option>' +
                '<option value="WEEK">WEEK</option>' +
                '<option value="DAY">DAY</option>' +
                '<option value="MONTH">MONTH</option>' +
                '<option value="YEAR">YEAR</option>' +
                '</select></br>' +
                '</div>' +
                '</div>' +
                '</div>';

            $('#newinput').append(newRowAdd);
        });

        $("body").on("click", "#DeleteRow", function() {
            $(this).parents("#row").remove();
        })



    });
</script>
@endpush