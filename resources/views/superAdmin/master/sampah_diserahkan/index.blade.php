@extends('superAdmin.layout')

@section('title', 'Data Sampah Diserahkan')

@section('content')
<div class="content-area-table">
    <div class="bg-primary text-white p-3 rounded mb-4">
        <h3 class="mb-0">Data Sampah Diserahkan</h3>
    </div>

    <div class="card">
        <div class="card-body">

            <div class="mb-3">
                <a href="{{ route('superadmin.master.sampah-diserahkan.create') }}" class="btn btn-primary text-white">
                    <i class="fas fa-plus"></i> Tambah Data
                </a>
            </div>

            <div class="table-responsive">
                <table id="sampahDiserahkanTable" class="table table-bordered table-striped">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th width="4%" class="text-center">No</th>
                            <th width="8%" class="text-center">Foto</th>
                            <th width="8%" class="text-center">Tanggal</th>
                            <th width="12%">User</th>
                            <th width="12%">Lokasi Asal</th>
                            <th width="12%">Jenis Sampah</th>
                            <th width="11%">Tujuan</th>
                            <th width="9%" class="text-center">Berat (Kg)</th>
                            <th width="14%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sampahDiserahkans as $index => $sampah)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>

                            <td class="text-center">
                                @if ($sampah->foto_diserahkan)
                                    <a href="{{ asset($sampah->foto_diserahkan) }}" target="_blank">
                                        <i class="fas fa-image"></i> Foto
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            <td class="text-center">
                                {{ \Carbon\Carbon::parse($sampah->tgl_diserahkan)->format('d/m/Y') }}
                            </td>

                            <td>{{ $sampah->user->name ?? '-' }}</td>
                            <td>{{ $sampah->lokasiAsal->nama_lokasi ?? '-' }}</td>
                            <td>{{ $sampah->jenis->kategori_jenis ?? '-' }} - {{ $sampah->jenis->nama_jenis ?? '' }}</td>
                            <td>{{ $sampah->tujuanSampah->nama_tujuan ?? '-' }}</td>

                            <td class="text-center">
                                {{ number_format($sampah->jumlah_berat, 2) }}
                            </td>

                            <td class="text-center">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('superadmin.master.sampah-diserahkan.show', $sampah->id) }}"
                                       class="btn btn-primary btn-sm text-white">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>

                                    <button type="button"
                                            class="btn btn-danger btn-sm"
                                            onclick="confirmDelete({{ $sampah->id }})">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </div>

                                <form id="delete-form-{{ $sampah->id }}"
                                      action="{{ route('superadmin.master.sampah-diserahkan.destroy', $sampah->id) }}"
                                      method="POST" style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>

                        @empty
                        <tr>
                            <td colspan="9" class="text-center">Tidak ada data</td>
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
$(document).ready(function () {
    let table = $('#sampahDiserahkanTable').DataTable({
        destroy: true,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        },
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
        ordering: true,
        searching: true,
        paging: true,
        info: false,
        columnDefs: [
            { orderable: false, targets: [1, 8] } // Foto dan Aksi tidak bisa di-sort
        ]
    });
});

function toggleDetail(id) {
    const detailRow = document.getElementById(id);
    if (detailRow.style.display === 'none') {
        detailRow.style.display = 'table-row';
    } else {
        detailRow.style.display = 'none';
    }
}

function confirmDelete(id) {
    Swal.fire({
        title: 'Konfirmasi',
        text: 'Apakah Anda yakin ingin menghapus data sampah diserahkan ini?',
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
}
.btn-primary:hover {
    background-color: #16295e !important;
}
.table th {
    vertical-align: middle;
}
.table td {
    font-size: 0.85rem;
}
.btn-sm {
    padding: 0.25rem 0.4rem;
    font-size: 0.7rem;
}
</style>
@endpush
