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

            <form class="mt-8 space-y-6" action="{{ route('register') }}" method="POST">
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

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="rounded-md bg-red-500/10 p-4">
                        <div class="flex">
                            <div class="text-sm text-red-400">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <div>
                    <button type="submit" class="cyber-button">
                        Daftar
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
@endsection
