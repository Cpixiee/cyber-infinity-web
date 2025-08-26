// Core functionality
document.addEventListener('DOMContentLoaded', () => {
    initializeHackerEffects();
    setupModals();
});

// Hacker effects - now using unified matrix effect
function initializeHackerEffects() {
    // Matrix effect is now handled by matrix-unified.js
    // This function is kept for backward compatibility
    console.log('Hacker effects initialized - using unified matrix system');
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
