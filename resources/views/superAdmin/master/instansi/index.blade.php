@extends('superAdmin.layout')

@section('title', 'Master Instansi')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div style="background-color: #1E3F8C" class="p-4 rounded-top">
                <h4 class="text-white mb-0">Master Data > Instansi</h4>
            </div>
            <div class="bg-white p-4 rounded-bottom shadow">
                <!-- Success/Error Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="mb-3">
                    <a href="{{ route('superadmin.master.instansi.create') }}" class="btn" style="background-color: #1E3F8C; color: white;">
                        <i class="fas fa-plus"></i> Tambah Instansi
                    </a>
                </div>

                <div class="table-responsive">
                    <table id="instansi-table" class="table table-striped table-bordered w-100">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Kode Instansi</th>
                                <th class="text-center">Nama Instansi</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($instansis as $index => $instansi)
                            <tr>
                                <td class="text-center">{{ $instansis->firstItem() + $index }}</td>
                                <td>{{ $instansi->kode_instansi }}</td>
                                <td>{{ $instansi->nama_instansi }}</td>
                                <td class="text-center">
                                    <a href="{{ route('superadmin.master.instansi.edit', $instansi->id_instansi) }}" 
                                       class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('superadmin.master.instansi.destroy', $instansi->id_instansi) }}" 
                                          method="POST" 
                                          class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada data instansi</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $instansis->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#instansi-table').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
        pagingType: "simple_numbers",
        paging: true,
        info: false,
        dom: "lrtip",
        language: {
            search: "Cari:",
            lengthMenu: "_MENU_",
            zeroRecords: "Tidak ditemukan data yang sesuai",
            paginate: {
                first: "Awal",
                last: "Akhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }
        }
    });

    // Confirm delete with SweetAlert
    $('.delete-form').on('submit', function(e) {
        e.preventDefault();
        var form = this;

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data instansi akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endpush

@endsection
