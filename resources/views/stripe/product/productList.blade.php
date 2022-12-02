@extends('layout.app')
@section('content')
<section>
  <table class="table">
    <thead>
      <tr>
        <th scope="col">Index</th>
        <th scope="col">Prodct Name</th>
        <th scope="col">Price</th>
        <th scope="col">Subscribe</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
    <a href="{{route('stripe-create-product')}}">Create Product</a>
      @foreach ($products as $product)
      <tr>
        <th scope="row">{{$product->id}}</th>
        <td>{{$product->product_name}}</td>
        <td>{{$product->product_price}}</td>
        <td><a href="{{env('APP_URL')}}/stripe/subscription/{{$product->id}}">Subscribe</a>
        <td><a href="{{env('APP_URL')}}/stripe/edit/product/{{$product->id}}">Edit</a>
            <a href="{{env('APP_URL')}}/stripe/delete/product/{{$product->id}}">Delete</a>
            <a href="{{env('APP_URL')}}/stripe/edit/product/{{$product->id}}">Create New Produt Price</a>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</section>
@endsection