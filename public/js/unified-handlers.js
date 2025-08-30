/**
 * Unified Event Handlers for Cyber Infinity
 * Consolidates registration, workshop, and other form handlers
 */

// Registration form handler
function handleRegistrationForm() {
    const forms = document.querySelectorAll('form[data-registration]');
    
    forms.forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            if (!validateFormFields(form.id)) {
                return false;
            }
            
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            
            try {
                setButtonLoading(submitBtn.id, true, 'Mendaftar...');
                
                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();
                
                if (response.ok) {
                    showSuccessToast('Pendaftaran Berhasil!', data.message);
                    form.reset();
                    
                    // Close modal if exists
                    const modal = form.closest('.modal');
                    if (modal) {
                        closeModal(modal);
                    }
                    
                    // Redirect if specified
                    if (data.redirect) {
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 2000);
                    }
                } else {
                    throw new Error(data.message || 'Registration failed');
                }
            } catch (error) {
                showErrorToast('Pendaftaran Gagal', error.message);
            } finally {
                setButtonLoading(submitBtn.id, false);
                submitBtn.textContent = originalText;
            }
        });
    });
}

// Workshop management handlers
function handleWorkshopActions() {
    // Delete workshop handler
    document.querySelectorAll('[data-delete-workshop]').forEach(button => {
        button.addEventListener('click', async () => {
            const workshopId = button.dataset.deleteWorkshop;
            const workshopTitle = button.dataset.workshopTitle || 'workshop ini';
            
            const result = await showConfirmDialog(
                'Hapus Workshop?',
                `Apakah Anda yakin ingin menghapus ${workshopTitle}? Tindakan ini tidak dapat dibatalkan.`,
                'Ya, Hapus',
                'Batal'
            );

            if (result.isConfirmed) {
                try {
                    const response = await fetch(`/workshops/${workshopId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (response.ok) {
                        showSuccessToast('Workshop Dihapus', 'Workshop berhasil dihapus.');
                        
                        // Remove the workshop card from DOM
                        const card = button.closest('.workshop-card, .card, tr');
                        if (card) {
                            card.style.opacity = '0';
                            card.style.transform = 'translateX(-100%)';
                            setTimeout(() => card.remove(), 300);
                        }
                    } else {
                        const data = await response.json();
                        throw new Error(data.message || 'Failed to delete workshop');
                    }
                } catch (error) {
                    showErrorToast('Error', error.message);
                }
            }
        });
    });

    // Edit workshop handler
    document.querySelectorAll('[data-edit-workshop]').forEach(button => {
        button.addEventListener('click', () => {
            const workshopId = button.dataset.editWorkshop;
            window.location.href = `/workshops/${workshopId}/edit`;
        });
    });
}

// Challenge submission handlers
function handleChallengeSubmissions() {
    const forms = document.querySelectorAll('form[data-challenge-submit]');
    
    forms.forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const flagInput = form.querySelector('input[name="flag"]');
            const submitBtn = form.querySelector('button[type="submit"]');
            
            if (!flagInput.value.trim()) {
                showErrorToast('Flag Kosong', 'Silakan masukkan flag terlebih dahulu.');
                flagInput.focus();
                return;
            }
            
            const originalBtnText = submitBtn.textContent;
            
            try {
                setButtonLoading(submitBtn.id, true, 'Memverifikasi...');
                
                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();
                
                if (response.ok && data.success) {
                    showSuccessToast('Flag Benar!', data.message);
                    flagInput.value = '';
                    
                    // Update UI if needed
                    if (data.points) {
                        updateUserPoints(data.points);
                    }
                } else {
                    showErrorToast('Flag Salah', data.message || 'Flag yang Anda masukkan salah.');
                    flagInput.select();
                }
            } catch (error) {
                showErrorToast('Error', 'Terjadi kesalahan saat memverifikasi flag.');
            } finally {
                setButtonLoading(submitBtn.id, false);
                submitBtn.textContent = originalBtnText;
            }
        });
    });
}

// Hint purchase handlers
function handleHintPurchases() {
    document.querySelectorAll('[data-purchase-hint]').forEach(button => {
        button.addEventListener('click', async () => {
            const hintId = button.dataset.purchaseHint;
            const cost = button.dataset.hintCost || '10';
            
            const result = await showConfirmDialog(
                'Beli Hint?',
                `Apakah Anda yakin ingin membeli hint ini dengan ${cost} poin?`,
                'Ya, Beli',
                'Batal'
            );

            if (result.isConfirmed) {
                try {
                    const response = await fetch(`/challenges/hints/${hintId}/purchase`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const data = await response.json();
                    
                    if (response.ok && data.success) {
                        showSuccessToast('Hint Dibeli!', data.message);
                        
                        // Show hint content
                        const hintContent = button.closest('.hint-item').querySelector('.hint-content');
                        if (hintContent) {
                            hintContent.textContent = data.hint_content || data.hint;
                            hintContent.classList.remove('hidden');
                        }
                        
                        // Update user points
                        if (data.user_points !== undefined) {
                            updateUserPoints(data.user_points, true);
                        }
                        
                        // Hide purchase button
                        button.style.display = 'none';
                        
                    } else {
                        showErrorToast('Pembelian Gagal', data.message || 'Gagal membeli hint.');
                    }
                } catch (error) {
                    showErrorToast('Error', 'Terjadi kesalahan saat membeli hint.');
                }
            }
        });
    });
}

// Update user points in UI
function updateUserPoints(newPoints, isDeduction = false) {
    const pointsElements = document.querySelectorAll('[data-user-points]');
    
    pointsElements.forEach(element => {
        const currentPoints = parseInt(element.textContent) || 0;
        const finalPoints = isDeduction ? newPoints : currentPoints + newPoints;
        
        // Animate the change
        element.style.transform = 'scale(1.2)';
        element.style.color = isDeduction ? '#ff0000' : '#00ff00';
        
        setTimeout(() => {
            element.textContent = finalPoints;
            element.style.transform = 'scale(1)';
            element.style.color = '#00ffff';
        }, 200);
    });
}

// Notification handlers
function handleNotifications() {
    // Mark all notifications as read
    document.querySelectorAll('[data-mark-all-read]').forEach(button => {
        button.addEventListener('click', async () => {
            try {
                const response = await fetch('/notifications/mark-all-read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (response.ok) {
                    // Update UI
                    document.querySelectorAll('.notification-item.unread').forEach(item => {
                        item.classList.remove('unread');
                    });
                    
                    const badge = document.querySelector('.notification-badge');
                    if (badge) {
                        badge.textContent = '0';
                        badge.classList.add('hidden');
                    }
                }
            } catch (error) {
                console.error('Failed to mark notifications as read:', error);
            }
        });
    });

    // Delete individual notification
    document.querySelectorAll('[data-delete-notification]').forEach(button => {
        button.addEventListener('click', async () => {
            const notificationId = button.dataset.deleteNotification;
            
            try {
                const response = await fetch(`/notifications/${notificationId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (response.ok) {
                    const item = button.closest('.notification-item');
                    if (item) {
                        item.style.opacity = '0';
                        setTimeout(() => item.remove(), 300);
                    }
                }
            } catch (error) {
                console.error('Failed to delete notification:', error);
            }
        });
    });
}

// Initialize all handlers when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    handleRegistrationForm();
    handleWorkshopActions();
    handleChallengeSubmissions();
    handleHintPurchases();
    handleNotifications();
    
    console.log('Unified handlers initialized');
});

// Export functions for global use
window.handleRegistrationForm = handleRegistrationForm;
window.handleWorkshopActions = handleWorkshopActions;
window.handleChallengeSubmissions = handleChallengeSubmissions;
window.handleHintPurchases = handleHintPurchases;
window.updateUserPoints = updateUserPoints;
