{{-- resources/views/superadmin/laporan/partials/daily_table.blade.php --}}
<div class="daily-tables">
    @foreach($rekapData as $bulan => $monthData)
    <div class="month-section mb-4">
        <h5>{{ $bulan }}</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="bg-primary text-white">
                    <tr>
                        <th rowspan="2">No</th>
                        <th rowspan="2">Tanggal</th>
                        <th colspan="2">Neraca</th>
                        @foreach($lokasis as $lokasi)
                        <th rowspan="2">{{ $lokasi->nama_lokasi }} (Kg)</th>
                        @endforeach
                    </tr>
                    <tr>
                        <th>Diserahkan (Kg)</th>
                        <th>Terkelola (Kg)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($monthData['data'] as $index => $data)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $data['tanggal'] }}</td>
                        <td class="text-end">{{ number_format($data['diserahkan'], 2) }}</td>
                        <td class="text-end">{{ number_format($data['terkelola'], 2) }}</td>
                        @foreach($lokasis as $lokasi)
                        <td class="text-end">{{ number_format($data['area_' . $lokasi->id], 2) }}</td>
                        @endforeach
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ count($lokasis) + 4 }}" class="text-center">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-light">
                    <tr>
                        <th colspan="2">Total</th>
                        <th class="text-end">{{ number_format($monthData['totals_neraca']['total_diserahkan'], 2) }}</th>
                        <th class="text-end">{{ number_format($monthData['totals_neraca']['total_terkelola'], 2) }}</th>
                        @foreach($lokasis as $lokasi)
                        <th class="text-end">{{ number_format($monthData['totals_area'][$lokasi->id], 2) }}</th>
                        @endforeach
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @endforeach
</div>