import './bootstrap';
import '../css/app.css';



// Counter Animation
function animateCounters() {
    const counters = document.querySelectorAll('[data-count]');
    
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-count'));
        const duration = 2000; // 2 seconds
        const increment = target / (duration / 16); // 60 FPS
        let current = 0;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            counter.textContent = Math.floor(current);
        }, 16);
    });
}

// Sidebar Toggle
function toggleSidebar() {
    const sidebar = document.querySelector('.cyber-sidebar, [data-sidebar]');
    if (sidebar) {
        sidebar.classList.toggle('active');
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Remove any existing matrix canvas if present
    const existingCanvas = document.getElementById('matrix-canvas');
    if (existingCanvas) {
        existingCanvas.remove();
    }
    
    // Animate counters
    animateCounters();
});

// Export functions for global use
window.toggleSidebar = toggleSidebar;
window.animateCounters = animateCounters;
