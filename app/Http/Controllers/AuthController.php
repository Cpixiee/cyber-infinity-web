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
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|string|email:rfc,dns|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
            'birth_day' => 'required|integer|between:1,31',
            'birth_month' => 'required|integer|between:1,12',
            'birth_year' => 'required|integer|between:1900,' . (date('Y') - 13), // Minimum age 13
        ]);

        // Validate birthdate
        if (!checkdate($request->birth_month, $request->birth_day, $request->birth_year)) {
            return back()->withErrors(['birth_day' => 'Invalid date provided.'])->withInput();
        }

        // Create birthdate from components
        $birthdate = sprintf('%04d-%02d-%02d', 
            $request->birth_year, 
            $request->birth_month, 
            $request->birth_day
        );

        $user = User::create([
            'name' => strip_tags($request->name),
            'email' => strtolower(trim($request->email)),
            'password' => Hash::make($request->password),
            'birthdate' => $birthdate,
            'role' => 'guest', // Default role
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Selamat datang di Cyber Infinity! Akun Anda berhasil dibuat.');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email:rfc,dns',
            'password' => 'required|min:8',
            'captcha' => 'required|string|min:5|max:5',
        ]);

        // Verify CAPTCHA first
        $userCaptcha = strtoupper(trim($request->captcha));
        $sessionCaptcha = strtoupper(session('captcha_text', ''));
        
        if (empty($sessionCaptcha) || $userCaptcha !== $sessionCaptcha) {
            // Clear CAPTCHA from session
            session()->forget('captcha_text');
            return back()->withErrors(['captcha' => 'CAPTCHA tidak valid atau sudah kedaluwarsa.'])->withInput($request->except('password', 'captcha'));
        }

        // Clear CAPTCHA from session after successful verification
        session()->forget('captcha_text');

        // Prepare credentials for authentication (without captcha)
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        // Rate limiting - 5 attempts per minute
        $key = 'login_attempts:' . $request->ip();
        $attempts = cache()->get($key, 0);
        
        if ($attempts >= 5) {
            return back()->with('error', 'Terlalu banyak percobaan login. Silakan coba lagi dalam 1 menit.');
        }

        // Sanitize email
        $credentials['email'] = strtolower(trim($credentials['email']));

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Clear login attempts on successful login
            cache()->forget($key);
            
            // Create login notification for the user
            Notification::createLoginNotification(Auth::user());
            
            return redirect()->intended('dashboard')->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '!');
        }

        // Increment failed attempts
        cache()->put($key, $attempts + 1, 60); // 60 seconds

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
