<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tailwind Test - Cyber Infinity</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-cyber-dark text-white font-cyber min-h-screen">
    <div class="matrix-bg"></div>
    
    <div class="relative z-10 min-h-screen flex items-center justify-center p-8">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-6xl font-orbitron font-bold text-cyber-green glow-green mb-8 animate-glow-pulse">
                TAILWIND CSS
            </h1>
            <h2 class="text-4xl font-orbitron font-bold text-cyber-cyan glow-cyan mb-8">
                Successfully Installed!
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-12">
                <!-- Card 1 -->
                <div class="card-cyber">
                    <div class="text-cyber-green text-4xl mb-4">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Tailwind CSS v4</h3>
                    <p class="text-gray-300">Modern utility-first CSS framework</p>
                </div>
                
                <!-- Card 2 -->
                <div class="card-cyber">
                    <div class="text-cyber-cyan text-4xl mb-4">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Vite Integration</h3>
                    <p class="text-gray-300">Fast build tool with HMR</p>
                </div>
                
                <!-- Card 3 -->
                <div class="card-cyber">
                    <div class="text-cyber-green text-4xl mb-4">
                        <i class="fas fa-code"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Custom Classes</h3>
                    <p class="text-gray-300">Cyber-themed utility classes</p>
                </div>
            </div>
            
            <div class="mt-12 space-x-4">
                <button class="btn-cyber">
                    <i class="fas fa-home mr-2"></i>
                    Back to Dashboard
                </button>
                <button class="px-6 py-3 bg-transparent border border-cyber-cyan text-cyber-cyan rounded-lg hover:bg-cyber-cyan hover:text-black transition-all duration-300">
                    <i class="fas fa-book mr-2"></i>
                    Documentation
                </button>
            </div>
            
            <!-- Tailwind Examples -->
            <div class="mt-16 grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-slate-800/50 border border-gray-600 rounded-xl p-6 backdrop-blur-sm">
                    <h3 class="text-2xl font-bold text-cyber-green mb-4">Tailwind Examples</h3>
                    <div class="space-y-3 text-left">
                        <div class="flex items-center space-x-3">
                            <div class="w-4 h-4 bg-red-500 rounded-full"></div>
                            <span class="text-red-400">bg-red-500</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-4 h-4 bg-blue-500 rounded-full"></div>
                            <span class="text-blue-400">bg-blue-500</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-4 h-4 bg-green-500 rounded-full"></div>
                            <span class="text-green-400">bg-green-500</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-4 h-4 bg-purple-500 rounded-full"></div>
                            <span class="text-purple-400">bg-purple-500</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-slate-800/50 border border-gray-600 rounded-xl p-6 backdrop-blur-sm">
                    <h3 class="text-2xl font-bold text-cyber-cyan mb-4">Custom Classes</h3>
                    <div class="space-y-3 text-left">
                        <div class="flex items-center space-x-3">
                            <div class="w-4 h-4 bg-cyber-dark border border-cyber-green"></div>
                            <span class="text-cyber-green">border-cyber-green</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-4 h-4 bg-cyber-dark border border-cyber-cyan"></div>
                            <span class="text-cyber-cyan">border-cyber-cyan</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="text-cyber-green glow-green">✨</span>
                            <span class="text-cyber-green">glow-green effect</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="text-cyber-cyan glow-cyan">✨</span>
                            <span class="text-cyber-cyan">glow-cyan effect</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
