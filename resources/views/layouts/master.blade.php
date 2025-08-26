<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Cyber Infinity')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/fih-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/fih-logo.png') }}">
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
    
    <style>
        /* Reset CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        #matrix {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        body {
            font-family: 'Share Tech Mono', monospace;
            background-color: #000;
            color: #fff;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                linear-gradient(rgba(0, 255, 0, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 255, 0, 0.03) 1px, transparent 1px);
            background-size: 20px 20px;
            pointer-events: none;
            animation: gridMove 20s linear infinite;
        }

        @keyframes gridMove {
            0% { transform: translateY(0); }
            100% { transform: translateY(20px); }
        }

        /* Navigation */
        .navbar {
            background-color: #000;
            padding: 1rem 0;
            border-bottom: 1px solid #333;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .nav-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .site-logo {
            height: 40px;
            width: auto;
        }

        .site-title {
            color: #00ff00;
            text-decoration: none;
            font-size: 1.5rem;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .nav-menu {
            display: none;
            flex-direction: column;
            gap: 1rem;
            list-style: none;
            align-items: center;
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background-color: #000;
            padding: 1rem;
            border-top: 1px solid #333;
        }
        
        .nav-menu.active {
            display: flex;
        }
        
        /* Desktop navigation */
        @media (min-width: 768px) {
            .nav-menu {
                display: flex;
                flex-direction: row;
                gap: 2rem;
                position: static;
                background: none;
                padding: 0;
                border: none;
            }
            
            .mobile-menu-btn {
                display: none;
            }
        }

        .nav-link {
            color: #fff;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9rem;
        }

        .nav-link:hover {
            color: #00ff00;
            text-shadow: 0 0 8px rgba(0, 255, 0, 0.5);
        }
        
        .nav-link.active {
            color: #00ff00;
            text-shadow: 0 0 8px rgba(0, 255, 0, 0.5);
        }

        .register-btn {
            border: 2px solid #00ff00;
            color: #00ff00;
            padding: 0.5rem 1.5rem;
        }

        .register-btn:hover {
            background: #00ff00;
            color: #000;
            box-shadow: 0 0 15px rgba(0, 255, 0, 0.5);
        }

        /* Main Content */
        main {
            flex: 1;
        }

        /* Footer */
        footer {
            background-color: #000;
            color: #666;
            padding: 2rem 0;
            text-align: center;
            width: 100%;
        }

        /* Buttons */
        .btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            border: 2px solid #00ff00;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: transparent;
            color: #00ff00;
        }

        .btn-primary:hover {
            background: #00ff00;
            color: #000;
        }

        .wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            z-index: 1;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-content {
                flex-direction: column;
                gap: 1rem;
            }
            
            .nav-menu {
                flex-direction: column;
                width: 100%;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <canvas id="matrix"></canvas>
    <div class="wrapper">
        <!-- Navigation -->
        <nav class="navbar">
            <div class="container">
                <div class="nav-content">
                    <div class="brand">
                        <img src="{{ asset('images/fih-logo.png') }}" alt="FIH Logo" class="site-logo">
                        <a href="{{ url('/') }}" class="site-title">Cyber Infinity</a>
                    </div>
                    <button class="mobile-menu-btn md:hidden p-2 text-white hover:text-green-400" onclick="toggleMobileMenu()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <ul class="nav-menu" id="mobile-menu">
                        <li><a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">Home</a></li>
                        <li><a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">About</a></li>
                        <li><a href="{{ route('login') }}" class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}">Login</a></li>
                        <li><a href="{{ route('register') }}" class="nav-link register-btn">Register</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        <footer>
            <div class="container">
                <p>&copy; {{ date('Y') }} Cyber Infinity. All rights reserved.</p>
            </div>
        </footer>
    </div>

    <!-- Unified CSS -->
    <link rel="stylesheet" href="{{ asset('css/unified-cyber.css') }}">
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/cyber-alerts.js') }}"></script>
    <script src="{{ asset('js/matrix-unified.js') }}"></script>
    <script src="{{ asset('js/unified-handlers.js') }}"></script>
    <script src="{{ asset('js/core.js') }}"></script>
    <script src="{{ asset('js/hacker-effects.js') }}"></script>
    
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('active');
        }
    </script>
</body>
</html>
