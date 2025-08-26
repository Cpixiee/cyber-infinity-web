document.addEventListener('DOMContentLoaded', () => {
    const registrationModal = document.getElementById('registrationModal');
    const registrationForm = document.getElementById('registrationForm');
    const submitButton = registrationForm?.querySelector('button[type="submit"]');
    const closeButtons = document.querySelectorAll('[data-modal-close]');
    let isSubmitting = false;

    // Handle opening registration modal
    document.querySelectorAll('.openRegistrationModal').forEach(button => {
        button.addEventListener('click', () => {
            const workshopId = button.getAttribute('data-workshop-id');
            if (workshopId) {
                registrationForm.setAttribute('action', `/workshops/${workshopId}/register`);
                registrationModal.classList.remove('hidden');
            }
        });
    });

    // Handle closing modal
    closeButtons.forEach(button => {
        button.addEventListener('click', () => {
            registrationModal.classList.add('hidden');
            registrationForm.reset();
            enableSubmitButton();
        });
    });

    // Handle form submission
    registrationForm?.addEventListener('submit', async (e) => {
        e.preventDefault();

        if (isSubmitting) {
            return;
        }

        disableSubmitButton('Memproses...');
        isSubmitting = true;

        const formData = new FormData(registrationForm);
        const action = registrationForm.getAttribute('action');

        try {
            const response = await fetch(action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                }
            });

            const data = await response.json();

            if (response.ok) {
                // Show success message
                showAlert('success', 'Pendaftaran berhasil! Silakan tunggu konfirmasi dari admin.');
                registrationModal.classList.add('hidden');
                registrationForm.reset();

                // Reload the page after a short delay
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                // Show error message
                if (data.errors) {
                    // Handle validation errors
                    const errorMessages = Object.values(data.errors).flat();
                    showAlert('error', errorMessages.join('<br>'));
                } else {
                    // Handle other errors
                    showAlert('error', data.message || 'Terjadi kesalahan. Silakan coba lagi.');
                }
                enableSubmitButton();
            }
        } catch (error) {
            showAlert('error', 'Terjadi kesalahan jaringan. Silakan coba lagi.');
            enableSubmitButton();
        } finally {
            isSubmitting = false;
        }
    });

    function disableSubmitButton(text = 'Memproses...') {
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.innerHTML = text;
        }
    }

    function enableSubmitButton(text = 'Daftar Workshop') {
        if (submitButton) {
            submitButton.disabled = false;
            submitButton.innerHTML = text;
        }
    }

    function showAlert(type, message) {
        const alertElement = document.createElement('div');
        alertElement.className = `fixed top-4 right-4 p-4 rounded-lg ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        } text-white max-w-md z-50`;
        alertElement.innerHTML = message;
        
        document.body.appendChild(alertElement);
        
        setTimeout(() => {
            alertElement.remove();
        }, 5000);
    }
});
