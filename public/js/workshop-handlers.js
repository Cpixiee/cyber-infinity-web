// Workshop handling
document.addEventListener('DOMContentLoaded', () => {
    setupWorkshopForms();
    setupWorkshopDelete();
});

function setupWorkshopForms() {
    const forms = document.querySelectorAll('form[data-workshop-form]');
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

function setupWorkshopDelete() {
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
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();
                        Swal.fire({
                            title: 'Deleted!',
                            text: data.message || 'Workshop has been deleted.',
                            icon: 'success',
                            background: '#1F2937',
                            color: '#fff',
                            iconColor: '#10B981'
                        }).then(() => {
                            const card = button.closest('.workshop-card');
                            if (card) {
                                card.remove();
                            } else {
                                window.location.reload();
                            }
                        });
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
