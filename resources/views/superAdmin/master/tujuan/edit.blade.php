@extends('superAdmin.layout')

@section('title', 'Edit Tujuan Sampah')

@section('content')
<div class="content-area-form">
    
    <div class="card">
        <div class="card-body bg-primary text-white" style="background-color: #1E3F8C !important;">
            <h5 class="mb-0">Edit Tujuan Sampah</h5>
        </div>
        
        <div class="card-body">
            <form action="{{ route('superadmin.master.tujuan.update', $tujuanSampah->id_tujuan) }}" method="POST" id="tujuanForm">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="kategori_tujuan" class="form-label">Kategori Tujuan <span class="text-danger">*</span></label>
                        <select class="form-control @error('kategori_tujuan') is-invalid @enderror" id="kategori_tujuan" name="kategori_tujuan" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="bank_sampah" {{ old('kategori_tujuan', $tujuanSampah->kategori_tujuan) == 'bank_sampah' ? 'selected' : '' }}>Bank Sampah</option>
                            <option value="tpa" {{ old('kategori_tujuan', $tujuanSampah->kategori_tujuan) == 'tpa' ? 'selected' : '' }}>TPA</option>
                        </select>
                        @error('kategori_tujuan')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="nama_tujuan" class="form-label">Nama Tujuan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_tujuan') is-invalid @enderror" id="nama_tujuan" name="nama_tujuan" value="{{ old('nama_tujuan', $tujuanSampah->nama_tujuan) }}" required>
                        @error('nama_tujuan')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="alamat" class="form-label">Alamat</label>
                        <input type="text" class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" value="{{ old('alamat', $tujuanSampah->alamat) }}">
                        @error('alamat')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="1" {{ old('status', $tujuanSampah->status) == 1 ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('status', $tujuanSampah->status) == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                        @error('status')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                
                <div class="d-flex">
                    <a href="{{ route('superadmin.master.tujuan-sampah') }}" class="btn btn-secondary me-2">
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
@endsection

@push('scripts')
<script>
    setupFormConfirmation('tujuanForm', 'Apakah Anda yakin ingin menyimpan perubahan data tujuan sampah ini?');
</script>
@endpush

@push('styles')
<style>
    .bg-primary {
        background-color: #1E3F8C !important;
    }
    .btn-primary {
        background-color: #0dcaf0 !important;
        border-color: #0dcaf0 !important;
        color: #fff !important;
    }
    .btn-primary:hover {
        background-color: #0bb5d6 !important;
        border-color: #0bb5d6 !important;
        color: #fff !important;
    }
    .btn-secondary {
        background-color: #6c757d !important;
        border-color: #6c757d !important;
        color: #fff !important;
    }
</style>
@endpush
