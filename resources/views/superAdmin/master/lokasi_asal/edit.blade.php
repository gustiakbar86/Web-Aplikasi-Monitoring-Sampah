@extends('superAdmin.layout')

@section('title', 'Edit Lokasi Asal')

@section('content')
<div class="content-area-form">
    
    <div class="card">
        <div class="card-body bg-primary text-white" style="background-color: #1E3F8C !important;">
            <h5 class="mb-0">Edit Lokasi Asal</h5>
        </div>
        
        <div class="card-body">
            <form action="{{ route('superadmin.master.lokasi-asal.update', $lokasiAsal->id_lokasi) }}" method="POST" id="lokasiForm">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="nama_lokasi" class="form-label">Nama Lokasi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_lokasi') is-invalid @enderror" id="nama_lokasi" name="nama_lokasi" value="{{ old('nama_lokasi', $lokasiAsal->nama_lokasi) }}" required>
                        @error('nama_lokasi')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                
                <div class="d-flex">
                    <a href="{{ route('superadmin.master.lokasi-asal') }}" class="btn btn-secondary me-2">
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
    setupFormConfirmation('lokasiForm', 'Apakah Anda yakin ingin menyimpan perubahan data lokasi asal ini?');
</script>
@endpush

@push('styles')
<style>
    .bg-primary {
        background-color: #1E3F8C !important;
    }
    .btn-primary {
        background-color: #0dcaf0;
        border-color: #0dcaf0;
        color: #fff;
    }
    .btn-primary:hover {
        background-color: #0bb5d6;
        border-color: #0bb5d6;
    }
    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }
</style>
@endpush
