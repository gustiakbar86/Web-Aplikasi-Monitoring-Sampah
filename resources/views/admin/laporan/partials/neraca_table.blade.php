{{-- resources/views/admin/laporan/partials/neraca_table.blade.php --}}
<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead class="bg-primary text-white">
            <tr>
                <th>No</th>
                <th>Bulan</th>
                <th>Sampah Diserahkan (Kg)</th>
                <th>Sampah Terkelola (Kg)</th>
                <th>Persentase Terkelola</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rekapData as $index => $data)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $data['bulan'] }}</td>
                <td class="text-end">{{ number_format($data['diserahkan'], 2) }}</td>
                <td class="text-end">{{ number_format($data['terkelola'], 2) }}</td>
                <td class="text-end">
                    @if($data['diserahkan'] > 0)
                        {{ number_format(($data['terkelola'] / $data['diserahkan']) * 100, 2) }}%
                    @else
                        0%
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot class="bg-light">
            <tr>
                <th colspan="2">Total</th>
                <th class="text-end">{{ number_format($totals['total_diserahkan'], 2) }}</th>
                <th class="text-end">{{ number_format($totals['total_terkelola'], 2) }}</th>
                <th class="text-end">
                    @if($totals['total_diserahkan'] > 0)
                        {{ number_format(($totals['total_terkelola'] / $totals['total_diserahkan']) * 100, 2) }}%
                    @else
                        0%
                    @endif
                </th>
            </tr>
        </tfoot>
    </table>
</div>