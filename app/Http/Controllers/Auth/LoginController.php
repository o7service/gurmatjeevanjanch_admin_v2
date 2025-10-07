<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */ 
    // protected $redirectTo = '/home';
    protected function authenticated(Request $request, $user)
    {
        if ($user->user_type == 1) {
            return redirect()->route('dashboard')->with("success", "Login Successfull");
        }
        if ($user->user_type == 2) {
            return redirect()->route('dashboard')->with("success", "Login Successfull");
        } else {
            return redirect()->route('dashboard')->with("error", "Invalid User Type");
        }
    }


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}
