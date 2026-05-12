{{-- resources/views/admin/laporan/partials/area_table.blade.php --}}
<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead class="bg-primary text-white">
            <tr>
                <th>No</th>
                <th>Bulan</th>
                @foreach($lokasis as $lokasi)
                <th>{{ $lokasi->nama_lokasi }} (Kg)</th>
                @endforeach
                <th>Total (Kg)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rekapData as $index => $data)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $data['bulan'] }}</td>
                @foreach($lokasis as $lokasi)
                <td class="text-end">{{ number_format($data[$lokasi->id], 2) }}</td>
                @endforeach
                <td class="text-end">{{ number_format(array_sum(array_intersect_key($data, array_flip($lokasis->pluck('id')->toArray()))), 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="{{ count($lokasis) + 3 }}" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot class="bg-light">
            <tr>
                <th colspan="2">Total</th>
                @foreach($lokasis as $lokasi)
                <th class="text-end">{{ number_format($totals[$lokasi->id], 2) }}</th>
                @endforeach
                <th class="text-end">{{ number_format(array_sum($totals), 2) }}</th>
            </tr>
        </tfoot>
    </table>
</div>