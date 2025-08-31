/**
 * ====================================================
 * MOBILE NAVIGATION JAVASCRIPT
 * Handles mobile menu toggle and responsive behavior
 * ====================================================
 */

// Initialize mobile navigation when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    initializeMobileNavigation();
});

/**
 * Initialize mobile navigation functionality
 */
function initializeMobileNavigation() {
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileCloseBtn = document.getElementById('mobile-close-btn');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('mobile-overlay');
    
    if (!mobileMenuBtn || !sidebar || !overlay) {
        console.log('Mobile navigation elements not found, skipping initialization');
        return;
    }
    
    // Mobile menu open
    mobileMenuBtn.addEventListener('click', function(e) {
        e.preventDefault();
        openMobileMenu();
    });
    
    // Mobile menu close
    if (mobileCloseBtn) {
        mobileCloseBtn.addEventListener('click', function(e) {
            e.preventDefault();
            closeMobileMenu();
        });
    }
    
    // Overlay click to close
    overlay.addEventListener('click', function() {
        closeMobileMenu();
    });
    
    // Escape key to close
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeMobileMenu();
        }
    });
    
    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 1024) {
            closeMobileMenu();
        }
    });
}

/**
 * Open mobile menu
 */
function openMobileMenu() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('mobile-overlay');
    
    if (window.innerWidth >= 1024) return; // Don't open on desktop
    
    if (sidebar && overlay) {
        sidebar.classList.add('mobile-open');
        overlay.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
}

/**
 * Close mobile menu
 */
function closeMobileMenu() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('mobile-overlay');
    
    if (sidebar && overlay) {
        sidebar.classList.remove('mobile-open');
        overlay.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
}

/**
 * Check if mobile menu is open
 */
function isMobileMenuOpen() {
    const sidebar = document.getElementById('sidebar');
    return sidebar && sidebar.classList.contains('mobile-open');
}
