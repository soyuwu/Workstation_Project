<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    private $userModel;
    public function __construct(){
        $this->userModel = new User();
    }

    //Regsister
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
        $this->userModel = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Log in immediately
        AuthController::logIn($request);

        // Redirect to HomePage.
        return redirect('/'); 
    }
    
    //LogIn
    // 3. Show form đăng nhập.
    public function showLogInForm(){
        return view('auth.logInForm');
    }

    public function logIn(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email', $email)->first();

        if(!$user){
            return back()->withErrors(['email' => 'Email này chưa được đăng ký']);
        }

        if(Hash::check($password, $user->password)){
            Auth::login($user);
            $request->session()->regenerate();
            Session::put('user_id', $user->id);
            Session::put('user_role', $user->role);

            return redirect('/')->with('success', 'Dang nhap thanh cong!');
        }

        return back()->withErrors(['password' => 'Mat khau sai']);
    }

    //LogOut
    public function logOut(){
        Session::forget('user_id');
        Session::forget('user_role');
        
        return redirect('/')->with('success', 'Ban da dang xuat thanh cong');
    }
}
