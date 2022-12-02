@extends('layout.app')
@section('content')
@dd($products->description)
<section>
    <div>
        @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
        @endif
        <form action="{{route('paypal-update-product', $products->id)}}" id="product-form" method="post" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="container">
                <div class="form-group">
                    <label for="uname"><b>Product Name</b></label>
                    <input type="text" class="form-control" placeholder="Product Name" id="productname" name="productname" readonly></br>
                </div>

                <div class="form-group">
                    <label for="uname"><b>Description</b></label>
                    <textarea id="description" class="form-control" name="description" value="{{$products->description}}" id="description" rows="3" cols="50"></textarea></br>
                </div>

                <div class="form-group">
                    <label for="uname"><b>Category</b></label>
                    <input type="text" class="form-control" id="category" name="category"></br>
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
                category: {
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
                category: {
                    required: "@lang('validation.required', ['attribute' => 'category'])",
                    not_empty: "@lang('validation.not_empty', ['attribute' => 'category'])",
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
    });
</script>
@endpush