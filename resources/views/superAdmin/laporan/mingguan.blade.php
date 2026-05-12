@extends('superAdmin.layout')

@section('title', 'Laporan Mingguan')

@section('content')
<div class="content-area">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Laporan Mingguan</h4>
        <div>
            <form action="{{ route('superadmin.laporan.mingguan') }}" method="GET" class="d-flex align-items-center">
                <label class="me-2 mb-0">Minggu:</label>
                <select name="week" class="form-select form-select-sm me-2" style="width: 100px;">
                    @for($w = 1; $w <= 5; $w++)
                    <option value="{{ $w }}" {{ $week == $w ? 'selected' : '' }}>Minggu {{ $w }}</option>
                    @endfor
                </select>
                <label class="me-2 mb-0">Bulan:</label>
                <select name="month" class="form-select form-select-sm me-2" style="width: 120px;">
                    @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create(null, $m, 1)->format('F') }}</option>
                    @endfor
                </select>
                <label class="me-2 mb-0">Tahun:</label>
                <select name="year" class="form-select form-select-sm me-2" style="width: 100px;">
                    @for($y = date('Y'); $y >= 2020; $y--)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
                <button type="submit" class="btn btn-sm btn-primary">Tampilkan</button>
            </form>
        </div>
    </div>

    <div class="alert alert-info">
        <strong>Periode:</strong> {{ $weekStart->format('d F Y') }} - {{ $weekEnd->format('d F Y') }}
    </div>

    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0">Sampah Terkelola</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tableTerkelola" class="table table-bordered table-striped">
                    <thead class="bg-warning">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Lokasi Asal</th>
                            <th>Jenis Sampah</th>
                            <th>Jumlah (kg)</th>
                            <th>Petugas Input</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sampahTerkelolas as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tgl)->format('d/m/Y') }}</td>
                            <td>{{ $item->lokasiAsal->nama_lokasi ?? '-' }}</td>
                            <td>{{ $item->jenis->nama_jenis ?? '-' }}</td>
                            <td>{{ number_format($item->jumlah_berat, 2) }}</td>
                            <td>{{ $item->user->name ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data sampah terkelola</td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($sampahTerkelolas->count() > 0)
                    <tfoot>
                        <tr class="bg-light fw-bold">
                            <td colspan="4" class="text-end">Total:</td>
                            <td>{{ number_format($sampahTerkelolas->sum('jumlah_berat'), 2) }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0">Sampah Diserahkan</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tableDiserahkan" class="table table-bordered table-striped">
                    <thead class="bg-warning">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Lokasi Asal</th>
                            <th>Jenis Sampah</th>
                            <th>Tujuan</th>
                            <th>Jumlah (kg)</th>
                            <th>Petugas Input</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sampahDiserahkans as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tgl)->format('d/m/Y') }}</td>
                            <td>{{ $item->lokasiAsal->nama_lokasi ?? '-' }}</td>
                            <td>{{ $item->jenis->nama_jenis ?? '-' }}</td>
                            <td>{{ $item->tujuanSampah->nama_tujuan ?? '-' }}</td>
                            <td>{{ number_format($item->jumlah_berat, 2) }}</td>
                            <td>{{ $item->user->name ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data sampah diserahkan</td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($sampahDiserahkans->count() > 0)
                    <tfoot>
                        <tr class="bg-light fw-bold">
                            <td colspan="5" class="text-end">Total:</td>
                            <td>{{ number_format($sampahDiserahkans->sum('jumlah_berat'), 2) }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // DataTable untuk Sampah Terkelola
        $('#tableTerkelola').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json'
            },
            pageLength: 25,
            order: [[1, 'desc']], // Sort by tanggal descending
            columnDefs: [
                { orderable: false, targets: 0 } // Disable sorting on No column
            ]
        });

        // DataTable untuk Sampah Diserahkan
        $('#tableDiserahkan').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json'
            },
            pageLength: 25,
            order: [[1, 'desc']], // Sort by tanggal descending
            columnDefs: [
                { orderable: false, targets: 0 } // Disable sorting on No column
            ]
        });
    });
</script>
@endpush
