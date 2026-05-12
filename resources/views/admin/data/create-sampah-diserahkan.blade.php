@extends('admin.layout')

@section('title', 'Tambah Data Sampah Diserahkan')

@push('styles')
<style>
    body {
        background-color: #f8f9fa;
    }
    
    .rounded-top {
        border-top-left-radius: 8px !important;
        border-top-right-radius: 8px !important;
    }
    
    .rounded-bottom {
        border-bottom-left-radius: 8px !important;
        border-bottom-right-radius: 8px !important;
    }
    
    .bg-primary {
        background-color: #1e3f8c !important;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-label {
        font-weight: 600;
        margin-bottom: 8px;
        color: #333;
    }
    
    .form-control {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
    }
    
    .btn-primary {
        background-color: #1e3f8c;
        border: none;
        padding: 8px 20px;
        border-radius: 4px;
        color: #fff;
        font-weight: 600;
        cursor: pointer;
    }
    
    .btn-secondary {
        background-color: #fff;
        border: 1px solid #ddd;
        padding: 8px 20px;
        border-radius: 4px;
        color: #333;
        font-weight: 600;
        cursor: pointer;
        margin-right: 10px;
    }
    
    .btn-primary:hover {
        background-color: #0099cc;
    }
    
    .btn-secondary:hover {
        background-color: #f0f0f0;
    }
    
    .col-md-6 {
        flex: 0 0 50%;
        max-width: 50%;
        padding-right: 15px;
        padding-left: 15px;
    }
    
    @media (max-width: 768px) {
        .col-md-6 {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="bg-primary p-4 rounded-top">
                <h4 class="text-white mb-0">Tambah Data Sampah Diserahkan</h4>
            </div>
            <div class="bg-white p-4 rounded-bottom shadow">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.data.sampah-diserahkan.store') }}" method="POST" enctype="multipart/form-data" id="formSampah">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_user" class="form-label">Pengguna <span class="text-danger">*</span></label>
                                <select id="id_user" name="id_user" class="form-control @error('id_user') is-invalid @enderror" required>
                                    <option value="">-- Pilih Pengguna --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('id_user') == $user->id ? 'selected' : '' }}>
                                            {{ $user->instansi->nama_instansi ?? 'Unknown' }} - {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_user')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tgl_diserahkan" class="form-label">Tanggal <span class="text-danger">*</span></label>
                                <input type="date" id="tgl_diserahkan" name="tgl_diserahkan" class="form-control @error('tgl_diserahkan') is-invalid @enderror" value="{{ old('tgl_diserahkan', date('Y-m-d')) }}" required>
                                @error('tgl_diserahkan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_lokasi" class="form-label">Sumber Sampah <span class="text-danger">*</span></label>
                                <select id="id_lokasi" name="id_lokasi" class="form-control @error('id_lokasi') is-invalid @enderror" required>
                                    <option value="">-- Pilih Sumber --</option>
                                    @foreach($lokasiAsals as $lokasi)
                                        <option value="{{ $lokasi->id_lokasi }}" {{ old('id_lokasi') == $lokasi->id_lokasi ? 'selected' : '' }}>
                                            {{ $lokasi->nama_lokasi }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_lokasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kategori_jenis" class="form-label">Kategori Jenis <span class="text-danger">*</span></label>
                                <select id="kategori_jenis" name="kategori_jenis" class="form-control @error('kategori_jenis') is-invalid @enderror" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($kategoriJenises as $kategori)
                                        <option value="{{ $kategori }}" {{ old('kategori_jenis') == $kategori ? 'selected' : '' }}>
                                            {{ ucfirst($kategori) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kategori_jenis')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_jenis" class="form-label">Jenis Sampah <span class="text-danger">*</span></label>
                                <select id="id_jenis" name="id_jenis" class="form-control @error('id_jenis') is-invalid @enderror" required>
                                    <option value="">-- Pilih Jenis --</option>
                                    @foreach($jenis as $j)
                                        <option value="{{ $j->id_jenis }}" data-kategori="{{ $j->kategori_jenis }}" {{ old('id_jenis') == $j->id_jenis ? 'selected' : '' }}>
                                            {{ $j->nama_jenis }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_jenis')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_tujuan" class="form-label">Tujuan Sampah <span class="text-danger">*</span></label>
                                <select id="id_tujuan" name="id_tujuan" class="form-control @error('id_tujuan') is-invalid @enderror" required>
                                    <option value="">-- Pilih Tujuan --</option>
                                    @foreach($tujuanSampahs as $tujuan)
                                        <option value="{{ $tujuan->id_tujuan }}" {{ old('id_tujuan') == $tujuan->id_tujuan ? 'selected' : '' }}>
                                            {{ $tujuan->nama_tujuan }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_tujuan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jumlah_berat" class="form-label">Berat (Kg) <span class="text-danger">*</span></label>
                                <input type="number" id="jumlah_berat" name="jumlah_berat" class="form-control @error('jumlah_berat') is-invalid @enderror" step="0.01" min="0" placeholder="0.00" value="{{ old('jumlah_berat') }}" required>
                                @error('jumlah_berat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="foto_diserahkan" class="form-label">Foto</label>
                                <input type="file" class="form-control @error('foto_diserahkan') is-invalid @enderror" id="foto_diserahkan" name="foto_diserahkan" accept="image/*">
                                <small class="text-muted">Format: JPG, PNG, GIF (Max 2MB)</small>
                                @error('foto_diserahkan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-start mt-4">
                        <a href="{{ route('admin.data.sampah-diserahkan') }}" class="btn btn-secondary">
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const kategoriSelect = document.getElementById('kategori_jenis');
    const jenisSelect = document.getElementById('id_jenis');
    const tujuanSelect = document.getElementById('id_tujuan');
    
    kategoriSelect.addEventListener('change', function() {
        const selectedKategori = this.value;
        const jenisOptions = document.querySelectorAll('#id_jenis option');
        
        if (selectedKategori === '' || !selectedKategori) {
            jenisSelect.disabled = true;
            tujuanSelect.disabled = true;
            jenisSelect.value = '';
            tujuanSelect.value = '';
        } else {
            jenisSelect.disabled = false;
            tujuanSelect.disabled = false;
            jenisSelect.value = '';
            tujuanSelect.value = '';
            
            jenisOptions.forEach(option => {
                if (option.value === '') {
                    option.style.display = 'block';
                } else if (option.dataset.kategori === selectedKategori) {
                    option.style.display = 'block';
                } else {
                    option.style.display = 'none';
                }
            });
        }
    });
});
</script>
@endpush
