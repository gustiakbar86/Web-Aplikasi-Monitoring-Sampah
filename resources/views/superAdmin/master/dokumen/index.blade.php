@extends('superAdmin.layout')

@section('title', 'Kelola Dokumen')

@section('content')
<div class="content-area">
    
    <div class="card">
        <div class="card-body bg-primary text-white">
            <h5 class="mb-0">Kelola Dokumen</h5>
        </div>
        
        <div class="card-body">
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <a href="{{ route('superadmin.master.dokumen.create') }}" class="btn btn-primary btn-sm">
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
                        @forelse($dokumens as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $item->nama_dokumen }}</td>
                            <td>{{ $item->instansi_kerjasama }}</td>
                            <td class="text-center">
                                @if($item->file_dokumen)
                                    <a href="{{ asset($item->file_dokumen) }}" target="_blank" class="btn btn-sm btn-light">
                                        <i class="fas fa-file-pdf"></i> Lihat File
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($item->berakhir)->format('Y-m-d') }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($item->created_at)->format('H:i:s') }}</td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{ route('superadmin.master.dokumen.edit', $item->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete({{ $item->id }})">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                    <form id="delete-form-{{ $item->id }}" action="{{ route('superadmin.master.dokumen.destroy', $item->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data dokumen</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($dokumens->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $dokumens->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin menghapus data dokumen ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }

    // Search functionality
    document.getElementById('searchInput').addEventListener('keyup', function(e) {
        const searchValue = e.target.value.toLowerCase();
        const tableRows = document.querySelectorAll('#dokumenTable tbody tr');
        
        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchValue) ? '' : 'none';
        });
    });
</script>
@endpush

@push('styles')
<style>
    .content-area {
        background-color: #f8f9fa;
        min-height: calc(100vh - 60px);
        padding: 20px;
    }

    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .bg-primary {
        background-color: #1E3F8C !important;
    }

    .btn-primary {
        background-color: #1E3F8C !important;
        border-color: #1E3F8C !important;
    }

    .btn-primary:hover {
        background-color: #16295e !important;
        border-color: #16295e !important;
    }

    .table th {
        vertical-align: middle;
    }

    .btn-group {
        display: flex;
        gap: 5px;
    }
</style>
@endpush
