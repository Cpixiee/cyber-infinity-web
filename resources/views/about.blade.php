@extends('layouts.master')

@section('title', 'About Us - Cyber Infinity')

@section('content')
<div class="min-h-screen bg-cyber-bg py-12 relative overflow-hidden">
    <!-- Matrix Background -->
    <canvas id="matrix" class="fixed inset-0 w-full h-full opacity-20 pointer-events-none"></canvas>
    
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <!-- Hero Section -->
        <div class="text-center mb-16">
            <div class="inline-block px-4 py-2 bg-cyber-accent bg-opacity-20 border border-cyber-accent rounded-lg mb-6">
                <span class="text-cyber-accent font-mono text-sm uppercase tracking-wider">>>> About Us</span>
            </div>
            <h1 class="text-4xl md:text-6xl font-bold text-cyber-text mb-6 glow-animation">
                CYBER INFINITY
            </h1>
            <div class="w-24 h-1 bg-cyber-accent mx-auto mb-6 glow-animation"></div>
            <p class="text-xl md:text-2xl text-cyber-accent max-w-3xl mx-auto font-mono">
                >> Your Ultimate Cybersecurity Training Platform
            </p>
        </div>

        <!-- Main Content Grid -->
        <div class="grid md:grid-cols-2 gap-8 lg:gap-12">
            <!-- Mission Card -->
            <div class="cyber-card">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-cyber-accent bg-opacity-20 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-bullseye text-cyber-accent text-xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-cyber-accent uppercase tracking-wider">Mission</h2>
                </div>
                <p class="text-cyber-text leading-relaxed font-mono">
                    Membangun generasi cybersecurity expert melalui platform pembelajaran interaktif yang menggabungkan 
                    <span class="text-cyber-accent">teori mendalam</span> dengan 
                    <span class="text-cyber-accent">praktik hands-on</span> dalam lingkungan yang aman dan terkontrol.
                </p>
            </div>

            <!-- Vision Card -->
            <div class="cyber-card">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-cyber-secondary bg-opacity-20 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-eye text-cyber-secondary text-xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-cyber-secondary uppercase tracking-wider">Vision</h2>
                </div>
                <p class="text-cyber-text leading-relaxed font-mono">
                    Menjadi platform pembelajaran cybersecurity terdepan di Indonesia yang menghasilkan 
                    <span class="text-cyber-secondary">profesional berkualitas tinggi</span> 
                    dan siap menghadapi tantangan keamanan siber masa depan.
                </p>
            </div>
        </div>

        <!-- Features Section -->
        <div class="mt-16">
            <h2 class="text-3xl font-bold text-cyber-accent text-center mb-12 uppercase tracking-wider">
                >> Platform Features
            </h2>
            
            <div class="grid md:grid-cols-3 gap-6">
                <!-- CTF Challenges -->
                <div class="cyber-card text-center">
                    <div class="w-16 h-16 bg-green-500 bg-opacity-20 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-flag text-green-400 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-green-400 mb-3 uppercase">CTF Challenges</h3>
                    <p class="text-cyber-text text-sm font-mono leading-relaxed">
                        Kompetisi Capture The Flag dengan berbagai kategori: Web Security, Cryptography, Forensics, OSINT, dan lainnya.
                    </p>
                </div>

                <!-- Workshops -->
                <div class="cyber-card text-center">
                    <div class="w-16 h-16 bg-blue-500 bg-opacity-20 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-chalkboard-teacher text-blue-400 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-blue-400 mb-3 uppercase">Workshops</h3>
                    <p class="text-cyber-text text-sm font-mono leading-relaxed">
                        Pelatihan intensif dengan mentor berpengalaman dalam berbagai aspek keamanan siber dan ethical hacking.
                    </p>
                </div>

                <!-- Learning Path -->
                <div class="cyber-card text-center">
                    <div class="w-16 h-16 bg-purple-500 bg-opacity-20 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-route text-purple-400 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-purple-400 mb-3 uppercase">Learning Path</h3>
                    <p class="text-cyber-text text-sm font-mono leading-relaxed">
                        Jalur pembelajaran terstruktur dari basic hingga advanced level dengan progress tracking yang detail.
                    </p>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="mt-16">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="cyber-card text-center">
                    <div class="text-3xl font-bold text-cyber-accent mb-2 counter" data-target="{{ \App\Models\User::count() }}">0</div>
                    <div class="text-sm text-cyber-text font-mono uppercase">Students</div>
                </div>
                <div class="cyber-card text-center">
                    <div class="text-3xl font-bold text-cyber-secondary mb-2 counter" data-target="{{ \App\Models\Challenge::count() }}">0</div>
                    <div class="text-sm text-cyber-text font-mono uppercase">Challenges</div>
                </div>
                <div class="cyber-card text-center">
                    <div class="text-3xl font-bold text-green-400 mb-2 counter" data-target="{{ \App\Models\Workshop::count() }}">0</div>
                    <div class="text-sm text-cyber-text font-mono uppercase">Workshops</div>
                </div>
                <div class="cyber-card text-center">
                    <div class="text-3xl font-bold text-yellow-400 mb-2 counter" data-target="{{ \App\Models\Ctf::count() }}">0</div>
                    <div class="text-sm text-cyber-text font-mono uppercase">CTF Events</div>
                </div>
            </div>
        </div>

        <!-- Contact Section -->
        <div class="mt-16 text-center">
            <div class="cyber-card max-w-2xl mx-auto">
                <h2 class="text-2xl font-bold text-cyber-accent mb-6 uppercase tracking-wider">
                    >> Join Our Community
                </h2>
                <p class="text-cyber-text font-mono mb-6 leading-relaxed">
                    Bergabunglah dengan ribuan cybersecurity enthusiast lainnya dan mulai perjalanan Anda 
                    menuju dunia keamanan siber yang menantang!
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}" class="cyber-button">
                        <i class="fas fa-user-plus mr-2"></i>
                        Daftar Sekarang
                    </a>
                    <a href="{{ route('login') }}" class="cyber-button btn-secondary">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.cyber-card {
    background: rgba(0, 0, 0, 0.8);
    border: 2px solid var(--cyber-accent);
    padding: 2rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    backdrop-filter: blur(10px);
}

.cyber-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0 20px rgba(0, 255, 255, 0.3);
    border-color: var(--cyber-secondary);
}

.cyber-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(45deg, transparent 48%, var(--cyber-accent) 50%, transparent 52%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.cyber-card:hover::before {
    opacity: 0.1;
}

.glow-animation {
    animation: glow 2s ease-in-out infinite alternate;
}

@keyframes glow {
    0% { text-shadow: 0 0 5px var(--cyber-accent); }
    100% { text-shadow: 0 0 20px var(--cyber-accent), 0 0 30px var(--cyber-accent); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate counters
    const counters = document.querySelectorAll('.counter');
    const animateCounter = (counter) => {
        const target = parseInt(counter.getAttribute('data-target'));
        const increment = target / 50; // Animation duration control
        let current = 0;
        
        const updateCounter = () => {
            if (current < target) {
                current += increment;
                counter.textContent = Math.ceil(current);
                requestAnimationFrame(updateCounter);
            } else {
                counter.textContent = target;
            }
        };
        
        updateCounter();
    };
    
    // Intersection Observer for triggering animation when visible
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });
    
    counters.forEach(counter => {
        observer.observe(counter);
    });
});
</script>
@endsection
