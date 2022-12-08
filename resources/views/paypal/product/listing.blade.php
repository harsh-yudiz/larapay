@extends('layout.app')
@section('content')
<section>
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
      @if(!empty($products) && $products->count())
      @foreach ($products as $product)
      <tr>
        <th scope="row">{{$product->id}}</th>
        <td>{{$product->product_name}}</td>
        <td>{{$product->description}}</td>
        <td>
          <a href="{{env('APP_URL')}}/paypal/create/plan/{{$product->id}}">Create Plan</a>
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