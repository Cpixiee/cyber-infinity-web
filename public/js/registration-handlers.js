// Registration handling
document.addEventListener('DOMContentLoaded', () => {
    setupRegistrationForms();
    setupRegistrationDelete();
    setupRegistrationModal();
});

function setupRegistrationForms() {
    const forms = document.querySelectorAll('form[data-registration-form]');
    forms.forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            const url = form.action;
            
            try {
                const response = await fetch(url, {
                    method: form.method,
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
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
                    }).then(() => {
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        } else {
                            closeModal(document.querySelector('.modal'));
                            if (data.reload) {
                                window.location.reload();
                            }
                        }
                    });
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

function setupRegistrationDelete() {
    const deleteButtons = document.querySelectorAll('[data-delete-registration]');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', async () => {
            const registrationId = button.dataset.deleteRegistration;
            
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
                    const response = await fetch(`/registrations/${registrationId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();
                        Swal.fire({
                            title: 'Deleted!',
                            text: data.message || 'Registration has been deleted.',
                            icon: 'success',
                            background: '#1F2937',
                            color: '#fff',
                            iconColor: '#10B981'
                        }).then(() => {
                            const row = button.closest('tr');
                            if (row) {
                                row.remove();
                            } else {
                                window.location.reload();
                            }
                        });
                    } else {
                        throw new Error('Failed to delete registration');
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

function setupRegistrationModal() {
    const registerButtons = document.querySelectorAll('[data-register-workshop]');
    const modal = document.querySelector('#registration-modal');
    
    if (!modal) return;

    registerButtons.forEach(button => {
        button.addEventListener('click', () => {
            const workshopId = button.dataset.registerWorkshop;
            const workshopTitle = button.dataset.workshopTitle;
            
            // Update modal content
            const form = modal.querySelector('form');
            form.querySelector('input[name="workshop_id"]').value = workshopId;
            modal.querySelector('#workshop-title').textContent = workshopTitle;
            
            // Show modal
            openModal(modal);
        });
    });
}
