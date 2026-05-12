@extends('superAdmin.layout')

@section('title', 'Tambah Jenis Sampah')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body bg-primary text-white" style="background-color: #1E3F8C !important;">
                    <h5 class="mb-0">Tambah Jenis Sampah</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('superadmin.master.jenis.store') }}" method="POST" id="jenisForm">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="kategori_jenis" class="form-label">Kategori Jenis <span class="text-danger">*</span></label>
                        <select class="form-select @error('kategori_jenis') is-invalid @enderror" id="kategori_jenis" name="kategori_jenis" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="organik" {{ old('kategori_jenis') == 'organik' ? 'selected' : '' }}>Organik</option>
                            <option value="anorganik" {{ old('kategori_jenis') == 'anorganik' ? 'selected' : '' }}>Anorganik</option>
                            <option value="residu" {{ old('kategori_jenis') == 'residu' ? 'selected' : '' }}>Residu</option>
                        </select>
                        @error('kategori_jenis')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="nama_jenis" class="form-label">Nama Jenis Sampah <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_jenis') is-invalid @enderror" id="nama_jenis" name="nama_jenis" value="{{ old('nama_jenis') }}" required>
                        @error('nama_jenis')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex">
                    <a href="{{ route('superadmin.master.jenis-sampah') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
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
    setupFormConfirmation('jenisForm', 'Apakah Anda yakin ingin menyimpan data jenis sampah ini?');
</script>
@endpush

@push('styles')
<style>
    .bg-primary {
        background-color: #1E3F8C !important;
    }

    .btn-primary {
        background-color: #1E3F8C !important;
        border-color: #1E3F8C !important;
        color: #fff !important;
    }

    .btn-primary:hover {
        background-color: #152f5e !important;
        border-color: #152f5e !important;
        color: #fff !important;
    }

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #5a6268;
    }

    .form-label {
        font-weight: 600;
        margin-bottom: 8px;
    }

    .form-select, .form-control {
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .form-select:focus, .form-control:focus {
        border-color: #1E3F8C;
        box-shadow: 0 0 0 0.2rem rgba(30, 63, 140, 0.25);
    }
</style>
@endpush
