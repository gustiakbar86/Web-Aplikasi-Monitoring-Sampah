@extends('admin.layout')

@section('title', 'Tambah Petugas')

@section('content')
<div class="content-area">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-body bg-primary text-white">
                    <h5 class="mb-0">Tambah Petugas</h5>
                </div>

                <div class="card-body">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.store-petugas') }}" method="POST" id="formTambahPetugas">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Instansi <span class="text-danger">*</span></label>
                            <select class="form-control" name="id_instansi" required>
                                <option value="">-- Pilih Instansi --</option>
                                @foreach($instansis as $instansi)
                                    <option value="{{ $instansi->id_instansi }}" {{ old('id_instansi') == $instansi->id_instansi ? 'selected' : '' }}>
                                        {{ $instansi->nama_instansi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="password" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="password_confirmation" required>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('admin.kelola-petugas') }}" class="btn btn-secondary" id="btnBack">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>

                            <button type="button" class="btn btn-primary" id="btnSubmit">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('formTambahPetugas');
    const btnSubmit = document.getElementById('btnSubmit');
    const btnBack = document.getElementById('btnBack');

    let formChanged = false;
    window.isSubmitting = false;

    // Deteksi perubahan form
    form.addEventListener('input', () => {
        formChanged = true;
    });

    // ✅ Fungsi reusable SweetAlert
    function showConfirm(title, text, confirmText, callback) {
        Swal.fire({
            title,
            text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: confirmText,
            cancelButtonText: 'Batal',
            confirmButtonColor: '#0dcaf0',
            cancelButtonColor: '#6c757d'
        }).then(result => {
            if (result.isConfirmed) callback();
        });
    }

    // ✅ Tombol SIMPAN
    btnSubmit.addEventListener('click', function () {
        showConfirm(
            'Simpan Data?',
            'Pastikan data sudah benar sebelum melanjutkan.',
            'Ya, Simpan',
            () => {
                window.isSubmitting = true;
                formChanged = false;
                form.submit();
            }
        );
    });

    // ✅ Tombol KEMBALI
    btnBack.addEventListener('click', function (e) {
        e.preventDefault();
        window.location.href = btnBack.href;
    });

    // ✅ Warning jika close tab / refresh
    window.onbeforeunload = function(e) {
        if (formChanged && !window.isSubmitting) {
            e.preventDefault();
            return '';
        }
    };

    // ✅ Sukses / flash message
    @if(session('success'))
    Swal.fire({
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        icon: 'success',
        confirmButtonColor: '#0dcaf0'
    });
    @endif

});
</script>
@endpush


@push('styles')
<style>
.bg-primary { background-color: #1E3F8C !important; }
.btn-primary { background-color: #0dcaf0; border-color: #0dcaf0; color: #fff; }
.btn-secondary { background-color: #6c757d; border-color: #6c757d; }
</style>
@endpush
