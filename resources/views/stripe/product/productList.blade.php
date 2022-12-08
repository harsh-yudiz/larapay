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
      @if(!empty($products) && $products->count())
      @foreach ($products as $product)
      <tr>
        <th scope="row">{{$product->id}}</th>
        <td>{{$product->product_name}}</td>
        <td>{{$product->product_price}}</td>
        @if($product->status == 'activate')
        <td><a href="{{env('APP_URL')}}/stripe/subscription/{{$product->id}}">Subscribe</a></td>
        @else
        <td>
          <p>Product is not activate</p>
        </td>
        @endif
        <td><a href="{{env('APP_URL')}}/stripe/edit/product/{{$product->id}}">Edit</a>
        </td>
      </tr>
      @endforeach
      @else
      <tr>
        <td colspan="10">There are no data.</td>
      </tr>
      @endif
    </tbody>
  </table>
  {!! $products->links() !!}
</section>
@endsection