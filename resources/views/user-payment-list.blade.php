@extends('layout.app')
@section('content')
<section>
  <table class="table">
    <thead>
      <tr>
        <th scope="col">Index</th>
        <th scope="col">User Name</th>
        <th scope="col">Email</th>
        <th scope="col">Payment</th>
        <th scope="col">Status</th>
      </tr>
    </thead>
    <tbody>
      @if(!empty($payments) && $payments->count())
      @foreach ($payments as $payment)
      <tr>
        <th scope="row">{{$payment->id}}</th>
        <td>{{$payment->user->name}}</td>
        <td>{{$payment->user->email}}</td>
        <td>{{$payment->amount}}</td>
        <td>{{$payment->status}}</td>
      </tr>
      @endforeach
      @else
      <tr>
        <td colspan="10">There are no data.</td>
      </tr>
      @endif
    </tbody>
  </table>
  {!! $payments->links() !!}
</section>
@endsection