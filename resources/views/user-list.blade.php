@extends('layout.app')
@section('content')
<section>
  <table class="table">
    <thead>
      <tr>
        <th scope="col">Index</th>
        <th scope="col">User Name</th>
        <th scope="col">Email</th>
        <th scope="col">Plan</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($users as $user)
      <tr>
        <th scope="row">{{$user->id}}</th>
        <td>{{$user->name}}</td>
        <td>{{$user->email}}</td>
        @if ($user->subscription)
        <td>{{$user->subscription->product->product_name}}</td>
        @endif
        @if($user->subscription && $user->subscription->subscription_id)
        <td><a href="{{env('APP_URL')}}/stripe/cancel/subscription/{{$user->subscription->id}}">Cancel Subscription</a></td>
        @endif
      </tr>
      @endforeach
    </tbody>
  </table>
</section>
@endsection