@extends('superAdmin.layout')

@section('title', 'Lihat Semua Data')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div style="background-color: #1E3F8C" class="p-4 rounded-top">
                <h4 class="text-white mb-0">Semua Data Sampah > Sampah Terkelola</h4>
            </div>
            <div class="bg-white p-4 rounded-bottom shadow">
                <!-- Toggle Button untuk Kolom Alasan Edit -->
                <div class="mb-3">
                    <button type="button" class="btn btn-sm btn-secondary" id="toggleAlasanEdit">
                        <i class="fas fa-eye"></i> Tampilkan Kolom Alasan Edit
                    </button>
                </div>

                <div class="table-responsive">
                    <table id="sampah-terkelola-table" class="table table-striped table-bordered w-100">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">User</th>
                                <th class="text-center">
                                    Instansi
                                    <br>
                                    <select id="instansiFilter" class="form-select form-select-sm mt-1" style="background-color: white; color: black;">
                                        <option value="">Semua</option>
                                        @foreach($instansis as $inst)
                                            <option value="{{ $inst->nama_instansi }}">{{ $inst->nama_instansi }}</option>
                                        @endforeach
                                    </select>
                                </th>
                                <th class="text-center">Jenis</th>
                                <th class="text-center">Sumber</th>
                                <th class="text-center">Berat (kg)</th>
                                <th class="text-center">Foto</th>
                                <th class="text-center">Tanggal</th>
                                <th class="text-center alasan-edit-col" style="display: none;">Alasan Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sampahTerkelolas as $index => $sampah)
                            <tr>
                                <td class="text-center">{{ $sampahTerkelolas->firstItem() + $index }}</td>
                                <td>{{ $sampah->user->name ?? '-' }}</td>
                                <td>{{ $sampah->user->instansi->nama_instansi ?? '-' }}</td>
                                <td>{{ $sampah->jenis->nama_jenis ?? '-' }}</td>
                                <td>{{ $sampah->lokasiAsal->nama_lokasi ?? '-' }}</td>
                                <td class="text-center">{{ number_format($sampah->jumlah_berat, 2) }}</td>
                                <td class="text-center">
                                    @if($sampah->foto_kelola)
                                        <a href="{{ asset($sampah->foto_kelola) }}" target="_blank" class="btn btn-sm btn-light">
                                            <i class="fas fa-image"></i> Lihat
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($sampah->tgl)->format('d-m-Y') }}</td>
                                <td class="alasan-edit-col" style="display: none;">
                                    @if($sampah->alasan_edit)
                                        <small>{{ $sampah->alasan_edit }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada data sampah terkelola</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $sampahTerkelolas->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#sampah-terkelola-table').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
        pagingType: "simple_numbers",
        paging: true,
        info: false,
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
        },
        columnDefs: [
            { targets: 8, visible: false } // Hide Alasan Edit column by default
        ]
    });

    // Filter by Instansi
    $('#instansiFilter').on('change', function() {
        table.column(2).search(this.value).draw();
    });

    // Toggle Alasan Edit Column
    var alasanEditVisible = localStorage.getItem('showAlasanEditTerkelola') === 'true';
    
    function updateToggleButton() {
        if (alasanEditVisible) {
            $('#toggleAlasanEdit').html('<i class="fas fa-eye-slash"></i> Sembunyikan Kolom Alasan Edit');
            $('.alasan-edit-col').show();
            table.column(8).visible(true);
        } else {
            $('#toggleAlasanEdit').html('<i class="fas fa-eye"></i> Tampilkan Kolom Alasan Edit');
            $('.alasan-edit-col').hide();
            table.column(8).visible(false);
        }
    }

    // Set initial state
    updateToggleButton();

    // Toggle button click
    $('#toggleAlasanEdit').on('click', function() {
        alasanEditVisible = !alasanEditVisible;
        localStorage.setItem('showAlasanEditTerkelola', alasanEditVisible);
        updateToggleButton();
    });
});
</script>
@endpush

@endsection