<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'birth_day' => 'required|numeric|between:1,31',
            'birth_month' => 'required|numeric|between:1,12',
            'birth_year' => 'required|numeric|between:1900,' . date('Y'),
        ]);

        // Create birthdate from components
        $birthdate = sprintf('%04d-%02d-%02d', 
            $request->birth_year, 
            $request->birth_month, 
            $request->birth_day
        );

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'birthdate' => $birthdate,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Selamat datang di Cyber Infinity! Akun Anda berhasil dibuat.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Create login notification for the user
            Notification::createLoginNotification(Auth::user());
            
            return redirect()->intended('dashboard')->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '!');
        }

        return back()->with('error', 'Email atau password yang Anda masukkan salah.');
    }

    public function logout(Request $request)
    {
        $name = Auth::user()->name;
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'Sampai jumpa lagi, ' . $name . '! Anda telah berhasil keluar.');
    }
}
