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
            </div>

            <div>
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
// Handle login form submission
function handleLoginSubmit(event) {
    event.preventDefault();
    
    // Show loading state
    setButtonLoading('loginBtn', true, 'Logging in...');
    
    // Validate form
    if (!validateFormFields('loginForm')) {
        setButtonLoading('loginBtn', false);
        document.getElementById('loginBtnText').textContent = 'Login';
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
    @endif
});
</script>
@endsection
