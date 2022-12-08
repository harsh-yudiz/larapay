@extends('layout.app')
@section('content')
<section>
    <div class="container bg-white my-3">
        <div>
            @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
            @endif
        </div>
        <div class="card">
            <div class="card-body shadow">
                <form action="{{route('user-register')}}" method="post">
                    @csrf
                    <label for="email"><b>Name</b></label>
                    <input type="text" placeholder="Enter Name" name="name">

                    <label for="email"><b>Email</b></label>
                    <input type="text" placeholder="Enter Email" name="email">

                    <label for="psw"><b>Password</b></label>
                    <input type="password" placeholder="Enter Password" name="password">

                    <button type="submit">Register</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection