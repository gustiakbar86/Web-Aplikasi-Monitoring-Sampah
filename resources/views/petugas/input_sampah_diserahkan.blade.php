@extends('petugas.layout')

@section('title', 'Input Data Sampah Diserahkan')

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
    .form-container {
        max-width: 100%;
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
    
    .page-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 20px;
    }
    
    .input-group {
        position: relative;
        display: flex;
        flex-wrap: wrap;
        align-items: stretch;
        width: 100%;
    }
    
    .input-group-text {
        display: flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        font-weight: 400;
        line-height: 1.5;
        text-align: center;
        white-space: nowrap;
        background-color: #e9ecef;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
    }
    
    .custom-file {
        position: relative;
        display: inline-block;
        width: 100%;
        height: calc(1.5em + 0.75rem + 2px);
        margin-bottom: 0;
    }
    
    .custom-file-input {
        position: relative;
        z-index: 2;
        width: 100%;
        height: calc(1.5em + 0.75rem + 2px);
        margin: 0;
        opacity: 0;
    }
    
    .custom-file-label {
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        z-index: 1;
        height: calc(1.5em + 0.75rem + 2px);
        padding: 0.375rem 0.75rem;
        font-weight: 400;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .row {
        display: flex;
        flex-wrap: wrap;
        margin-right: -15px;
        margin-left: -15px;
    }
    
    .col-md-6 {
        flex: 0 0 50%;
        max-width: 50%;
        padding-right: 15px;
        padding-left: 15px;
    }
    
    /* Responsive styles */
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
                <h4 class="text-white mb-0">Input Data Sampah</h4>
            </div>
            <div class="bg-white p-4 rounded-bottom shadow">
                <form action="{{ route('petugas.store-sampah-diserahkan') }}" method="POST" enctype="multipart/form-data" id="formSampah">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tgl" class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" id="tgl_diserahkan" name="tgl_diserahkan" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="id_lokasi" class="form-label">Sumber Sampah <span class="text-danger">*</span></label>
                        <select id="id_lokasi" name="id_lokasi" class="form-control" required>
                            <option value="" selected disabled>-- Pilih Sumber --</option>
                            @foreach($lokasiAsals as $lokasi)
                                <option value="{{ $lokasi->id_lokasi }}">{{ $lokasi->nama_lokasi }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="kategori_jenis" class="form-label">Kategori Jenis <span class="text-danger">*</span></label>
                        <select id="kategori_jenis" name="kategori_jenis" class="form-control" required>
                            <option value="" selected disabled>-- Pilih Kategori --</option>
                            @foreach($kategoriJenises as $kategori)
                                <option value="{{ $kategori }}">{{ ucfirst($kategori) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-md-6" id="jenis-form-wrapper">
                    <div class="form-group">
                        <label for="id_jenis" class="form-label">Jenis Sampah <span class="text-danger">*</span></label>
                        <select id="id_jenis" name="id_jenis" class="form-control" disabled>
                            <option value="" selected disabled>-- Pilih Jenis --</option>
                            @foreach($jenis as $j)
                                <option value="{{ $j->id_jenis }}" data-kategori="{{ $j->kategori_jenis }}">{{ $j->nama_jenis }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row" id="tujuan-form-wrapper">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="id_tujuan" class="form-label">Tujuan Diserahkan <span class="text-danger">*</span></label>
                        <select id="id_tujuan" name="id_tujuan" class="form-control" required>
                            <option value="" selected disabled>-- Pilih Tujuan --</option>
                            @foreach($tujuanSampahs as $tujuan)
                                <option value="{{ $tujuan->id_tujuan }}">{{ $tujuan->nama_tujuan }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="jumlah_berat" class="form-label">Berat (Kg) <span class="text-danger">*</span></label>
                        <input type="number" id="jumlah_berat" name="jumlah_berat" class="form-control" step="0.01" min="0" placeholder="0.00" required>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="foto_diserahkan" class="form-label">Foto</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="foto_diserahkan" name="foto_diserahkan" accept="image/*">
                                <label class="custom-file-label" for="foto_diserahkan">Choose file</label>
                            </div>
                        </div>
                        <small class="text-muted">* Format: JPG, PNG, GIF (Max 2MB)</small>
                    </div>
                </div>
            </div>
            
            <input type="hidden" name="id_user" value="{{ Auth::id() }}">
            
            <div class="form-group" style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd;">
                <button type="button" class="btn btn-primary" id="btnSubmit" style="display: inline-block;">
                    <i class="fas fa-save me-2"></i> Simpan
                </button>
            </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@include('components.form-confirmation')

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('formSampah');
        const btnSubmit = document.getElementById('btnSubmit');
        const btnBack = document.getElementById('btnBack');
        let formChanged = false;
        window.isSubmitting = false;

        // Track form changes
        const formInputs = form.querySelectorAll('input, select, textarea');
        formInputs.forEach(input => {
            input.addEventListener('change', function() {
                formChanged = true;
            });
        });

        // Update file input label with selected filename
        const fileInput = document.querySelector('.custom-file-input');
        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                const fileName = e.target.files[0]?.name || 'Choose file';
                const label = e.target.nextElementSibling;
                if (label) {
                    label.innerHTML = fileName;
                }
                formChanged = true;
            });
        }

        // Function to show confirm dialog
        function showConfirm(title, text, confirmText, callback) {
            Swal.fire({
                title,
                text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: confirmText,
                cancelButtonText: 'Batal',
                confirmButtonColor: '#0dcaf0',
                cancelButtonColor: '#6c757d'
            }).then(result => {
                if (result.isConfirmed) callback();
            });
        }

        // ✅ Tombol SIMPAN
        btnSubmit.addEventListener('click', function() {
            // Validate form first
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            showConfirm(
                'Simpan Data?',
                'Pastikan data sudah benar sebelum melanjutkan.',
                'Ya, Simpan',
                () => {
                    window.isSubmitting = true;
                    formChanged = false;
                    form.submit();
                }
            );
        });

        // ✅ Warning jika close tab / refresh
        window.onbeforeunload = function(e) {
            if (formChanged && !window.isSubmitting) {
                e.preventDefault();
                return '';
            }
        };

        // ✅ Sukses / flash message
        @if(session('success'))
        Swal.fire({
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonColor: '#0dcaf0'
        });
        @endif

        // ✅ Error / flash message
        @if(session('error'))
        Swal.fire({
            title: 'Gagal!',
            text: '{{ session('error') }}',
            icon: 'error',
            confirmButtonColor: '#dc3545'
        });
        @endif
        
        // ✅ Enable/Disable Jenis & Tujuan berdasarkan Kategori
        const kategoriSelect = document.getElementById('kategori_jenis');
        const jenisSelect = document.getElementById('id_jenis');
        const tujuanSelect = document.getElementById('id_tujuan');
        
        kategoriSelect.addEventListener('change', function() {
            const selectedKategori = this.value.toLowerCase(); // Convert to lowercase for comparison
            const jenisOptions = document.querySelectorAll('#id_jenis option');
            
            if (selectedKategori === '' || !selectedKategori) {
                // Disable dan reset jenis & tujuan select
                jenisSelect.disabled = true;
                tujuanSelect.disabled = true;
                jenisSelect.value = '';
                tujuanSelect.value = '';
                jenisSelect.removeAttribute('required');
                tujuanSelect.removeAttribute('required');
            } else {
                // Enable jenis & tujuan select
                jenisSelect.disabled = false;
                tujuanSelect.disabled = false;
                jenisSelect.setAttribute('required', 'required');
                tujuanSelect.setAttribute('required', 'required');
                jenisSelect.value = '';
                tujuanSelect.value = '';
                
                // Filter opsi jenis sesuai kategori (case-insensitive)
                jenisOptions.forEach(option => {
                    if (option.value === '') {
                        // Default option selalu tampil
                        option.style.display = 'block';
                    } else if (option.dataset.kategori.toLowerCase() === selectedKategori) {
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