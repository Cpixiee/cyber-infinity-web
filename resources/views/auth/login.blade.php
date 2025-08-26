@extends('layouts.master')

@section('title', 'Login - Cyber Infinity')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endpush

@section('content')
<div class="auth-container flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <canvas id="matrix" class="matrix-bg"></canvas>
    <div class="auth-box">
        <h2 class="terminal-text">Login ke Cyber Infinity</h2>
        <p class="terminal-text">>> Initializing secure connection...</p>

        <form id="loginForm" class="mt-8 space-y-6" action="{{ route('login') }}" method="POST" onsubmit="return handleLoginSubmit(event)">
            @csrf

            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="email" class="sr-only">Email</label>
                    <input id="email" name="email" type="email" autocomplete="email" required
                        class="w-full px-3 py-2 border placeholder-gray-500 text-gray-100 bg-gray-800/50 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 sm:text-sm transition-all duration-200"
                        placeholder="Email" value="{{ old('email') }}">
                </div>
                <div>
                    <label for="password" class="sr-only">Password</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                        class="w-full px-3 py-2 border placeholder-gray-500 text-gray-100 bg-gray-800/50 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 sm:text-sm transition-all duration-200"
                        placeholder="Password">
                </div>
                
                <!-- CAPTCHA -->
                <div>
                    <label for="captcha" class="sr-only">CAPTCHA</label>
                    <div class="flex gap-3 items-center">
                        <div class="flex-1">
                            <input id="captcha" name="captcha" type="text" required maxlength="5"
                                class="w-full px-3 py-2 border placeholder-gray-500 text-gray-100 bg-gray-800/50 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 sm:text-sm transition-all duration-200 uppercase"
                                placeholder="Masukkan CAPTCHA" autocomplete="off">
                        </div>
                        <div class="captcha-container">
                            <img id="captchaImage" src="{{ route('captcha.generate') }}" alt="CAPTCHA" 
                                 class="border border-gray-600 rounded cursor-pointer hover:opacity-80 transition-opacity"
                                 onclick="refreshCaptcha()" title="Klik untuk refresh CAPTCHA">
                        </div>
                        <button type="button" onclick="refreshCaptcha()" 
                                class="px-3 py-2 bg-gray-700 text-gray-300 rounded-md hover:bg-gray-600 transition-colors">
                            <i class="fas fa-refresh"></i>
                        </button>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Klik gambar untuk refresh CAPTCHA</p>
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="cyber-button" id="loginBtn">
                    <span id="loginBtnText">Login</span>
                    <i id="loginSpinner" class="fas fa-spinner fa-spin ml-2 hidden"></i>
                </button>
            </div>
        </form>

        <div class="mt-6 text-center auth-footer">
            <p class="text-sm">
                Belum punya akun?
                <a href="{{ route('register') }}">
                    Daftar di sini
                </a>
            </p>
        </div>
    </div>
</div>

<script>
// Refresh CAPTCHA
function refreshCaptcha() {
    const captchaImage = document.getElementById('captchaImage');
    const captchaInput = document.getElementById('captcha');
    
    // Add loading effect
    captchaImage.style.opacity = '0.5';
    
    // Generate new CAPTCHA with timestamp to prevent caching
    const timestamp = new Date().getTime();
    captchaImage.src = '{{ route("captcha.generate") }}?' + timestamp;
    
    // Clear input and focus
    captchaInput.value = '';
    captchaInput.focus();
    
    // Reset opacity when loaded
    captchaImage.onload = function() {
        this.style.opacity = '1';
    };
}

// Handle login form submission
function handleLoginSubmit(event) {
    event.preventDefault();
    
    // Validate CAPTCHA
    const captchaInput = document.getElementById('captcha');
    if (!captchaInput.value || captchaInput.value.length !== 5) {
        showErrorToast('CAPTCHA tidak valid', 'Silakan masukkan 5 karakter CAPTCHA');
        captchaInput.focus();
        refreshCaptcha();
        return false;
    }
    
    // Show loading state
    setButtonLoading('loginBtn', true, 'Logging in...');
    
    // Validate form
    if (!validateFormFields('loginForm')) {
        setButtonLoading('loginBtn', false);
        document.getElementById('loginBtnText').textContent = 'Login';
        refreshCaptcha();
        return false;
    }
    
    // Submit form
    document.getElementById('loginForm').submit();
    return true;
}

// Show flash messages
document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
        showSuccessToast("{{ session('success') }}");
    @endif

    @if(session('error'))
        showErrorToast("{{ session('error') }}");
    @endif

    @if($errors->any())
        showValidationErrors(@json($errors->all()));
        // Refresh CAPTCHA if there were validation errors
        setTimeout(refreshCaptcha, 1000);
    @endif
    
    // Auto uppercase CAPTCHA input
    document.getElementById('captcha').addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });
});
</script>
@endsection
