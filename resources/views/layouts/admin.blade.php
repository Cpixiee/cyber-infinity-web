<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cyber Infinity - Dashboard</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/fih-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/fih-logo.png') }}">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/hacker-effects.css') }}">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2000,
                background: '#1F2937',
                color: '#fff',
                iconColor: '#10B981'
            });
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "{{ session('error') }}",
                showConfirmButton: true,
                background: '#1F2937',
                color: '#fff',
                iconColor: '#EF4444'
            });
        });
    </script>
    @endif
</head>
<body class="bg-gray-900">
    @yield('content')

    <!-- Scripts -->
    @yield('scripts')
    @stack('scripts')
</body>
</html>
    @yield('content')
    
    <!-- Custom JS -->
    <script src="{{ url('js/hacker-effects.js') }}"></script>
    <script src="{{ url('js/workshop-modal.js') }}"></script>
    <script src="{{ url('js/registration-form.js') }}"></script>

    <!-- Modal Components -->
    @include('components.registration-modal')
</body>
</html>
