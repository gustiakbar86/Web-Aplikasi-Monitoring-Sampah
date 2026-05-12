@extends('admin.layout')

@section('title', 'Edit Dokumen')

@section('content')
<div class="content-area">
    
    <div class="card">
        <div class="card-body bg-primary text-white">
            <h5 class="mb-0">Edit Dokumen</h5>
        </div>
        
        <div class="card-body">
            <form action="{{ route('admin.dokumen.update', $dokumen->id) }}" method="POST" enctype="multipart/form-data" id="dokumenForm">
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
                    <a href="{{ route('admin.dokumen.index') }}" class="btn btn-secondary me-2">
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

        e.preventDefault();
        
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin menyimpan perubahan dokumen ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Simpan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                isSubmitting = true;
                this.submit();
            }
        });
    });
</script>
@endpush

@push('styles')
<style>
    .bg-primary {
        background-color: #1E3F8C !important;
    }
    .text-primary {
        color: #1E3F8C !important;
    }
    .nav-tabs-container {
        background-color: #1E3F8C;
        border-radius: 5px;
        padding: 5px;
    }
    .nav-pills .nav-link {
        border-radius: 5px;
        padding: 10px 15px;
        margin-right: 2px;
    }
    .nav-pills .nav-link.active {
        background-color: white;
        color: #ffffff !important;
        font-weight: bold;
    }
    .btn-primary {
        background-color: #0dcaf0;
        border-color: #0dcaf0;
        color: #fff;
    }
    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }
</style>
@endpush
