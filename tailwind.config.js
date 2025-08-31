/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./app/**/*.php",
    "./resources/**/*.html",
  ],
  theme: {
    extend: {
      colors: {
        'cyber-green': '#00ff00',
        'cyber-cyan': '#00ffff',
        'cyber-dark': '#000000',
        'cyber-dark-green': '#001100',
        'cyber-dark-cyan': '#001111',
      },
      fontFamily: {
        'cyber': ['Share Tech Mono', 'monospace'],
        'orbitron': ['Orbitron', 'monospace'],
      },
      animation: {
        'glow-pulse': 'glow-pulse 2s infinite',
        'scan-line': 'scan-line 3s infinite',
        'matrix-move': 'matrix-move 20s linear infinite',
      },
      keyframes: {
        'glow-pulse': {
          '0%, 100%': { 
            textShadow: '0 0 20px rgba(0, 255, 0, 0.8)'
          },
          '50%': { 
            textShadow: '0 0 30px rgba(0, 255, 0, 1), 0 0 40px rgba(0, 255, 0, 0.5)'
          },
        },
        'scan-line': {
          '0%, 100%': { 
            opacity: '0', 
            transform: 'translateX(-100%)'
          },
          '50%': { 
            opacity: '1', 
            transform: 'translateX(0)'
          },
        },
        'matrix-move': {
          '0%': { 
            transform: 'translateY(0) translateX(0)'
          },
          '100%': { 
            transform: 'translateY(25px) translateX(25px)'
          },
        },
      },
      backdropBlur: {
        'cyber': '15px',
      },
      boxShadow: {
        'cyber': '0 0 20px rgba(0, 255, 0, 0.3)',
        'cyber-cyan': '0 0 20px rgba(0, 255, 255, 0.3)',
        'cyber-lg': '0 20px 40px rgba(0, 255, 0, 0.2)',
      },
    },
  },
  plugins: [],
}
