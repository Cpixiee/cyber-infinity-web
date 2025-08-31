/**
 * Unified Matrix Effect for Cyber Infinity
 * Handles both #matrix and #matrix-container elements
 */

class MatrixEffect {
    constructor(canvasId = 'matrix') {
        this.canvas = document.getElementById(canvasId);
        if (!this.canvas) {
            // Try alternative canvas ID
            this.canvas = document.querySelector('#matrix-container canvas') || 
                         document.querySelector('canvas[id*="matrix"]');
        }
        
        if (!this.canvas) {
            console.warn('Matrix canvas not found');
            return;
        }

        this.ctx = this.canvas.getContext('2d');
        this.chars = 'ｦｱｳｴｵｶｷｹｺｻｼｽｾｿﾀﾂﾃﾅﾆﾇﾈﾊﾋﾎﾏﾐﾑﾒﾓﾔﾕﾗﾘﾜABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#$%^&*()';
        this.charArray = this.chars.split('');
        this.fontSize = 14;
        this.drops = [];
        
        this.init();
    }

    init() {
        this.setCanvasSize();
        this.initDrops();
        this.startAnimation();
        
        // Handle window resize
        window.addEventListener('resize', () => this.handleResize());
    }

    setCanvasSize() {
        this.canvas.width = window.innerWidth;
        this.canvas.height = window.innerHeight;
        this.columns = Math.floor(this.canvas.width / this.fontSize);
    }

    initDrops() {
        this.drops = [];
        for (let i = 0; i < this.columns; i++) {
            this.drops[i] = Math.random() * -100; // Start at random positions above screen
        }
    }

    draw() {
        // Semi-transparent black background for fade effect
        this.ctx.fillStyle = 'rgba(0, 0, 0, 0.05)';
        this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);

        // Green text with glow effect
        this.ctx.fillStyle = '#0F0';
        this.ctx.font = this.fontSize + 'px monospace';
        this.ctx.shadowBlur = 5;
        this.ctx.shadowColor = '#0F0';

        // Draw characters
        for (let i = 0; i < this.drops.length; i++) {
            const char = this.charArray[Math.floor(Math.random() * this.charArray.length)];
            const x = i * this.fontSize;
            const y = this.drops[i] * this.fontSize;
            
            this.ctx.fillText(char, x, y);

            // Reset drop when it reaches bottom
            if (this.drops[i] * this.fontSize > this.canvas.height && Math.random() > 0.975) {
                this.drops[i] = 0;
            }

            this.drops[i]++;
        }

        // Reset shadow
        this.ctx.shadowBlur = 0;
    }

    startAnimation() {
        const animate = () => {
            this.draw();
            requestAnimationFrame(animate);
        };
        animate();
    }

    handleResize() {
        this.setCanvasSize();
        this.initDrops();
    }

    // Static method to initialize matrix effect
    static init(canvasId = 'matrix') {
        return new MatrixEffect(canvasId);
    }
}

// Auto-initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    // Remove any existing matrix canvases to prevent conflicts
    const existingCanvases = document.querySelectorAll('canvas[id*="matrix"]');
    existingCanvases.forEach(canvas => {
        if (canvas.id !== 'matrix') {
            canvas.remove();
        }
    });

    // Initialize the unified matrix effect
    window.matrixEffect = MatrixEffect.init();
});

// Export for global use
window.MatrixEffect = MatrixEffect;
