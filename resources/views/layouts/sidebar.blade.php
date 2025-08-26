<!-- Sidebar -->
<aside class="w-64 bg-gray-800 shadow-sm border-r border-gray-700 fixed inset-y-0 left-0 z-50">
    <div class="flex flex-col h-full">
        <!-- Logo -->
        <div class="flex items-center px-6 py-4 border-b border-gray-700">
            <div class="flex items-center">
                <img src="{{ asset('images/fih-logo.png') }}" alt="FIH Logo" class="w-8 h-8 rounded-lg">
                <h1 class="ml-3 text-xl font-bold text-white">Cyber Infinity</h1>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-4 space-y-2">
            <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 text-sm font-medium text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white">
                <i class="fas fa-home w-5 h-5 mr-3"></i>
                Dashboard
            </a>
            
            <a href="{{ route('workshops.index') }}" class="flex items-center px-3 py-2 text-sm font-medium text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white">
                <i class="fas fa-graduation-cap w-5 h-5 mr-3"></i>
                Workshop
            </a>
            
            <a href="{{ route('challenges.index') }}" class="flex items-center px-3 py-2 text-sm font-medium text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white">
                <i class="fas fa-flag w-5 h-5 mr-3"></i>
                Challenges
            </a>
            
            @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.registrations.index') }}" class="flex items-center px-3 py-2 text-sm font-medium text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white">
                <i class="fas fa-user-check w-5 h-5 mr-3"></i>
                Registrasi Workshop
            </a>
            
            <a href="{{ route('admin.challenges.index') }}" class="flex items-center px-3 py-2 text-sm font-medium text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white">
                <i class="fas fa-cog w-5 h-5 mr-3"></i>
                Kelola Challenges
            </a>
            @endif
            
            <a href="{{ route('profile.edit') }}" class="flex items-center px-3 py-2 text-sm font-medium text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white">
                <i class="fas fa-user-cog w-5 h-5 mr-3"></i>
                Profile
            </a>
        </nav>

        <!-- User Profile & Logout -->
        <div class="border-t border-gray-700 p-4">
            <div class="flex items-center mb-3">
                <div class="w-8 h-8 bg-gray-600 rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-gray-300 text-sm"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-400">{{ ucfirst(auth()->user()->role) }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                @csrf
                <button type="button" onclick="confirmLogout()" class="w-full flex items-center px-3 py-2 text-sm font-medium text-red-400 rounded-lg hover:bg-red-900 hover:text-red-300">
                    <i class="fas fa-sign-out-alt w-5 h-5 mr-3"></i>
                    Logout
                </button>
            </form>
        </div>
    </div>
</aside>

<script>
function confirmLogout() {
    if (confirm('Apakah Anda yakin ingin keluar?')) {
        document.getElementById('logout-form').submit();
    }
}
</script>


