@extends('petugas.layout')

@section('title', 'Edit Data Sampah Diserahkan')

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
                <h4 class="text-white mb-0">Edit Data Sampah Diserahkan</h4>
            </div>
            <div class="bg-white p-4 rounded-bottom shadow">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <h5 class="alert-heading">Validasi Gagal!</h5>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <form action="{{ route('petugas.update-sampah-diserahkan', $sampah->id) }}" method="POST" enctype="multipart/form-data" id="formEdit">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id_user" value="{{ Auth::id() }}">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tgl_diserahkan" class="form-label">Tanggal Diserahkan <span class="text-danger">*</span></label>
                                <input type="date" id="tgl_diserahkan" name="tgl_diserahkan" class="form-control @error('tgl_diserahkan') is-invalid @enderror" value="{{ old('tgl_diserahkan', $sampah->tgl_diserahkan ? $sampah->tgl_diserahkan->format('Y-m-d') : '') }}" required>
                                @error('tgl_diserahkan')
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
                                    @foreach($jenis as $j)
                                        <option value="{{ $j->id_jenis }}" data-kategori="{{ $j->kategori_jenis }}" {{ old('id_jenis', $sampah->id_jenis) == $j->id_jenis ? 'selected' : '' }}>
                                            {{ $j->nama_jenis }}
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
                                <label for="id_tujuan" class="form-label">Tujuan Sampah <span class="text-danger">*</span></label>
                                <select id="id_tujuan" name="id_tujuan" class="form-control @error('id_tujuan') is-invalid @enderror" required>
                                    <option value="">-- Pilih Tujuan --</option>
                                    @foreach($tujuanSampahs as $tujuan)
                                        <option value="{{ $tujuan->id_tujuan }}" {{ old('id_tujuan', $sampah->id_tujuan) == $tujuan->id_tujuan ? 'selected' : '' }}>
                                            {{ $tujuan->nama_tujuan }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_tujuan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jumlah_berat" class="form-label">Berat (Kg) <span class="text-danger">*</span></label>
                                <input type="number" id="jumlah_berat" name="jumlah_berat" class="form-control @error('jumlah_berat') is-invalid @enderror" step="0.01" min="0" value="{{ old('jumlah_berat', $sampah->jumlah_berat) }}" required>
                                @error('jumlah_berat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="foto_diserahkan" class="form-label">Foto</label>
                                @if($sampah->foto_diserahkan)
                                    <div class="mb-2">
                                <img src="{{ asset($sampah->foto_diserahkan) }}" alt="Foto Sampah" style="max-width: 200px; border-radius: 4px;">
                                        <p class="text-muted small mt-1">Foto saat ini</p>
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('foto_diserahkan') is-invalid @enderror" id="foto_diserahkan" name="foto_diserahkan" accept="image/*">
                                <small class="text-muted">* Kosongkan jika tidak ingin mengganti foto. Format: JPG, PNG, GIF (Max 2MB)</small>
                                @error('foto_diserahkan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="alasan_edit" class="form-label">Alasan Edit <span class="text-danger">*</span></label>
                                <textarea id="alasan_edit" name="alasan_edit" class="form-control @error('alasan_edit') is-invalid @enderror" rows="4" maxlength="500" placeholder="Jelaskan alasan Anda melakukan pengeditan data ini..." required>{{ old('alasan_edit', $sampah->alasan_edit) }}</textarea>
                                <small class="text-muted">Maksimal 500 karakter</small>
                                @error('alasan_edit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group" style="margin-top: 30px;">
                        <a href="{{ route('petugas.sampah-diserahkan') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="button" class="btn btn-primary" onclick="confirmEdit()">
                            <i class="fas fa-save me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmEdit() {
        const alasanEdit = document.getElementById('alasan_edit').value.trim();
        
        if (!alasanEdit) {
            Swal.fire({
                icon: 'warning',
                title: 'Alasan Edit Diperlukan',
                text: 'Silakan isi alasan edit sebelum menyimpan perubahan.',
                confirmButtonColor: '#1e3f8c'
            });
            return;
        }
        
        Swal.fire({
            title: 'Konfirmasi Edit',
            text: 'Apakah Anda yakin ingin menyimpan perubahan data sampah diserahkan ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#1e3f8c',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Simpan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('formEdit').submit();
            }
        });
    }

    // Kategori filtering logic - filter jenis dropdown and set disabled state
    function setupKategoriListener() {
        const kategoriSelect = document.getElementById('kategori_jenis');
        const jenisSelect = document.getElementById('id_jenis');

        kategoriSelect.addEventListener('change', function() {
            const selectedKategori = this.value.toLowerCase(); // Convert to lowercase for comparison
            
            // Filter jenis options by kategori
            const jenisOptions = jenisSelect.querySelectorAll('option');
            jenisOptions.forEach(option => {
                if (option.value === '') {
                    option.style.display = 'block';
                } else {
                    const optionKategori = option.getAttribute('data-kategori');
                    if (optionKategori && optionKategori.toLowerCase() === selectedKategori) {
                        option.style.display = 'block';
                    } else {
                        option.style.display = 'none';
                    }
                }
            });
            
            // Reset jenis selection if current selection doesn't match kategori
            if (jenisSelect.value) {
                const currentJenisKategori = jenisSelect.options[jenisSelect.selectedIndex].getAttribute('data-kategori');
                if (!currentJenisKategori || currentJenisKategori.toLowerCase() !== selectedKategori) {
                    jenisSelect.value = '';
                }
            }

            // Set jenis dropdown disabled state
            jenisSelect.disabled = selectedKategori === '';
        });
    }

    // Initialize on page load
    window.addEventListener('DOMContentLoaded', function() {
        setupKategoriListener();
        
        // Trigger change event to initialize based on current kategori selection
        const kategoriSelect = document.getElementById('kategori_jenis');
        kategoriSelect.dispatchEvent(new Event('change'));
    });
</script>
@endpush
