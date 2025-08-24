<!-- Modal for Workshop Registration -->
<div class="modal" id="workshop-modal" style="display: none;">
    <style>
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background-color: #2d2d2d;
            padding: 2rem;
            border-radius: 8px;
            width: 100%;
            max-width: 500px;
            position: relative;
            margin: 20px;
        }

        .modal-header {
            margin-bottom: 1.5rem;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
            margin: 0;
        }

        .close-button {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: none;
            border: none;
            color: #888;
            cursor: pointer;
            font-size: 1.5rem;
            padding: 0.5rem;
        }

        .close-button:hover {
            color: white;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: #d1d1d1;
        }

        .form-control {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #404040;
            background-color: #1a1a1a;
            color: white;
            border-radius: 4px;
        }

        .form-control:focus {
            outline: none;
            border-color: #3490dc;
        }

        .alert {
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }

        .alert-error {
            background-color: #742a2a;
            color: #fbd5d5;
            border: 1px solid #9b2c2c;
        }

        .alert-success {
            background-color: #22543d;
            color: #c6f6d5;
            border: 1px solid #276749;
        }

        .modal-actions {
            margin-top: 2rem;
            display: flex;
            gap: 1rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
            flex: 1;
        }

        .btn-primary {
            background-color: #3490dc;
            color: white;
        }

        .btn-primary:hover {
            background-color: #2779bd;
        }

        .btn-secondary {
            background-color: #718096;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #4a5568;
        }

        @media (max-width: 640px) {
            .modal-content {
                margin: 1rem;
            }

            .modal-actions {
                flex-direction: column;
            }
        }
    </style>

    <div class="modal-content">
        <button class="close-button" onclick="closeModal()">&times;</button>
        
        <div class="modal-header">
            <h3 class="modal-title">Daftar Workshop</h3>
        </div>

        @if($errors->any())
        <div class="alert alert-error">
            <ul style="list-style: disc inside;">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        <form wire:submit.prevent="register">
            <div class="form-group">
                <label class="form-label" for="notes">Catatan (Opsional)</label>
                <textarea class="form-control" id="notes" wire:model="notes" rows="3" 
                    placeholder="Tambahkan catatan atau permintaan khusus..."></textarea>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary">
                    Daftar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function closeModal() {
    document.getElementById('workshop-modal').style.display = 'none';
}

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeModal();
    }
});
</script>
