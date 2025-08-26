<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255', 'unique:users,username,' . $user->id, 'regex:/^[a-zA-Z0-9_]+$/'],
        ]);

        $user->name = $validated['name'];
        
        if ($request->filled('username')) {
            $user->username = $validated['username'];
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui.'
        ]);
    }

    public function updatePassword(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $validated = $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'min:8', 'confirmed'],
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password saat ini tidak cocok.'
            ], 422);
        }

        $user->password = Hash::make($validated['new_password']);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diperbarui.'
        ]);
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'image', 'max:5120'] // 5MB
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Delete old avatar if exists
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Store new avatar
        $avatarPath = $request->file('avatar')->store('avatars', 'public');
        
        $user->avatar = $avatarPath;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Avatar berhasil diperbarui.',
            'avatar_url' => Storage::url($avatarPath)
        ]);
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'unique:users,email,' . Auth::id()]
        ]);

        $email = $request->email;
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Store OTP in cache for 10 minutes
        Cache::put("otp_email_change_" . Auth::id(), [
            'email' => $email,
            'otp' => $otp
        ], 600);

        // Send OTP via email (simplified - you can implement actual email sending)
        // For now, we'll just return the OTP in response for testing
        // In production, remove this and implement proper email sending
        
        try {
            // You can implement actual email sending here
            // Mail::to($email)->send(new OtpMail($otp));
            
            return response()->json([
                'success' => true,
                'message' => 'Kode OTP telah dikirim ke email baru.',
                'debug_otp' => $otp // Remove this in production
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim email OTP.'
            ], 500);
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'string', 'size:6']
        ]);

        $userId = Auth::id();
        $cachedData = Cache::get("otp_email_change_" . $userId);

        if (!$cachedData || 
            $cachedData['email'] !== $request->email || 
            $cachedData['otp'] !== $request->otp) {
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP tidak valid atau sudah kadaluarsa.'
            ], 422);
        }

        // Update user email
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->email = $request->email;
        $user->save();

        // Clear OTP from cache
        Cache::forget("otp_email_change_" . $userId);

        return response()->json([
            'success' => true,
            'message' => 'Email berhasil diperbarui.'
        ]);
    }
}
