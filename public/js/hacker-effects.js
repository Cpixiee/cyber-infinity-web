// Typing effect
function typeText(element, text, speed = 50) {
    let i = 0;
    element.textContent = '';
    
    function type() {
        if (i < text.length) {
            element.textContent += text.charAt(i);
            i++;
            setTimeout(type, speed);
        }
    }
    
    type();
}

// Glitch effect
function glitchText(element) {
    const originalText = element.textContent;
    const glitchChars = '!@#$%^&*<>-_';
    
    function createGlitch() {
        let glitchedText = '';
        for(let i = 0; i < originalText.length; i++) {
            if(Math.random() < 0.1) { // 10% chance for each character
                glitchedText += glitchChars[Math.floor(Math.random() * glitchChars.length)];
            } else {
                glitchedText += originalText[i];
            }
        }
        element.textContent = glitchedText;
    }

    // Apply glitch occasionally
    setInterval(() => {
        if(Math.random() < 0.1) { // 10% chance every interval
            createGlitch();
            setTimeout(() => {
                element.textContent = originalText;
            }, 100);
        }
    }, 2000);
}

// Initialize effects when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    const titleElement = document.querySelector('.cyber-title');
    const textElement = document.querySelector('.cyber-text');
    
    if(titleElement) {
        const originalTitle = titleElement.textContent;
        typeText(titleElement, originalTitle);
        setTimeout(() => glitchText(titleElement), 1000);
    }
    
    if(textElement) {
        const originalText = textElement.textContent;
        setTimeout(() => {
            typeText(textElement, originalText, 30);
        }, 2000);
    }
});
