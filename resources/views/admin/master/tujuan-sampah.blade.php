@extends('admin.layout')

@section('title', 'Kelola Data Master - Tujuan Sampah')

@section('content')
<div class="content-area">
    <div class="row justify-content-center">
        <div class="col-11">
            <div class="card">
        @if(request('action') != 'create' && request('action') != 'edit')
        <div class="card-body bg-primary text-white">
            <h5 class="mb-0">Kelola Data Master > Tujuan Sampah</h5>
        </div>
        @endif
        
        <div class="card-body">
            @if(request('action') != 'create' && request('action') != 'edit')
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <a href="{{ route('admin.master.tujuan-sampah') }}?action=create" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus-circle"></i> Tambah Tujuan
                </a>
                <div class="d-flex gap-2">
                    <select class="form-select form-select-sm" id="kategoriFilter">
                        <option value="">Semua Kategori</option>
                        <option value="sampah">Sampah</option>
                        <option value="lb3">LB3</option>
                    </select>
                    <select class="form-select form-select-sm" id="statusFilter">
                        <option value="">Semua Status</option>
                        <option value="1">Aktif</option>
                        <option value="0">Tidak Aktif</option>
                    </select>
                    <input type="text" class="form-control form-control-sm" id="searchInput" placeholder="Cari data...">
                </div>
            </div>
            @endif
            
            @if(session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: "{{ session('success') }}",
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#1E3F8C',
                            didOpen: () => {
                                const successSound = new Audio('/assets/sounds/success.mp3');
                                successSound.play();
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "{{ route('admin.master.tujuan-sampah') }}";
                            }
                        });
                    });
                </script>
            @endif
            @if(session('error'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: "{{ session('error') }}",
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#1E3F8C'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                if (window.location.href.includes('action=edit')) {
                                    // Stay on edit page if edit failed
                                    return;
                                } else if (window.location.href.includes('action=create')) {
                                    // Stay on create page if create failed
                                    return;
                                } else {
                                    // For delete operations, stay on index
                                    window.location.href = "{{ route('admin.master.tujuan-sampah') }}";
                                }
                            }
                        });
                    });
                </script>
            @endif
            
            @if(request('action') == 'create')
                <div class="card mb-4">
                    <div class="card-body bg-primary text-white">
                        <h5 class="mb-0">Tambah Tujuan Sampah</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.master.tujuan-sampah.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="kategori_tujuan" class="form-label">Kategori</label>
                                <select class="form-control" id="kategori_tujuan" name="kategori_tujuan" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="bank_sampah" {{ old('kategori_tujuan') == 'bank_sampah' ? 'selected' : '' }}>Bank Sampah</option>
                                    <option value="tpa" {{ old('kategori_tujuan') == 'tpa' ? 'selected' : '' }}>TPA</option>
                                </select>
                                @error('kategori_tujuan')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="nama_tujuan" class="form-label">Tujuan</label>
                                <input type="text" class="form-control" id="nama_tujuan" name="nama_tujuan" value="{{ old('nama_tujuan') }}" required>
                                @error('nama_tujuan')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <input type="text" class="form-control" id="alamat" name="alamat" value="{{ old('alamat') }}">
                                @error('alamat')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="">-- Pilih Status --</option>
                                    <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                                @error('status')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-secondary back-confirm">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </button>
                                <button type="button" class="btn btn-primary save-confirm">
                                    <i class="fas fa-save"></i> Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @elseif(request('action') == 'edit' && request('id'))
                <div class="card mb-4">
                    <div class="card-body bg-primary text-white">
                        <h5 class="mb-0">Edit Tujuan Sampah</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $editItem = $tujuanSampah->firstWhere('id_tujuan', request('id'));
                        @endphp
                        
                        @if($editItem)
                            <form action="{{ route('admin.master.tujuan-sampah.update', $editItem->id_tujuan) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label for="kategori_tujuan" class="form-label">Kategori</label>
                                    <select class="form-control" id="kategori_tujuan" name="kategori_tujuan" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        <option value="bank_sampah" {{ old('kategori_tujuan', $editItem->kategori_tujuan) == 'bank_sampah' ? 'selected' : '' }}>Bank Sampah</option>
                                        <option value="tpa" {{ old('kategori_tujuan', $editItem->kategori_tujuan) == 'tpa' ? 'selected' : '' }}>TPA</option>
                                    </select>
                                    @error('kategori_tujuan')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="nama_tujuan" class="form-label">Tujuan</label>
                                    <input type="text" class="form-control" id="nama_tujuan" name="nama_tujuan" value="{{ old('nama_tujuan', $editItem->nama_tujuan) }}" required>
                                    @error('nama_tujuan')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="alamat" class="form-label">Alamat</label>
                                    <input type="text" class="form-control" id="alamat" name="alamat" value="{{ old('alamat', $editItem->alamat) }}">
                                    @error('alamat')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="">-- Pilih Status --</option>
                                        <option value="1" {{ old('status', $editItem->status) == 1 ? 'selected' : '' }}>Aktif</option>
                                        <option value="0" {{ old('status', $editItem->status) == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                                    </select>
                                    @error('status')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-secondary back-confirm">
                                        <i class="fas fa-arrow-left"></i> Kembali
                                    </button>
                                    <button type="button" class="btn btn-primary save-confirm">
                                        <i class="fas fa-save"></i> Simpan
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="alert alert-danger">
                                Data tujuan sampah tidak ditemukan.
                                <a href="{{ route('admin.master.tujuan-sampah') }}" class="btn btn-sm btn-secondary ms-2">
                                    Kembali
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="tujuanTable">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th width="5%" class="text-center sortable" data-sort="no">No</th>
                                <th width="15%" class="text-center sortable" data-sort="kategori">Kategori</th>
                                <th width="25%" class="text-center sortable" data-sort="tujuan">Tujuan</th>
                                <th width="25%" class="text-center sortable" data-sort="alamat">Alamat</th>
                                <th width="10%" class="text-center sortable" data-sort="status">Status</th>
                                <th width="20%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tujuanSampah as $index => $item)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $item->kategori_tujuan }}</td>
                                <td>{{ $item->nama_tujuan }}</td>
                                <td>{{ $item->alamat }}</td>
                                <td class="text-center"><span class="badge {{ $item->status ? 'bg-success' : 'bg-danger' }}">{{ $item->status ? 'Aktif' : 'Tidak Aktif' }}</span></td>
                                <td class="text-center">
                                    <a href="{{ route('admin.master.tujuan-sampah') }}?action=edit&id={{ $item->id_tujuan }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger delete-confirm" 
                                            data-action="{{ route('admin.master.tujuan-sampah.delete', $item->id_tujuan) }}">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                    <form id="delete-form-{{ $item->id_tujuan }}" action="{{ route('admin.master.tujuan-sampah.delete', $item->id_tujuan) }}" method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data tujuan sampah</td>
                            </tr>
                            @endforelse
                            
                            @if(count($tujuanSampah) == 0)
                            <!-- Data contoh jika tidak ada data dari database -->
                            <tr>
                                <td class="text-center">1</td>
                                <td>sampah</td>
                                <td>Daur Ulang</td>
                                <td>Jl. Veteran No.17</td>
                                <td class="text-center"><span class="badge bg-success">Aktif</span></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-info" disabled>
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-sm btn-danger" disabled>
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">2</td>
                                <td>sampah</td>
                                <td>Bank Sampah</td>
                                <td>Jl. Kayu Tangi No.15</td>
                                <td class="text-center"><span class="badge bg-success">Aktif</span></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-info" disabled>
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-sm btn-danger" disabled>
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">3</td>
                                <td>lb3</td>
                                <td>TPA</td>
                                <td>Jl. Hasan Basri No.10</td>
                                <td class="text-center"><span class="badge bg-success">Aktif</span></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-info" disabled>
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-sm btn-danger" disabled>
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<style>
    .form-select, .form-control {
        max-width: 200px;
    }
    
    .sortable {
        position: relative;
        cursor: pointer;
    }
    
    .sortable i {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        color: rgba(255, 255, 255, 0.5);
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #1E3F8C;
        border-color: #1E3F8C;
        color: white !important;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 3px 8px;
        margin: 0;
        border: none;
    }
    
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_info {
        display: none;
    }

    .dataTables_wrapper .dataTables_paginate {
        margin-top: 8px;
        text-align: center;
    }

    .dataTables_wrapper .dataTables_filter {
        display: none;
    }

    .form-select, .form-control {
        max-width: 150px;
    }
    
    .bg-primary {
        background-color: #1E3F8C !important;
    }
    .text-primary {
        color: #1E3F8C !important;
    }
    .btn-primary {
        background-color: #0dcaf0;
        border-color: #0dcaf0;
        color: #fff;
    }
    .btn-info {
        background-color: #17a2b8;
        color: white;
    }
    .btn-danger {
        background-color: #dc3545;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize DataTable
        const table = $('#tujuanTable').DataTable({
            pageLength: 10,
            order: [[0, 'asc']],
            language: {
                paginate: {
                    previous: 'Sebelumnya',
                    next: 'Selanjutnya'
                }
            },
            dom: 'tp',
            initComplete: function () {
                this.api().columns().every(function () {
                    let column = this;
                    if ($(column.header()).hasClass('sortable')) {
                        $(column.header()).append(' <i class="fas fa-sort"></i>');
                        $(column.header()).css('cursor', 'pointer');
                    }
                });
            }
        });

        // Apply category filter
        $('#kategoriFilter').on('change', function() {
            table.column(1).search(this.value).draw();
        });

        // Apply status filter
        $('#statusFilter').on('change', function() {
            let searchTerm = this.value === '1' ? 'Aktif' : (this.value === '0' ? 'Tidak Aktif' : '');
            table.column(4).search(searchTerm).draw();
        });

        // Apply search filter
        $('#searchInput').on('keyup', function() {
            table.search(this.value).draw();
        });
        // Delete confirmation
        document.querySelectorAll('.delete-confirm').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const action = this.dataset.action;
                
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#1E3F8C',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.querySelector(`form[action="${action}"]`);
                        if (form) {
                            form.submit();
                        }
                    }
                });
            });
        });

        // Save confirmation
        document.querySelectorAll('.save-confirm').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                const isEdit = form.method.toLowerCase() === 'post' && form.innerHTML.includes('_method=PUT');
                
                Swal.fire({
                    title: isEdit ? 'Konfirmasi Update' : 'Konfirmasi Simpan',
                    text: isEdit ? "Anda yakin ingin mengubah data ini?" : "Pastikan data yang dimasukkan sudah benar!",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#1E3F8C',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, simpan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Menyimpan...',
                            html: 'Mohon tunggu sebentar',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                                form.submit();
                            }
                        });
                    }
                });
            });
        });

        // Back confirmation
        document.querySelectorAll('.back-confirm').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Perubahan yang belum disimpan akan hilang!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#1E3F8C',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, kembali!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('admin.master.tujuan-sampah') }}";
                    }
                });
            });
        });


    });
</script>
@endpush