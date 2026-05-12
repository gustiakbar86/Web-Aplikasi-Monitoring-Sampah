@extends('admin.layout')

@section('title', 'Edit Sampah Terkelola')

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
                <h4 class="text-white mb-0">Edit Data Sampah Terkelola</h4>
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

                <form action="{{ route('admin.data.sampah-terkelola.update', $sampah->id) }}" method="POST" enctype="multipart/form-data" id="formEdit">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tgl" class="form-label">Tanggal <span class="text-danger">*</span></label>
                                <input type="date" id="tgl" name="tgl" class="form-control @error('tgl') is-invalid @enderror" value="{{ old('tgl', $sampah->tgl ? $sampah->tgl->format('Y-m-d') : '') }}" required>
                                @error('tgl')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_lokasi" class="form-label">Sumber Sampah <span class="text-danger">*</span></label>
                                <select id="id_lokasi" name="id_lokasi" class="form-control @error('id_lokasi') is-invalid @enderror" required>
                                    <option value="">-- Pilih Sumber --</option>
                                    @foreach($lokasiAsals as $lokasi)
                                        <option value="{{ $lokasi->id_lokasi }}" {{ old('id_lokasi', $sampah->id_lokasi) == $lokasi->id_lokasi ? 'selected' : '' }}>
                                            {{ $lokasi->nama_lokasi }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_lokasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kategori_jenis" class="form-label">Kategori Jenis <span class="text-danger">*</span></label>
                                <select id="kategori_jenis" name="kategori_jenis" class="form-control @error('kategori_jenis') is-invalid @enderror" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($kategoriJenises as $kategori)
                                        <option value="{{ $kategori }}" {{ old('kategori_jenis', $sampah->jenis->kategori_jenis) == $kategori ? 'selected' : '' }}>
                                            {{ ucfirst($kategori) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kategori_jenis')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_jenis" class="form-label">Jenis Sampah <span class="text-danger">*</span></label>
                                <select id="id_jenis" name="id_jenis" class="form-control @error('id_jenis') is-invalid @enderror" required>
                                    <option value="">-- Pilih Jenis --</option>
                                    @foreach($jenisAll as $jenis)
                                        <option value="{{ $jenis->id_jenis }}" data-kategori="{{ $jenis->kategori_jenis }}" {{ old('id_jenis', $sampah->id_jenis) == $jenis->id_jenis ? 'selected' : '' }}>
                                            {{ $jenis->nama_jenis }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_jenis')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jumlah_berat" class="form-label">Berat (Kg) <span class="text-danger">*</span></label>
                                <input type="number" id="jumlah_berat" name="jumlah_berat" class="form-control @error('jumlah_berat') is-invalid @enderror" step="0.01" min="0" value="{{ old('jumlah_berat', $sampah->jumlah_berat) }}" required>
                                @error('jumlah_berat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="foto_kelola" class="form-label">Foto</label>
                                @if($sampah->foto_kelola)
                                    <div class="mb-2">
                                        <img src="{{ asset($sampah->foto_kelola) }}" alt="Foto Sampah" style="max-width: 200px; border-radius: 4px;">
                                        <p class="text-muted small mt-1">Foto saat ini</p>
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('foto_kelola') is-invalid @enderror" id="foto_kelola" name="foto_kelola" accept="image/*">
                                <small class="text-muted">* Kosongkan jika tidak ingin mengganti foto. Format: JPG, PNG, GIF (Max 2MB)</small>
                                @error('foto_kelola')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="alasan_edit" class="form-label">Alasan Edit <span class="text-danger">*</span></label>
                                <textarea id="alasan_edit" name="alasan_edit" class="form-control @error('alasan_edit') is-invalid @enderror" rows="5" maxlength="500" placeholder="Jelaskan alasan Anda melakukan pengeditan data ini..." required>{{ old('alasan_edit', $sampah->alasan_edit) }}</textarea>
                                <small class="text-muted">Maksimal 500 karakter</small>
                                @error('alasan_edit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-start mt-4">
                        <a href="{{ route('admin.data.sampah-terkelola') }}" class="btn btn-secondary">
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
    
    kategoriSelect.addEventListener('change', function() {
        const selectedKategori = this.value;
        const jenisOptions = document.querySelectorAll('#id_jenis option');
        
        if (selectedKategori === '' || !selectedKategori) {
            jenisSelect.disabled = true;
            jenisSelect.value = '';
        } else {
            jenisSelect.disabled = false;
            
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
    
    // Trigger change event to initialize based on current kategori selection
    kategoriSelect.dispatchEvent(new Event('change'));
});
</script>
@endpush
