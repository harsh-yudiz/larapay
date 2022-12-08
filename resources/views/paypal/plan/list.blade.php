@extends('layout.app')
@section('content')
<section>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Index</th>
                <th scope="col">Plan Name</th>
                <th scope="col">Description</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @if(!empty($plans) && $plans->count())
            @foreach ($plans as $plans)
            <tr>
                <th scope="row">{{$plans->id}}</th>
                <td>{{$plans->product_name}}</td>
                <td>{{$plans->description}}</td>
                <td>
                    <a href="{{env('APP_URL')}}/paypal/subscription/create/{{$plans->id}}">Purchase Subscription</a>
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
    {!! $plans->links() !!}
</section>
@endsection