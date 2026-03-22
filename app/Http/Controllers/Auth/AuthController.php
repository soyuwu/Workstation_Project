<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Authenticatable;

class AuthController extends Controller
{
    // 1. Show form cho người dùng.
    public function showRegisterForm(){
        return view('auth.registerForm');
    }

    // 2. Khách bấm nút đăng ký.
    public function register(Request $request){
        // Validation.
        $request->validate([
            'name' => 'required|min:2',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Create new user.
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Log in immediately
        //Auth::login($user);

        // Redirect to HomePage.
        return redirect('/'); 
    }
    
    // 3. Show form đăng nhập.
    public function showLogInForm(){
        return view('auth.logInForm');
    }

    public function logIn(Request $request){
        $request->validate([
            'email' => 'required|email',
            'passsword' => 'required|min:8'
        ]);

        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email', $email)->first();

        if(!$user){
            return back()->withErrors(['email' => 'Email này chưa được đăng ký']);
        }

        if(password_verify($password, $user->password)){
            Session::put('user_id', $user->id);
            Session::put('user_role', $user->role);

            return redirect('/')->with('success', 'Dang nhap thanh cong!');
        }

        return back()->withErrors(['password' => 'Mat khau sai']);
    }
}
