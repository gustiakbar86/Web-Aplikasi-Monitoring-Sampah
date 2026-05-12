@extends('superAdmin.layout')

@section('title', 'Data Jenis Sampah')

@section('content')
<div class="content-area-table">
    <div class="bg-primary text-white p-3 rounded mb-4">
        <h3 class="mb-0">Data Jenis Sampah</h3>
    </div>
    
    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <a href="{{ route('superadmin.master.jenis.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Data
                </a>
            </div>
            
            <div class="table-responsive">
                <table id="jenisTable" class="table table-bordered table-striped">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th width="10%" class="text-center">No</th>
                            <th width="25%">Kategori</th>
                            <th width="35%">Nama Jenis Sampah</th>
                            <th width="30%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jenises as $index => $jen)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ ucfirst($jen->kategori_jenis) }}</td>
                            <td>{{ $jen->nama_jenis }}</td>
                            <td class="text-center">
                                <a href="{{ route('superadmin.master.jenis.edit', $jen->id_jenis) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete({{ $jen->id_jenis }})">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                                <form id="delete-form-{{ $jen->id_jenis }}" action="{{ route('superadmin.master.jenis.destroy', $jen->id_jenis) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#jenisTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json",
                "lengthMenu": "_MENU_"
            },
            "pageLength": 10,
            "ordering": true,
            "searching": true,
            "info": false,
            "dom": "lrtip"
        });
    });

    function confirmDelete(id) {
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin menghapus data jenis sampah ini?',
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
</script>
@endpush

@push('styles')
<style>
    .bg-primary {
        background-color: #1E3F8C !important;
    }
    .btn-primary {
        background-color: #1E3F8C !important;
        border-color: #1E3F8C !important;
        color: #fff;
    }
    .btn-primary:hover {
        background-color: #16295e !important;
        border-color: #16295e !important;
    }
    .table th {
        vertical-align: middle;
    }
</style>
@endpush
