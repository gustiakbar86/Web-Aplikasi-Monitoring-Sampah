@extends('superAdmin.layout')

@section('title', 'Edit Instansi')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div style="background-color: #1E3F8C" class="p-4 rounded-top">
                <h4 class="text-white mb-0">Master Data > Edit Instansi</h4>
            </div>
            <div class="bg-white p-4 rounded-bottom shadow">
                <form action="{{ route('superadmin.master.instansi.update', $instansi->id_instansi) }}" method="POST" id="instansiForm">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="kode_instansi" class="form-label">Kode Instansi <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('kode_instansi') is-invalid @enderror" 
                               id="kode_instansi" 
                               name="kode_instansi" 
                               value="{{ old('kode_instansi', $instansi->kode_instansi) }}"
                               placeholder="Masukkan kode instansi"
                               required>
                        @error('kode_instansi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="nama_instansi" class="form-label">Nama Instansi <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('nama_instansi') is-invalid @enderror" 
                               id="nama_instansi" 
                               name="nama_instansi" 
                               value="{{ old('nama_instansi', $instansi->nama_instansi) }}"
                               placeholder="Masukkan nama instansi"
                               required>
                        @error('nama_instansi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-start gap-2">
                        <button type="submit" class="btn btn-primary text-white">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <a href="{{ route('superadmin.master.instansi') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    let isSubmitting = false;
    
    $('#instansiForm').on('submit', function(e) {
        if (isSubmitting) {
            e.preventDefault();
            return;
        }
        
        e.preventDefault();
        
        var kodeInstansi = $('#kode_instansi').val().trim();
        var namaInstansi = $('#nama_instansi').val().trim();

        if (!kodeInstansi) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Kode instansi harus diisi!',
            });
            return false;
        }

        if (!namaInstansi) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Nama instansi harus diisi!',
            });
            return false;
        }
        
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin menyimpan perubahan data instansi ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Simpan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                isSubmitting = true;
                document.getElementById('instansiForm').submit();
            }
        });
    });
});
</script>
<style>
    .btn-primary {
        background-color: #0dcaf0;
        border-color: #0dcaf0;
        color: #fff !important;
    }
    .btn-primary:hover {
        background-color: #0bb5d6;
        border-color: #0bb5d6;
        color: #fff !important;
    }
    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
        color: #fff !important;
    }
</style>
@endpush

@endsection
