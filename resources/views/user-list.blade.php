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
        <th scope="col">Action

        </th>
      </tr>
    </thead>
    <tbody>
      @foreach ($users as $user)
      <tr>
        <th scope="row">{{$user->id}}</th>
        <td>{{$user->name}}</td>
        <td>{{$user->email}}</td>
        @if ($user->subscription && $user->subscription->product != null)
        <td>{{$user->subscription->product->product_name}}</td>
        @else
        <td></td>
        @endif
        @if ($user->subscription && $user->subscription->subscription_id)
        @if($user->subscription->status == 'activated')
        @if($user->subscription->is_subscription == 'stripe')
        <td><a href="{{env('APP_URL')}}/stripe/cancel/subscription/{{$user->subscription->id}}">Cancel Subscription</a>
          <a href="{{env('APP_URL')}}/stripe/subscription/avtive-deactive/{{$user->subscription->id}}">Deactivated subscription</a>
        </td>
        @else
        <td><a href="{{env('APP_URL')}}/paypal/subscription/active-deactive/{{$user->subscription->id}}">Cancel Subscription</a>
          <a href="{{env('APP_URL')}}/paypal/subscription/active-deactive/{{$user->subscription->id}}">Deactivated subscription</a>
        </td>
        @endif
        @else
        @if ($user->subscription->status != 'canceled')
        @if ($user->subscription->is_subscription == 'stripe')
        <td><a href="{{env('APP_URL')}}/stripe/subscription/avtive-deactive/{{$user->subscription->id}}">Activated subscription</a></td>
        @else
        <td><a href="{{env('APP_URL')}}/paypal/subscription/active-deactive/{{$user->subscription->id}}">Activated subscription</a></td>
        @endif
        @else
        <td></td>
        @endif
        @endif
        @else
        <td></td>
        @endif
      </tr>
      @endforeach
    </tbody>
  </table>
</section>
@endsection