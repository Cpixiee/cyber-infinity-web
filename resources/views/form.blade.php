@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-900">
    <!-- Sidebar -->
    <aside class="fixed inset-y-0 left-0 bg-gray-800 w-64 border-r border-gray-700">
        <div class="flex items-center justify-between h-16 px-4 border-b border-gray-700">
            <div class="flex items-center space-x-2">
                <img class="h-8 w-auto" src="{{ asset('images/fih-logo-removebg-preview.png') }}" alt="Logo">
                <span class="text-xl font-bold bg-gradient-to-r from-blue-400 to-purple-400 text-transparent bg-clip-text">Form</span>
            </div>
        </div>
        
        <nav class="mt-5 px-2">
            <a href="{{ route('dashboard') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md text-gray-300 hover:bg-gray-700 hover:text-white">
                <svg class="mr-4 h-6 w-6 text-gray-400 group-hover:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
            </a>

            <a href="{{ route('form') }}" class="mt-1 group flex items-center px-2 py-2 text-base font-medium rounded-md text-white bg-gray-700">
                <svg class="mr-4 h-6 w-6 text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                Form Pendaftaran
            </a>

            <a href="#" class="mt-1 group flex items-center px-2 py-2 text-base font-medium rounded-md text-gray-300 hover:bg-gray-700 hover:text-white">
                <svg class="mr-4 h-6 w-6 text-gray-400 group-hover:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Profil
            </a>

            <button onclick="confirmLogout()" class="mt-1 w-full group flex items-center px-2 py-2 text-base font-medium rounded-md text-gray-300 hover:bg-gray-700 hover:text-white">
                <svg class="mr-4 h-6 w-6 text-gray-400 group-hover:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Keluar
            </button>
        </nav>
    </aside>

    <!-- Main content -->
    <div class="pl-64 flex-1">
        <header class="bg-gray-800 shadow-lg">
            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                <h2 class="text-xl font-bold text-white">
                    Form Pendaftaran Bootcamp
                </h2>
            </div>
        </header>

        <main class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Form Section -->
                    <div class="bg-gray-800 overflow-hidden shadow rounded-lg border border-gray-700">
                        <div class="p-6">
                            <h4 class="text-xl font-medium text-white mb-4">Form Pendaftaran</h4>
                            <form id="bootcampForm" class="space-y-4" action="{{ route('form.submit') }}" method="POST">
                                @csrf
                                <div>
                                    <label for="nama" class="block text-sm font-medium text-gray-300">Nama Lengkap</label>
                                    <input type="text" id="nama" name="nama" required class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-300">Email</label>
                                    <input type="email" id="email" name="email" required class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label for="notelp" class="block text-sm font-medium text-gray-300">No. Telepon</label>
                                    <input type="tel" id="notelp" name="notelp" required class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label for="program" class="block text-sm font-medium text-gray-300">Program Bootcamp</label>
                                    <select id="program" name="program" required class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Pilih Program</option>
                                        <option value="basic">Basic Cyber Security</option>
                                        <option value="advanced">Advanced Cyber Security</option>
                                        <option value="expert">Expert Cyber Security</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="alasan" class="block text-sm font-medium text-gray-300">Alasan Mengikuti Bootcamp</label>
                                    <textarea id="alasan" name="alasan" rows="4" required class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-blue-500 focus:ring-blue-500"></textarea>
                                </div>

                                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Daftar Sekarang
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Info Section -->
                    <div class="bg-gray-800 overflow-hidden shadow rounded-lg border border-gray-700">
                        <div class="p-6">
                            <h4 class="text-xl font-medium text-white mb-4">Bootcamp Cyber Security 2025</h4>
                            <div class="space-y-4 text-gray-300">
                                <div>
                                    <h5 class="text-lg font-medium text-blue-400 mb-2">Program yang Tersedia</h5>
                                    <div class="space-y-4">
                                        <!-- Basic Program -->
                                        <div class="p-4 rounded-lg bg-gray-700/50 border border-gray-600">
                                            <h6 class="text-lg font-medium text-blue-300">Basic Cyber Security</h6>
                                            <ul class="mt-2 space-y-1 text-sm">
                                                <li>• Fundamental Keamanan Cyber</li>
                                                <li>• Dasar Network Security</li>
                                                <li>• Pengenalan Ethical Hacking</li>
                                                <li>• Durasi: 1 Bulan</li>
                                                <li>• Perfect untuk pemula</li>
                                            </ul>
                                        </div>

                                        <!-- Advanced Program -->
                                        <div class="p-4 rounded-lg bg-gray-700/50 border border-gray-600">
                                            <h6 class="text-lg font-medium text-purple-300">Advanced Cyber Security</h6>
                                            <ul class="mt-2 space-y-1 text-sm">
                                                <li>• Advanced Network Security</li>
                                                <li>• Web Application Security</li>
                                                <li>• Penetration Testing</li>
                                                <li>• Durasi: 2 Bulan</li>
                                                <li>• Untuk yang sudah memahami dasar</li>
                                            </ul>
                                        </div>

                                        <!-- Expert Program -->
                                        <div class="p-4 rounded-lg bg-gray-700/50 border border-gray-600">
                                            <h6 class="text-lg font-medium text-red-300">Expert Cyber Security</h6>
                                            <ul class="mt-2 space-y-1 text-sm">
                                                <li>• Advanced Penetration Testing</li>
                                                <li>• Malware Analysis</li>
                                                <li>• Digital Forensics</li>
                                                <li>• Durasi: 3 Bulan</li>
                                                <li>• Untuk professional</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6">
                                    <h5 class="text-lg font-medium text-blue-400 mb-2">Fasilitas</h5>
                                    <ul class="space-y-2">
                                        <li class="flex items-center">
                                            <svg class="h-5 w-5 text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Sertifikat Resmi
                                        </li>
                                        <li class="flex items-center">
                                            <svg class="h-5 w-5 text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                            Modul Pembelajaran
                                        </li>
                                        <li class="flex items-center">
                                            <svg class="h-5 w-5 text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                            Rekaman Kelas
                                        </li>
                                        <li class="flex items-center">
                                            <svg class="h-5 w-5 text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                                            </svg>
                                            Konsultasi Personal
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
document.getElementById('bootcampForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    Swal.fire({
        title: 'Konfirmasi Pendaftaran',
        text: 'Apakah data yang Anda masukkan sudah benar?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Daftar!',
        cancelButtonText: 'Periksa Lagi',
        background: '#1F2937',
        color: '#fff'
    }).then((result) => {
        if (result.isConfirmed) {
            // Di sini bisa ditambahkan AJAX call ke backend
            Swal.fire({
                title: 'Berhasil!',
                text: 'Pendaftaran Anda telah berhasil dikirim. Tim kami akan menghubungi Anda segera.',
                icon: 'success',
                background: '#1F2937',
                color: '#fff'
            }).then(() => {
                this.submit();
            });
        }
    });
});
</script>
@endsection
