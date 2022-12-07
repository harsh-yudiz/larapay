@extends('layout.app')
@section('content')
<section>
<a href="{{route('paypal-create-prodcut')}}">Create Product</a></td>
  <table class="table">
    <thead>
      <tr>
        <th scope="col">Index</th>
        <th scope="col">Product Name</th>
        <th scope="col">Description</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($products as $product)
      <tr>
        <th scope="row">{{$product->id}}</th>
        <td>{{$product->product_name}}</td>
        <td>{{$product->description}}</td>
        <td>
          <!-- <a href="{{env('APP_URL')}}/paypal/edit/product/{{$product->id}}">Edit Product</a> -->
        <a href="{{env('APP_URL')}}/paypal/create/plan/{{$product->id}}">Create Plan</a></td>
      </tr>
      @endforeach
    </tbody>
  </table>
</section>
@endsection