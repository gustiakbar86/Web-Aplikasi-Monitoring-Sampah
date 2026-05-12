{{-- Create Form --}}
<form action="{{ $createRoute }}" method="POST" class="create-form needs-validation" novalidate>
    @csrf
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    @yield('create-form-fields')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</form>

{{-- Edit Form --}}
<form action="{{ $updateRoute }}" method="POST" class="update-form">
    @csrf
    @method('PUT')
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @yield('edit-form-fields')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </div>
    </div>
</form>

{{-- Delete Form --}}
<form action="{{ $deleteRoute }}" method="POST" class="delete-form">
    @csrf
    @method('DELETE')
    <input type="hidden" name="id" id="delete_id">
</form>

@push('scripts')
<script>
    // Function to populate edit modal
    function populateEditModal(data) {
        // To be implemented in specific views
        @yield('edit-modal-script')
    }

    // Function to set delete ID
    function setDeleteId(id) {
        document.getElementById('delete_id').value = id;
    }
</script>
@endpush