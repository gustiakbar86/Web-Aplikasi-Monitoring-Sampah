@extends('petugas.layout')

@section('title', 'Lihat Riwayat Inputan - Sampah Terkelola')

@push('styles')
<style>
    .bg-primary {
        background-color: #1E3F8C !important;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th {
        background-color: #1E3F8C;
        color: white;
        padding: 12px;
        text-align: left;
        font-weight: 600;
        border: 0.5px solid #ddd;
    }

    .table td {
        background-color: white;
        padding: 12px;
        border: 0.5px solid #ddd;
        font-size: 0.85rem;
    }

    .table tr:hover td {
        background-color: #f5f5f5;
    }

    .btn-info {
        background-color: #0dcaf0 !important;
        border: none;
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
    }

    .btn-info:hover {
        background-color: #0bb5da !important;
    }

    .btn-warning {
        background-color: #ffc107 !important;
        border: none;
        color: black;
        padding: 5px 10px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
    }

    .btn-warning:hover {
        background-color: #e0a800 !important;
    }

    .text-center {
        text-align: center;
    }

    .table-responsive {
        overflow-x: auto;
    }

    /* Adjustments for mobile */
    @media (max-width: 768px) {
        .table {
            font-size: 0.8rem;
        }
        
        .table th,
        .table td {
            padding: 5px;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row mb-4">
        <div class="col-md-10 mx-auto">
            <div class="bg-primary text-white p-3 rounded mb-4">
                <h3 class="mb-0">Lihat Riwayat Inputan - Sampah Terkelola</h3>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="sampahTerkelolaTable" class="table table-bordered table-striped">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th width="4%" class="text-center">No</th>
                                    <th width="10%" class="text-center">Foto</th>
                                    <th width="10%" class="text-center">Tanggal</th>
                                    <th width="14%">Lokasi Asal</th>
                                    <th width="15%">Jenis Sampah</th>
                                    <th width="9%" class="text-center">Berat (Kg)</th>
                                    <th width="14%">Alasan Edit</th>
                                    <th width="10%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($sampahTerkelolas as $index => $sampah)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>

                                    <td class="text-center">
                                        @if ($sampah->foto_kelola)
                                            <a href="{{ asset($sampah->foto_kelola) }}" target="_blank">
                                                <i class="fas fa-image"></i> Foto
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        {{ \Carbon\Carbon::parse($sampah->tgl)->format('d/m/Y') }}
                                    </td>

                                    <td>{{ $sampah->lokasiAsal->nama_lokasi ?? '-' }}</td>
                                    <td>{{ $sampah->jenis->kategori_jenis ?? '-' }} - {{ $sampah->jenis->nama_jenis ?? '' }}</td>

                                    <td class="text-center">
                                        {{ number_format($sampah->jumlah_berat, 2) }}
                                    </td>

                                    <td>
                                        @if ($sampah->alasan_edit)
                                            <small class="text-muted">{{ $sampah->alasan_edit }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <div class="d-inline-flex gap-1">

                                            <a href="{{ route('petugas.edit-sampah-terkelola', $sampah->id) }}"
                                               class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                        </div>
                                    </td>
                                </tr>

                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    let table = $('#sampahTerkelolaTable').DataTable({
        destroy: true,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        },
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
        ordering: true,
        searching: true,
        paging: true,
        info: true,
        columnDefs: [
            { orderable: false, targets: [1, 6, 7] } // Foto, Alasan Edit, dan Aksi tidak bisa di-sort
        ]
    });
});
</script>
@endpush
