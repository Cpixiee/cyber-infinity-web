<!-- Registration Modal -->
<div x-data="{ open: false }" x-show="open" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <!-- Background overlay -->
    <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity"></div>

    <!-- Modal panel -->
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-gray-800 rounded-xl max-w-xl w-full shadow-2xl" @click.away="open = false">
            <!-- Modal header -->
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-700">
                <h3 class="text-xl font-medium text-white">Pendaftaran Workshop</h3>
                <button @click="open = false" class="text-gray-400 hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal body -->
            <form id="registrationForm" method="POST" action="{{ route('workshops.register', $workshop->id) }}" class="p-6 space-y-6">
                @csrf
                <input type="hidden" name="workshop_id" value="{{ $workshop->id }}">
                
                <!-- Workshop Info -->
                <div class="bg-gray-700/50 rounded-lg p-4 mb-6">
                    <h4 class="text-lg font-medium text-white mb-2">{{ $workshop->title }}</h4>
                    <div class="text-sm text-gray-300">{{ $workshop->description }}</div>
                    <div class="mt-3 flex items-center text-sm text-gray-400">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ $workshop->start_date }}
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="full_name" class="block text-sm font-medium text-gray-300">Nama Lengkap</label>
                        <input type="text" name="full_name" id="full_name" required 
                            class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="class" class="block text-sm font-medium text-gray-300">Kelas</label>
                        <input type="text" name="class" id="class" required 
                            class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="nis" class="block text-sm font-medium text-gray-300">NIS</label>
                        <input type="text" name="nis" id="nis" required 
                            class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300">Email</label>
                        <input type="email" name="email" id="email" required 
                            class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="agreement_1" id="agreement_1" required
                                    class="w-4 h-4 rounded border-gray-600 bg-gray-700 text-blue-500 focus:ring-blue-500 focus:ring-offset-gray-800">
                            </div>
                            <label for="agreement_1" class="ml-3 text-sm text-gray-300">
                                Saya siap mengikuti pelatihan dengan segenap hati
                            </label>
                        </div>

                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="agreement_2" id="agreement_2" required
                                    class="w-4 h-4 rounded border-gray-600 bg-gray-700 text-blue-500 focus:ring-blue-500 focus:ring-offset-gray-800">
                            </div>
                            <label for="agreement_2" class="ml-3 text-sm text-gray-300">
                                Saya mengikuti kegiatan ini dengan kesadaran sendiri
                            </label>
                        </div>

                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="agreement_3" id="agreement_3" required
                                    class="w-4 h-4 rounded border-gray-600 bg-gray-700 text-blue-500 focus:ring-blue-500 focus:ring-offset-gray-800">
                            </div>
                            <label for="agreement_3" class="ml-3 text-sm text-gray-300">
                                Saya siap mengikuti kegiatan ini dengan sungguh-sungguh
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" id="closeRegistrationModal"
                        class="px-4 py-2 bg-gray-700 text-gray-300 rounded hover:bg-gray-600 focus:outline-none transition duration-150">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none transition duration-150">
                        Daftar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('registrationModal');
    const openButtons = document.querySelectorAll('.openRegistrationModal');
    const closeButton = document.getElementById('closeRegistrationModal');
    const form = document.getElementById('registrationForm');

    openButtons.forEach(button => {
        button.addEventListener('click', () => {
            modal.classList.remove('hidden');
        });
    });

    closeButton.addEventListener('click', () => {
        modal.classList.add('hidden');
        form.reset();
    });

    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.add('hidden');
            form.reset();
        }
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Check if all checkboxes are checked
        const agreements = ['agreement_1', 'agreement_2', 'agreement_3'];
        const allChecked = agreements.every(id => document.getElementById(id).checked);
        
        if (!allChecked) {
            Swal.fire({
                title: 'Peringatan',
                text: 'Anda harus menyetujui semua pernyataan untuk melanjutkan.',
                icon: 'warning',
                background: '#1F2937',
                color: '#fff'
            });
            return;
        }

        // Submit form
        const formData = new FormData(form);
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Berhasil!',
                    text: data.message,
                    icon: 'success',
                    background: '#1F2937',
                    color: '#fff'
                }).then(() => {
                    modal.classList.add('hidden');
                    form.reset();
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                });
            } else {
                Swal.fire({
                    title: 'Gagal!',
                    text: data.message,
                    icon: 'error',
                    background: '#1F2937',
                    color: '#fff'
                });
            }
        })
        .catch(error => {
            Swal.fire({
                title: 'Error!',
                text: 'Terjadi kesalahan. Silakan coba lagi.',
                icon: 'error',
                background: '#1F2937',
                color: '#fff'
            });
        });
    });
});
</script>
