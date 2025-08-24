// Core functionality
document.addEventListener('DOMContentLoaded', () => {
    initializeHackerEffects();
    setupModals();
});

// Hacker effects
function initializeHackerEffects() {
    const container = document.getElementById('matrix-container');
    if (!container) return;

    // Matrix rain effect
    const canvas = document.createElement('canvas');
    canvas.style.width = '100%';
    canvas.style.height = '100%';
    container.appendChild(canvas);

    const ctx = canvas.getContext('2d');
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#$%^&*()';
    const fontSize = 10;
    const columns = Math.floor(window.innerWidth / fontSize);
    let drops = [];

    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;

    for (let i = 0; i < columns; i++) {
        drops[i] = 1;
    }

    function draw() {
        ctx.fillStyle = 'rgba(0, 0, 0, 0.05)';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        ctx.fillStyle = '#0F0';
        ctx.font = fontSize + 'px monospace';

        for (let i = 0; i < drops.length; i++) {
            const text = characters[Math.floor(Math.random() * characters.length)];
            ctx.fillText(text, i * fontSize, drops[i] * fontSize);
            if (drops[i] * fontSize > canvas.height && Math.random() > 0.975) {
                drops[i] = 0;
            }
            drops[i]++;
        }
    }

    setInterval(draw, 33);

    window.addEventListener('resize', () => {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
        drops = Array(Math.floor(window.innerWidth / fontSize)).fill(1);
    });
}

// Modal handling
function setupModals() {
    document.querySelectorAll('[data-modal-target]').forEach(button => {
        button.addEventListener('click', () => {
            const modal = document.querySelector(button.dataset.modalTarget);
            openModal(modal);
        });
    });

    document.querySelectorAll('[data-close-modal]').forEach(button => {
        button.addEventListener('click', () => {
            const modal = button.closest('.modal');
            closeModal(modal);
        });
    });

    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', e => {
            if (e.target === modal) closeModal(modal);
        });
    });
}

function openModal(modal) {
    if (!modal) return;
    modal.classList.remove('hidden');
    modal.classList.add('fade-in');
}

function closeModal(modal) {
    if (!modal) return;
    modal.classList.add('hidden');
    modal.classList.remove('fade-in');
}

// Form handling
function setupForms() {
    document.querySelectorAll('form[data-ajax]').forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(form);
            const url = form.action;
            
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();
                
                if (response.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message,
                        background: '#1F2937',
                        color: '#fff',
                        iconColor: '#10B981'
                    });

                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                } else {
                    throw new Error(data.message || 'Something went wrong');
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message,
                    background: '#1F2937',
                    color: '#fff',
                    iconColor: '#EF4444'
                });
            }
        });
    });
}

// Workshop handling
function setupWorkshops() {
    const deleteButtons = document.querySelectorAll('[data-delete-workshop]');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', async () => {
            const workshopId = button.dataset.deleteWorkshop;
            
            const result = await Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Yes, delete it!',
                background: '#1F2937',
                color: '#fff'
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch(`/workshops/${workshopId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    if (response.ok) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Workshop has been deleted.',
                            icon: 'success',
                            background: '#1F2937',
                            color: '#fff',
                            iconColor: '#10B981'
                        });
                        
                        // Remove the workshop card from the DOM
                        const card = button.closest('.workshop-card');
                        if (card) {
                            card.remove();
                        }
                    } else {
                        throw new Error('Failed to delete workshop');
                    }
                } catch (error) {
                    Swal.fire({
                        title: 'Error!',
                        text: error.message,
                        icon: 'error',
                        background: '#1F2937',
                        color: '#fff',
                        iconColor: '#EF4444'
                    });
                }
            }
        });
    });
}
