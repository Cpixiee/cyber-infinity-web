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

        <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
            @csrf

            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="email" class="sr-only">Email</label>
                    <input id="email" name="email" type="email" autocomplete="email" required
                        class="w-full px-3 py-2 border placeholder-gray-500 text-gray-100 bg-gray-800/50 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 sm:text-sm"
                        placeholder="Email">
                </div>
                <div>
                    <label for="password" class="sr-only">Password</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                        class="w-full px-3 py-2 border placeholder-gray-500 text-gray-100 bg-gray-800/50 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 sm:text-sm"
                        placeholder="Password">
                </div>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="rounded-md bg-red-500/10 p-4 mt-4">
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
                    Login
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
@endsection
