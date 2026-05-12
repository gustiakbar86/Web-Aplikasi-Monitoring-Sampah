@extends('superAdmin.layout')

@section('title', 'Laporan Tahunan')

@section('content')
<div class="container px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Laporan Tahunan</h4>
        <div>
            <form action="{{ route('superadmin.laporan.tahunan') }}" method="GET" class="d-flex align-items-center">
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
        <strong>Periode:</strong> Tahun {{ $year }}
    </div>

    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0">Ringkasan Bulanan - Sampah Terkelola</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tableTerkelola" class="table table-bordered table-striped">
                    <thead class="bg-warning">
                        <tr>
                            <th>Bulan</th>
                            <th>Sampah (kg)</th>
                            <th>LB3 (kg)</th>
                            <th>Total (kg)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalSampah = 0;
                            $totalLB3 = 0;
                            $totalAll = 0;
                        @endphp
                        @for($m = 1; $m <= 12; $m++)
                        @php
                            $bulanIndonesia = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                            $monthName = $bulanIndonesia[$m];
                            $sampah = $monthlyData[$m]['terkelola']['sampah'];
                            $lb3 = $monthlyData[$m]['terkelola']['lb3'];
                            $total = $monthlyData[$m]['terkelola']['total'];
                            $totalSampah += $sampah;
                            $totalLB3 += $lb3;
                            $totalAll += $total;
                        @endphp
                        <tr>
                            <td>{{ $monthName }}</td>
                            <td>{{ number_format($sampah, 2) }}</td>
                            <td>{{ number_format($lb3, 2) }}</td>
                            <td>{{ number_format($total, 2) }}</td>
                        </tr>
                        @endfor
                    </tbody>
                    <tfoot>
                        <tr class="bg-light fw-bold">
                            <td>Total Tahun {{ $year }}</td>
                            <td>{{ number_format($totalSampah, 2) }}</td>
                            <td>{{ number_format($totalLB3, 2) }}</td>
                            <td>{{ number_format($totalAll, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0">Ringkasan Bulanan - Sampah Diserahkan</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tableDiserahkan" class="table table-bordered table-striped">
                    <thead class="bg-warning">
                        <tr>
                            <th>Bulan</th>
                            <th>Sampah (kg)</th>
                            <th>LB3 (kg)</th>
                            <th>Total (kg)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalDiserahkanSampah = 0;
                            $totalDiserahkanLB3 = 0;
                            $totalDiserahkanAll = 0;
                        @endphp
                        @for($m = 1; $m <= 12; $m++)
                        @php
                            $bulanIndonesia = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                            $monthName = $bulanIndonesia[$m];
                            $sampah = $monthlyData[$m]['diserahkan']['sampah'];
                            $lb3 = $monthlyData[$m]['diserahkan']['lb3'];
                            $total = $monthlyData[$m]['diserahkan']['total'];
                            $totalDiserahkanSampah += $sampah;
                            $totalDiserahkanLB3 += $lb3;
                            $totalDiserahkanAll += $total;
                        @endphp
                        <tr>
                            <td>{{ $monthName }}</td>
                            <td>{{ number_format($sampah, 2) }}</td>
                            <td>{{ number_format($lb3, 2) }}</td>
                            <td>{{ number_format($total, 2) }}</td>
                        </tr>
                        @endfor
                    </tbody>
                    <tfoot>
                        <tr class="bg-light fw-bold">
                            <td>Total Tahun {{ $year }}</td>
                            <td>{{ number_format($totalDiserahkanSampah, 2) }}</td>
                            <td>{{ number_format($totalDiserahkanLB3, 2) }}</td>
                            <td>{{ number_format($totalDiserahkanAll, 2) }}</td>
                        </tr>
                    </tfoot>
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
            pageLength: 12,
            order: [],
            searching: false,
            paging: false,
            info: false
        });

        // DataTable untuk Sampah Diserahkan
        $('#tableDiserahkan').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json'
            },
            pageLength: 12,
            order: [],
            searching: false,
            paging: false,
            info: false
        });
    });
</script>
@endpush
