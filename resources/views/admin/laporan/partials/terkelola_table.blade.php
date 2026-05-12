{{-- resources/views/admin/laporan/partials/terkelola_table.blade.php --}}
<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead class="bg-primary text-white">
            <tr>
                <th>No</th>
                <th>Bulan</th>
                <th>Recycling (Kg)</th>
                <th>Reuse (Kg)</th>
                <th>Reduce (Kg)</th>
                <th>TPA (Kg)</th>
                <th>Total (Kg)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rekapData as $index => $data)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $data['bulan'] }}</td>
                <td class="text-end">{{ number_format($data['recycling'], 2) }}</td>
                <td class="text-end">{{ number_format($data['reuse'], 2) }}</td>
                <td class="text-end">{{ number_format($data['reduce'], 2) }}</td>
                <td class="text-end">{{ number_format($data['tpa'], 2) }}</td>
                <td class="text-end">{{ number_format($data['recycling'] + $data['reuse'] + $data['reduce'] + $data['tpa'], 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot class="bg-light">
            <tr>
                <th colspan="2">Total</th>
                <th class="text-end">{{ number_format($totals['total_recycling'], 2) }}</th>
                <th class="text-end">{{ number_format($totals['total_reuse'], 2) }}</th>
                <th class="text-end">{{ number_format($totals['total_reduce'], 2) }}</th>
                <th class="text-end">{{ number_format($totals['total_tpa'], 2) }}</th>
                <th class="text-end">{{ number_format($totals['total_recycling'] + $totals['total_reuse'] + $totals['total_reduce'] + $totals['total_tpa'], 2) }}</th>
            </tr>
        </tfoot>
    </table>
</div>