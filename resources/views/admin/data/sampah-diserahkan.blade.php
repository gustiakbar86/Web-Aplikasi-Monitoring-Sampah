@extends('admin.layout')

@section('title', 'Lihat Data Sampah')

@section('content')
<div class="content-area">
    
    <div class="card">
        <div class="card-body" style="background-color: #1E3F8C; color: white;">
            <h5 class="mb-0">Lihat Data Sampah > Sampah Diserahkan</h5>
        </div>
        
        <div class="card-body">
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <div>
                    <a href="{{ route('admin.data.sampah-diserahkan.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-2"></i>Tambah Data
                    </a>
                </div>
                <div class="d-flex gap-2">
                    <input type="text" class="form-control form-control-sm" id="searchInput" placeholder="Cari data...">
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="sampahDiserahkanTable">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="text-center sortable" width="4%">No</th>
                            <th class="text-center sortable" width="12%">User</th>
                            <th class="text-center sortable" width="13%">Sumber</th>
                            <th class="text-center sortable" width="11%">Jenis</th>
                            <th class="text-center sortable" width="9%">Berat (Kg)</th>
                            <th class="text-center sortable" width="13%">Tujuan</th>
                            <th class="text-center" width="7%">Foto</th>
                            <th class="text-center sortable" width="10%">Tanggal</th>
                            <th class="text-center" width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sampahDiserahkans as $index => $sampah)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $sampah->user->instansi->nama_instansi ?? 'Unknown' }} - {{ $sampah->user->name ?? 'Unknown' }}</td>
                            <td>{{ $sampah->lokasiAsal->nama_lokasi ?? 'Unknown' }}</td>
                            <td>{{ $sampah->jenis->kategori_jenis ?? 'Unknown' }} - {{ $sampah->jenis->nama_jenis ?? 'Unknown' }}</td>
                            <td class="text-center">{{ number_format($sampah->jumlah_berat, 2) }}</td>
                            <td>{{ $sampah->tujuanSampah->nama_tujuan ?? 'Unknown' }}</td>
                            <td class="text-center">
                                @if($sampah->foto_diserahkan)
                                    <a href="{{ asset($sampah->foto_diserahkan) }}" target="_blank" class="btn btn-sm btn-light">
                                        <i class="fas fa-file-image"></i> Lihat Foto
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($sampah->tgl_diserahkan)->format('d-m-Y') }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.data.sampah-diserahkan.show', $sampah->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

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
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const table = $('#sampahDiserahkanTable').DataTable({
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

        // Apply jenis filter
        $('#jenisFilter').on('change', function() {
            table.column(4).search(this.value).draw();
        });
        
        // Apply instansi filter
        $('#instansiFilter').on('change', function() {
            table.column(2).search(this.value).draw();
        });

        // Apply search filter
        $('#searchInput').on('keyup', function() {
            table.search(this.value).draw();
        });
    });

    // Show alasan edit modal
    function showAlasenModal(id, alasan) {
        Swal.fire({
            title: 'Alasan Edit',
            html: alasan || '-',
            icon: 'info',
            confirmButtonText: 'Kembali'
        });
    }
</script>
@endpush
