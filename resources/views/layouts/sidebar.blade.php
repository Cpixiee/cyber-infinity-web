<!-- Sidebar -->
<aside class="w-64 bg-white shadow-sm border-r border-gray-200 fixed inset-y-0 left-0 z-50">
    <div class="flex flex-col h-full">
        <!-- Logo -->
        <div class="flex items-center px-6 py-4 border-b border-gray-200">
            <div class="flex items-center">
                <img src="{{ asset('images/fih-logo.png') }}" alt="FIH Logo" class="w-8 h-8 rounded-lg">
                <h1 class="ml-3 text-xl font-bold text-gray-900">Cyber Infinity</h1>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-4 space-y-2">
            <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                <i class="fas fa-home w-5 h-5 mr-3"></i>
                Dashboard
            </a>
            
            <a href="{{ route('workshops.index') }}" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                <i class="fas fa-graduation-cap w-5 h-5 mr-3"></i>
                Workshop
            </a>
            
            <!-- Challenges Dropdown -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                    <div class="flex items-center">
                        <i class="fas fa-flag w-5 h-5 mr-3"></i>
                        <span>Challenges</span>
                    </div>
                    <i class="fas fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
                </button>
                
                <div x-show="open" x-transition class="mt-1 ml-6 space-y-1">
                    <a href="{{ route('challenges.index') }}" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                        <i class="fas fa-play w-4 h-4 mr-3"></i>
                        Lihat Challenges
                    </a>
                    @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.challenges.index') }}" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                        <i class="fas fa-cog w-4 h-4 mr-3"></i>
                        Kelola Challenges
                    </a>
                    @endif
                </div>
            </div>
            
            <!-- CTF Dropdown -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                    <div class="flex items-center">
                        <i class="fas fa-trophy w-5 h-5 mr-3 text-yellow-600"></i>
                        <span>CTF</span>
                    </div>
                    <i class="fas fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
                </button>
                
                <div x-show="open" x-transition class="mt-1 ml-6 space-y-1">
                    <a href="{{ route('ctf.index') }}" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                        <i class="fas fa-flag w-4 h-4 mr-3"></i>
                        CTF Events
                    </a>

                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.ctf.index') }}" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                        <i class="fas fa-cog w-4 h-4 mr-3"></i>
                        Manage CTF
                    </a>
                    @endif
                </div>
            </div>
            
            @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.registrations.index') }}" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                <i class="fas fa-user-check w-5 h-5 mr-3"></i>
                Registrasi Workshop
            </a>
            
            <a href="{{ route('admin.challenges.index') }}" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                <i class="fas fa-cog w-5 h-5 mr-3"></i>
                Kelola Challenges
            </a>
            
            <a href="{{ route('admin.ctf.index') }}" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                <i class="fas fa-trophy w-5 h-5 mr-3 text-yellow-600"></i>
                Kelola CTF
            </a>
            @endif
            
            <a href="{{ route('profile.edit') }}" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900">
                <i class="fas fa-user-cog w-5 h-5 mr-3"></i>
                Profile
            </a>
        </nav>

        <!-- User Profile & Logout -->
        <div class="border-t border-gray-200 p-4">
            <div class="flex items-center mb-3">
                @if(auth()->user()->avatar)
                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" 
                         class="w-8 h-8 rounded-full object-cover sidebar-avatar">
                @else
                    <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-gray-600 text-sm"></i>
                    </div>
                @endif
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                    @if(auth()->user()->username)
                        <p class="text-xs text-blue-600">{{ auth()->user()->username ? '@' . auth()->user()->username : '@' . strtolower(str_replace(' ', '', auth()->user()->name)) }}</p>
                    @else
                        <p class="text-xs text-gray-500">{{ ucfirst(auth()->user()->role) }}</p>
                    @endif
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                @csrf
                <button type="button" onclick="confirmLogout()" class="w-full flex items-center px-3 py-2 text-sm font-medium text-red-600 rounded-lg hover:bg-red-50">
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



            

            <a href="{{ route('profile.edit') }}" class="flex items-center px-3 py-2 text-sm font-medium text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white">

                <i class="fas fa-user-cog w-5 h-5 mr-3"></i>

                Profile

            </a>

        </nav>



        <!-- User Profile & Logout -->

        <div class="border-t border-gray-700 p-4">

            <div class="flex items-center mb-3">

                @if(auth()->user()->avatar)

                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" 

                         class="w-8 h-8 rounded-full object-cover sidebar-avatar">

                @else

                    <div class="w-8 h-8 bg-gradient-to-r from-cyber-accent to-cyber-secondary rounded-full flex items-center justify-center">

                        <span class="text-black text-xs font-bold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>

                    </div>

                @endif

                <div class="ml-3">

                    <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>

                    @if(auth()->user()->username)

                        <p class="text-xs text-cyber-accent">{{ auth()->user()->username ? '@' . auth()->user()->username : '@' . strtolower(str_replace(' ', '', auth()->user()->name)) }}</p>

                    @else

                        <p class="text-xs text-gray-400">{{ ucfirst(auth()->user()->role) }}</p>

                    @endif

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






