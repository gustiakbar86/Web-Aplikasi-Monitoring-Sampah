@extends('superAdmin.layout')

@section('title', 'Tambah Sampah Terkelola')

@section('content')
<div class="content-area-form">
    
    <div class="card">
        <div class="card-body bg-primary text-white" style="background-color: #1E3F8C !important;">
            <h5 class="mb-0">Tambah Sampah Terkelola</h5>
        </div>
        
        <div class="card-body">
            <form action="{{ route('superadmin.master.sampah-terkelola.store') }}" method="POST" id="sampahTerkelolaForm">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="tgl" class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('tgl') is-invalid @enderror" id="tgl" name="tgl" value="{{ old('tgl', date('Y-m-d')) }}" required>
                        @error('tgl')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="id_user" class="form-label">User <span class="text-danger">*</span></label>
                        <select class="form-select @error('id_user') is-invalid @enderror" id="id_user" name="id_user" required>
                            <option value="">-- Pilih User --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('id_user') == $user->id ? 'selected' : '' }}>{{ $user->instansi->nama_instansi }} - {{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('id_user')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="id_lokasi" class="form-label">Lokasi Asal <span class="text-danger">*</span></label>
                        <select class="form-select @error('id_lokasi') is-invalid @enderror" id="id_lokasi" name="id_lokasi" required>
                            <option value="">-- Pilih Lokasi Asal --</option>
                            @foreach($lokasiAsals as $lokasi)
                                <option value="{{ $lokasi->id_lokasi }}" {{ old('id_lokasi') == $lokasi->id_lokasi ? 'selected' : '' }}>{{ $lokasi->nama_lokasi }}</option>
                            @endforeach
                        </select>
                        @error('id_lokasi')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="kategori_jenis" class="form-label">Kategori Jenis <span class="text-danger">*</span></label>
                        <select class="form-select @error('kategori_jenis') is-invalid @enderror" id="kategori_jenis" name="kategori_jenis" required>
                            <option value="">-- Pilih Kategori Jenis --</option>
                            @foreach($kategoriJenises as $kategori)
                                <option value="{{ $kategori }}" {{ old('kategori_jenis') == $kategori ? 'selected' : '' }}>
                                    {{ ucfirst($kategori) }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_jenis')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3" id="jenis-form-wrapper" style="display: none;">
                    <div class="col-md-6">
                        <label for="id_jenis" class="form-label">Jenis Sampah <span class="text-danger">*</span></label>
                        <select class="form-select @error('id_jenis') is-invalid @enderror" id="id_jenis" name="id_jenis">
                            <option value="">-- Pilih Jenis Sampah --</option>
                            @foreach($jenises as $jen)
                                <option value="{{ $jen->id_jenis }}" data-kategori="{{ $jen->kategori_jenis }}" {{ old('id_jenis') == $jen->id_jenis ? 'selected' : '' }}>{{ $jen->nama_jenis }}</option>
                            @endforeach
                        </select>
                        @error('id_jenis')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="jumlah_berat" class="form-label">Jumlah Berat (Kg) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control @error('jumlah_berat') is-invalid @enderror" id="jumlah_berat" name="jumlah_berat" value="{{ old('jumlah_berat') }}" required>
                        @error('jumlah_berat')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                
                <div class="d-flex">
                    <a href="{{ route('superadmin.master.sampah-terkelola') }}" class="btn btn-secondary me-2">
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    setupFormConfirmation('sampahTerkelolaForm', 'Apakah Anda yakin ingin menyimpan data sampah terkelola ini?');
    
    // Show/Hide Jenis Sampah Form berdasarkan Kategori
    document.getElementById('kategori_jenis').addEventListener('change', function() {
        const selectedKategori = this.value.toLowerCase(); // Convert to lowercase for comparison
        const jenisFormWrapper = document.getElementById('jenis-form-wrapper');
        const jenisSelect = document.getElementById('id_jenis');
        const jenisOptions = document.querySelectorAll('#id_jenis option[data-kategori]');
        const defaultOption = document.querySelector('#id_jenis option[value=""]');
        
        if (selectedKategori === '') {
            // Sembunyikan form jenis
            jenisFormWrapper.style.display = 'none';
            jenisSelect.removeAttribute('required');
            jenisSelect.value = '';
        } else {
            // Tampilkan form jenis
            jenisFormWrapper.style.display = 'block';
            jenisSelect.setAttribute('required', 'required');
            
            // Filter opsi jenis sesuai kategori (case-insensitive)
            jenisOptions.forEach(option => {
                if (option.dataset.kategori.toLowerCase() === selectedKategori) {
                    option.style.display = 'block';
                } else {
                    option.style.display = 'none';
                }
            });
            
            // Tampilkan pilihan default
            if (defaultOption) {
                defaultOption.style.display = 'block';
            }
            
            // Reset jenis dropdown
            jenisSelect.value = '';
        }
    });
    
    // Initialize on page load
    window.addEventListener('DOMContentLoaded', function() {
        const kategoriSelect = document.getElementById('kategori_jenis');
        if (kategoriSelect.value !== '') {
            kategoriSelect.dispatchEvent(new Event('change'));
        }
    });
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
