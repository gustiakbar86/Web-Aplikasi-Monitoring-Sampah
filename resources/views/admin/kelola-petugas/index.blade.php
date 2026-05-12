@extends('admin.layout')

@section('title', 'Kelola Data Petugas')

@section('content')
<div class="container-fluid px-4 py-4">
<div class="content-area">
    <div class="card">
        <div class="card-body bg-primary text-white">
            <h5 class="mb-0">Kelola Data Petugas</h5>
        </div>
        
        <div class="card-body">            
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <a href="{{ route('admin.tambah-petugas') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus-circle"></i> Tambah Petugas
                </a>
                <div class="d-flex gap-2">
                    <input type="text" class="form-control form-control-sm" id="searchInput" placeholder="Cari data...">
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="petugasTable">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th width="5%" class="text-center sortable">No</th>
                            <th width="25%" class="text-center sortable">Email</th>
                            <th width="25%" class="text-center sortable">Nama Lengkap</th>
                            <th width="25%" class="text-center sortable">Instansi</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($petugas as $index => $p)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $p->email }}</td>
                            <td>{{ $p->name }}</td>
                            <td>{{ $p->instansi->nama_instansi ?? '-' }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.edit-petugas', $p->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('admin.delete-petugas', $p->id) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger delete-btn">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data petugas</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script>
    // Handle tombol hapus
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('form');
            
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah anda yakin ingin menghapus petugas ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Tampilkan notifikasi sukses jika ada
    @if(session('success'))
        Swal.fire({
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonColor: '#1E3F8C',
            confirmButtonText: 'OK',
            didOpen: () => {
                const successSound = new Audio('/assets/sounds/success.mp3');
                successSound.play();
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('admin.kelola-petugas') }}";
            }
        });
    @endif
    @if(session('error'))
        Swal.fire({
            title: 'Gagal!',
            text: '{{ session('error') }}',
            icon: 'error',
            confirmButtonColor: '#1E3F8C',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                const currentUrl = window.location.href;
                if (currentUrl.includes('/edit/')) {
                    // Stay on edit page if edit failed
                    return;
                } else if (currentUrl.includes('/tambah')) {
                    // Stay on create page if create failed
                    return;
                } else {
                    // For delete operations, stay on index
                    window.location.href = "{{ route('admin.kelola-petugas') }}";
                }
            }
        });
    @endif
</script>
@endpush

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
        const table = $('#petugasTable').DataTable({
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