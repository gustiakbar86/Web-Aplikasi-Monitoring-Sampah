@extends('admin.layout')

@section('title', 'Kelola Dokumen')

@section('content')
<div class="content-area">
    
    <div class="card">
        <div class="card-body bg-primary text-white">
            <h5 class="mb-0">Kelola Dokumen</h5>
        </div>
        
        <div class="card-body">
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <a href="{{ route('admin.dokumen.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus-circle"></i> Tambah Dokumen
                </a>
                <div class="d-flex gap-2">
                    <input type="text" class="form-control form-control-sm" id="searchInput" placeholder="Cari data...">
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dokumenTable">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th width="5%" class="text-center sortable">No</th>
                            <th width="20%" class="text-center sortable">Nama Dokumen</th>
                            <th width="15%" class="text-center sortable">Instansi</th>
                            <th width="15%" class="text-center">File</th>
                            <th width="15%" class="text-center sortable">Tanggal Berakhir</th>
                            <th width="15%" class="text-center sortable">Waktu Upload</th>
                            <th width="10%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dokumens as $index => $dokumen)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $dokumen->nama_dokumen }}</td>
                            <td>{{ $dokumen->instansi_kerjasama }}</td>
                            <td class="text-center">
                                @if($dokumen->file_dokumen)
                                    <a href="{{ asset($dokumen->file_dokumen) }}" target="_blank" class="btn btn-sm btn-light">
                                        <i class="fas fa-file-pdf"></i> Lihat File
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($dokumen->berakhir)->format('Y-m-d') }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($dokumen->created_at)->format('H:i:s') }}</td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{ route('admin.dokumen.edit', $dokumen->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete({{ $dokumen->id }})">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                    <form id="delete-form-{{ $dokumen->id }}" action="{{ route('admin.dokumen.destroy', $dokumen->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <!-- Data contoh jika tidak ada data dari database -->
                        <tr>
                            <td class="text-center">1</td>
                            <td>Rekap Pengelolaan Sampah</td>
                            <td>Pelindo Subregional Banjarmasin</td>
                            <td class="text-center">
                                <a href="#" class="btn btn-sm btn-light">
                                    <i class="fas fa-file-pdf"></i> Lihat File
                                </a>
                            </td>
                            <td class="text-center">2025-09-23</td>
                            <td class="text-center">16:11:07</td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="#" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(isset($dokumens) && $dokumens->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $dokumens->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

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
                window.location.href = "{{ route('admin.dokumen.index') }}";
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
                const currentUrl = window.location.href;
                if (currentUrl.includes('/edit/')) {
                    // Stay on edit page if edit failed
                    return;
                } else if (currentUrl.includes('/create')) {
                    // Stay on create page if create failed
                    return;
                } else {
                    // For delete operations, stay on index
                    window.location.href = "{{ route('admin.dokumen.index') }}";
                }
            }
        });
    });
</script>
@endif

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<style>
    .table th, .table td {
        vertical-align: middle;
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

    .dataTables_wrapper .dataTables_paginate .previous,
    .dataTables_wrapper .dataTables_paginate .next {
        display: block;
        margin-top: 5px;
    }

    .form-select, .form-control {
        max-width: 150px;
    }
    
    .bg-primary {
        background-color: #1E3F8C !important;
    }
    .btn-light {
        background-color: #f8f9fa;
        border-color: #ddd;
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
        const table = $('#dokumenTable').DataTable({
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

        // Apply search filter
        $('#searchInput').on('keyup', function() {
            table.search(this.value).draw();
        });
    });
</script>
@endpush

@push('scripts')
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin menghapus dokumen ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endpush
