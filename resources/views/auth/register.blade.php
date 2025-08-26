@extends('layouts.master')

@section('title', 'Register - Cyber Infinity')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')
<div class="auth-container flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <canvas id="matrix" class="matrix-bg"></canvas>
    <div class="max-w-md w-full space-y-8 relative">
        <!-- Background effects -->
        <div class="absolute inset-0 bg-gradient-to-r from-green-500/5 to-emerald-500/5 rounded-lg filter blur"></div>
        
        <!-- Content -->
        <div class="auth-box p-8 rounded-xl backdrop-blur-xl">
            <div class="text-center">
                <h2 class="text-3xl font-bold cyber-text glitch-text" data-text="Join Cyber Infinity">
                    Join Cyber Infinity
                </h2>
                <p class="mt-2 text-sm text-green-400 text-center">
                    >>> Initializing new user registration sequence...
                </p>
            </div>

            <form id="registerForm" class="mt-8 space-y-6" action="{{ route('register') }}" method="POST" onsubmit="return handleRegisterSubmit(event)">
                @csrf
                
                <div class="space-y-4">
                    <!-- Nama Lengkap -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300">Nama Lengkap</label>
                        <div class="mt-1">
                            <input id="name" name="name" type="text" required 
                                class="appearance-none relative block w-full px-3 py-2 border border-gray-700 placeholder-gray-500 text-gray-100 bg-gray-800/50 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                                placeholder="Masukkan nama lengkap">
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300">Email</label>
                        <div class="mt-1">
                            <input id="email" name="email" type="email" autocomplete="email" required 
                                class="appearance-none relative block w-full px-3 py-2 border border-gray-700 placeholder-gray-500 text-gray-100 bg-gray-800/50 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                                placeholder="nama@email.com">
                        </div>
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        <label for="birthdate" class="block text-sm font-medium text-gray-300">Tanggal Lahir</label>
                        <div class="mt-1 grid grid-cols-3 gap-3">
                            <select id="birth_day" name="birth_day" required 
                                class="appearance-none relative block w-full px-3 py-2 border border-gray-700 placeholder-gray-500 text-gray-100 bg-gray-800/50 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm">
                                <option value="">Tanggal</option>
                                @for ($i = 1; $i <= 31; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                            <select id="birth_month" name="birth_month" required 
                                class="appearance-none relative block w-full px-3 py-2 border border-gray-700 placeholder-gray-500 text-gray-100 bg-gray-800/50 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm">
                                <option value="">Bulan</option>
                                @foreach (['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $month)
                                    <option value="{{ $loop->iteration }}">{{ $month }}</option>
                                @endforeach
                            </select>
                            <select id="birth_year" name="birth_year" required 
                                class="appearance-none relative block w-full px-3 py-2 border border-gray-700 placeholder-gray-500 text-gray-100 bg-gray-800/50 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm">
                                <option value="">Tahun</option>
                                @for ($i = date('Y'); $i >= date('Y') - 100; $i--)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-300">Password</label>
                        <div class="mt-1">
                            <input id="password" name="password" type="password" required 
                                class="appearance-none relative block w-full px-3 py-2 border border-gray-700 placeholder-gray-500 text-gray-100 bg-gray-800/50 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                                placeholder="Minimal 8 karakter">
                        </div>
                     
                    </div>

                    <!-- Konfirmasi Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-300">Konfirmasi Password</label>
                        <div class="mt-1">
                            <input id="password_confirmation" name="password_confirmation" type="password" required 
                                class="appearance-none relative block w-full px-3 py-2 border border-gray-700 placeholder-gray-500 text-gray-100 bg-gray-800/50 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                                placeholder="Masukkan ulang password">
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="cyber-button" id="registerBtn">
                        <span id="registerBtnText">Daftar</span>
                        <i id="registerSpinner" class="fas fa-spinner fa-spin ml-2 hidden"></i>
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-400">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-medium text-blue-400 hover:text-blue-500">
                        Login di sini
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
// Handle register form submission
function handleRegisterSubmit(event) {
    event.preventDefault();
    
    // Show loading state
    const btn = document.getElementById('registerBtn');
    const btnText = document.getElementById('registerBtnText');
    const spinner = document.getElementById('registerSpinner');
    
    btn.disabled = true;
    btnText.textContent = 'Mendaftar...';
    spinner.classList.remove('hidden');
    
    // Validate form
    if (!validateRegisterForm()) {
        // Reset button state
        btn.disabled = false;
        btnText.textContent = 'Daftar';
        spinner.classList.add('hidden');
        return false;
    }
    
    // Submit form
    document.getElementById('registerForm').submit();
    return true;
}

// Email validation helper
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Show validation errors using SweetAlert2
function showValidationErrors(errors) {
    let errorList = errors.map(error => `• ${error}`).join('<br>');
    
    Swal.fire({
        title: 'Validation Error',
        html: `<div class="text-left">${errorList}</div>`,
        icon: 'error',
        confirmButtonText: 'Perbaiki',
        background: '#1f2937',
        color: '#fff',
        customClass: {
            popup: 'cyber-validation-error'
        }
    });
}

// Custom validation for register form
function validateRegisterForm() {
    const form = document.getElementById('registerForm');
    const errors = [];
    
    // Get form fields
    const name = form.querySelector('input[name="name"]');
    const email = form.querySelector('input[name="email"]');
    const birthDay = form.querySelector('select[name="birth_day"]');
    const birthMonth = form.querySelector('select[name="birth_month"]');
    const birthYear = form.querySelector('select[name="birth_year"]');
    const password = form.querySelector('input[name="password"]');
    const passwordConfirm = form.querySelector('input[name="password_confirmation"]');
    
    // Reset field errors
    [name, email, password, passwordConfirm].forEach(field => {
        field.classList.remove('border-red-500');
    });
    
    // Validate name
    if (!name.value.trim()) {
        errors.push('Nama lengkap harus diisi');
        name.classList.add('border-red-500');
    }
    
    // Validate email
    if (!email.value.trim()) {
        errors.push('Email harus diisi');
        email.classList.add('border-red-500');
    } else if (!isValidEmail(email.value)) {
        errors.push('Format email tidak valid');
        email.classList.add('border-red-500');
    }
    
    // Validate birthdate
    if (!birthDay.value || !birthMonth.value || !birthYear.value) {
        errors.push('Tanggal lahir harus diisi lengkap');
        [birthDay, birthMonth, birthYear].forEach(field => {
            if (!field.value) field.classList.add('border-red-500');
        });
    }
    
    // Validate password
    if (!password.value) {
        errors.push('Password harus diisi');
        password.classList.add('border-red-500');
    } else if (password.value.length < 8) {
        errors.push('Password minimal 8 karakter');
        password.classList.add('border-red-500');
    }
    
    // Validate password confirmation
    if (!passwordConfirm.value) {
        errors.push('Konfirmasi password harus diisi');
        passwordConfirm.classList.add('border-red-500');
    } else if (password.value !== passwordConfirm.value) {
        errors.push('Konfirmasi password tidak cocok');
        passwordConfirm.classList.add('border-red-500');
    }
    
    if (errors.length > 0) {
        showValidationErrors(errors);
        return false;
    }
    
    return true;
}

// Show flash messages
function showFlashMessages() {
    @if(session('success'))
        Swal.fire({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            icon: 'success',
            title: "{{ session('success') }}",
            background: '#1f2937',
            color: '#fff',
            iconColor: '#10b981'
        });
    @endif

    @if(session('error'))
        Swal.fire({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 6000,
            timerProgressBar: true,
            icon: 'error',
            title: "{{ session('error') }}",
            background: '#1f2937',
            color: '#fff',
            iconColor: '#ef4444'
        });
    @endif

    @if($errors->any())
        showValidationErrors(@json($errors->all()));
    @endif
}

// Real-time field validation
document.addEventListener('DOMContentLoaded', function() {
    // Show flash messages
    showFlashMessages();
    
    const emailField = document.querySelector('input[name="email"]');
    const passwordField = document.querySelector('input[name="password"]');
    const passwordConfirmField = document.querySelector('input[name="password_confirmation"]');
    
    // Email validation
    emailField.addEventListener('blur', function() {
        if (this.value && !isValidEmail(this.value)) {
            this.classList.add('border-red-500');
            Swal.fire({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                icon: 'warning',
                title: 'Format email tidak valid',
                background: '#1f2937',
                color: '#fff',
                iconColor: '#f59e0b'
            });
        } else {
            this.classList.remove('border-red-500');
        }
    });
    
    // Password strength indicator
    passwordField.addEventListener('input', function() {
        const strength = getPasswordStrength(this.value);
        if (this.value.length > 0 && this.value.length < 8) {
            this.classList.add('border-red-500');
        } else {
            this.classList.remove('border-red-500');
        }
        
        // Show strength indicator
        if (this.value.length > 0) {
            showPasswordStrength(strength);
        }
    });
    
    // Password confirmation validation
    passwordConfirmField.addEventListener('input', function() {
        if (this.value && passwordField.value !== this.value) {
            this.classList.add('border-red-500');
        } else {
            this.classList.remove('border-red-500');
        }
    });
    
    // Clear errors on focus
    document.querySelectorAll('input, select').forEach(field => {
        field.addEventListener('focus', function() {
            this.classList.remove('border-red-500');
        });
    });
});

function getPasswordStrength(password) {
    let score = 0;
    if (password.length >= 8) score++;
    if (/[a-z]/.test(password)) score++;
    if (/[A-Z]/.test(password)) score++;
    if (/[0-9]/.test(password)) score++;
    if (/[^A-Za-z0-9]/.test(password)) score++;
    return score;
}

function showPasswordStrength(strength) {
    const strengths = ['Sangat Lemah', 'Lemah', 'Sedang', 'Kuat', 'Sangat Kuat'];
    const colors = ['#ef4444', '#f59e0b', '#eab308', '#22c55e', '#10b981'];
    
    // You can add a strength indicator UI here if needed
}
</script>
@endsection
