<?php

namespace App\Http\Controllers;

use App\Http\Requests\register;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function register()
    {
        return view('register');
    }

    public function UserRegister(register $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($user) {
            return redirect()->route('login');
        }
    }

    public function UserLogin(Request $request)
    {
        $credentials = [
            'email' => $request['email'],
            'password' => $request['password'],
        ];

        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            return redirect()->route('checkout-view')->with('message', 'You have sucessfully login.');
        } else {
            return redirect()->back()->with('message', 'Someting went to wrong, Plesae try again.');
        }
    }
    
    public function userLogout()
    {
        Session::flush();

        Auth::logout();

        return redirect()->route('login');
    }

    public function userList()
    {
        $users = User::with('subscription.product')->get();
        return view('user-list', compact('users'));
    }

    public function Sucess()
    {
        return view('sucess');
    }
}
