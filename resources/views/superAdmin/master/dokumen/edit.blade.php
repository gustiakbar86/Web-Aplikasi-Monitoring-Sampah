@extends('superAdmin.layout')

@section('title', 'Edit Dokumen')

@section('content')
<div class="content-area">
    
    <div class="card">
        <div class="card-body bg-primary text-white">
            <h5 class="mb-0">Edit Dokumen</h5>
        </div>
        
        <div class="card-body">
            <form action="{{ route('superadmin.master.dokumen.update', $dokumen->id) }}" method="POST" enctype="multipart/form-data" id="dokumenForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="id_user" value="{{ auth()->id() }}">
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="nama_dokumen" class="form-label">Nama Dokumen</label>
                        <input type="text" class="form-control @error('nama_dokumen') is-invalid @enderror" id="nama_dokumen" name="nama_dokumen" value="{{ old('nama_dokumen', $dokumen->nama_dokumen) }}" required>
                        @error('nama_dokumen')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="instansi_kerjasama" class="form-label">Instansi Kerjasama</label>
                        <input type="text" class="form-control @error('instansi_kerjasama') is-invalid @enderror" id="instansi_kerjasama" name="instansi_kerjasama" value="{{ old('instansi_kerjasama', $dokumen->instansi_kerjasama) }}" required>
                        @error('instansi_kerjasama')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="file_dokumen" class="form-label">File</label>
                        <input type="file" class="form-control @error('file_dokumen') is-invalid @enderror" id="file_dokumen" name="file_dokumen">
                        @error('file_dokumen')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                        
                        @if($dokumen->file_dokumen)
                        <div class="mt-2">
                            <p>File saat ini: <strong>{{ basename($dokumen->file_dokumen) }}</strong></p>
                            <a href="{{ asset($dokumen->file_dokumen) }}" target="_blank" class="btn btn-sm btn-light">
                                <i class="fas fa-file-pdf"></i> Lihat File
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="berakhir" class="form-label">Tanggal Berakhir</label>
                        <input type="date" class="form-control @error('berakhir') is-invalid @enderror" id="berakhir" name="berakhir" value="{{ old('berakhir', \Carbon\Carbon::parse($dokumen->berakhir)->format('Y-m-d')) }}" required>
                        @error('berakhir')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="keterangan_dokumen" class="form-label">Keterangan</label>
                        <textarea class="form-control @error('keterangan_dokumen') is-invalid @enderror" id="keterangan_dokumen" name="keterangan_dokumen" rows="3">{{ old('keterangan_dokumen', $dokumen->keterangan_dokumen) }}</textarea>
                        @error('keterangan_dokumen')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                
                <div class="d-flex">
                    <a href="{{ route('superadmin.master.dokumen') }}" class="btn btn-secondary me-2">
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
    let isSubmitting = false;

    document.getElementById('dokumenForm').addEventListener('submit', function(e) {
        if (isSubmitting) {
            e.preventDefault();
            return;
        }
        isSubmitting = true;
    });
</script>
@endpush

@push('styles')
<style>
    .content-area {
        background-color: #f8f9fa;
        min-height: calc(100vh - 60px);
        padding: 20px;
    }

    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .bg-primary {
        background-color: #1E3F8C !important;
    }

    .btn-primary {
        background-color: #1E3F8C !important;
        border-color: #1E3F8C !important;
    }

    .btn-primary:hover {
        background-color: #16295e !important;
        border-color: #16295e !important;
    }

    .form-label {
        font-weight: 500;
    }

    .btn-light {
        background-color: #f8f9fa;
        border-color: #ddd;
    }
</style>
@endpush
