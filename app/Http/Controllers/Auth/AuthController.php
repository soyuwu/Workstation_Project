<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Authenticatable;

class AuthController extends Controller
{
    // 1. Show form cho người dùng.
    public function showRegisterForm() {
        return view('auth.register');
    }

    // 2. Khách bấm nút đăng ký.
    public function register(Request $request) {
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
        // Auth::login($user);

        // Redirect to HomePage.
        return redirect('/'); 
    }
}
