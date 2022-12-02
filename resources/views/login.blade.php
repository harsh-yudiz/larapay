@extends('layout.app')
@section('content')
<section>
    <div class="container bg-white my-3">
        @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
        @endif
        <div class="card">
            <form action="{{route('user-login')}}" method="post">
                @csrf
                <label for="email"><b>Email</b></label>
                <input type="text" placeholder="Enter Email" name="email" required>

                <label for="psw"><b>Password</b></label>
                <input type="password" placeholder="Enter Password" name="password" required>

                <button type="submit">Login</button>
            </form>
        </div>
    </div>
</section>
@endsection