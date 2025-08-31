function confirmLogout() {
    Swal.fire({
        title: 'Yakin ingin keluar?',
        text: "Anda akan keluar dari sistem",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#EF4444',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Ya, Keluar',
        cancelButtonText: 'Batal',
        background: '#1F2937',
        color: '#fff'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('logout-form').submit();
        }
    });
}
