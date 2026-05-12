@extends('superAdmin.layout')

@section('title', 'Data Tujuan Sampah')

@section('content')
<div class="content-area-table">
    <div class="bg-primary text-white p-3 rounded mb-4">
        <h3 class="mb-0">Data Tujuan Sampah</h3>
    </div>
    
    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <a href="{{ route('superadmin.master.tujuan.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Data
                </a>
            </div>
            
            <div class="table-responsive">
                <table id="tujuanTable" class="table table-bordered table-striped">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th width="8%" class="text-center">No</th>
                            <th width="15%">Kategori Tujuan</th>
                            <th width="25%">Nama Tujuan</th>
                            <th width="25%">Alamat</th>
                            <th width="10%" class="text-center">Status</th>
                            <th width="17%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tujuanSampahs as $index => $tuj)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $tuj->kategori_tujuan }}</td>
                            <td>{{ $tuj->nama_tujuan }}</td>
                            <td>{{ $tuj->alamat ?? '-' }}</td>
                            <td class="text-center"><span class="badge {{ $tuj->status ? 'bg-success' : 'bg-danger' }}">{{ $tuj->status ? 'Aktif' : 'Tidak Aktif' }}</span></td>
                            <td class="text-center">
                                <a href="{{ route('superadmin.master.tujuan.edit', $tuj->id_tujuan) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete({{ $tuj->id_tujuan }})">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                                <form id="delete-form-{{ $tuj->id_tujuan }}" action="{{ route('superadmin.master.tujuan.destroy', $tuj->id_tujuan) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data</td>
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
        $('#tujuanTable').DataTable({
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
            text: 'Apakah Anda yakin ingin menghapus data tujuan sampah ini?',
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
