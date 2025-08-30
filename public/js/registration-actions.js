document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.action-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const action = this.dataset.action;
            const url = this.dataset.url;
            const regId = this.dataset.registrationId;
            const isApprove = action === 'approve';
            
            Swal.fire({
                title: isApprove ? 'Yakin ingin menerima pendaftaran ini?' : 'Yakin ingin menolak pendaftaran ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: isApprove ? '#10B981' : '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: isApprove ? 'Ya, Terima' : 'Ya, Tolak',
                cancelButtonText: 'Batal',
                background: '#1F2937',
                color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById(`action-form-${regId}`);
                    if (!form) {
                        console.error(`Form with id action-form-${regId} not found`);
                        return;
                    }

                    const originalText = this.innerHTML;
                    
                    // Disable button and show loading state
                    this.disabled = true;
                    this.innerHTML = `<span class="inline-block animate-spin mr-2">⚡</span> ${isApprove ? 'Menerima...' : 'Menolak...'}`;
                    
                    form.action = url;
                    form.submit();
                    
                    // Restore button after 2 seconds (for UX)
                    setTimeout(() => {
                        this.disabled = false;
                        this.innerHTML = originalText;
                    }, 2000);
                }
            });
        });
    });
});
