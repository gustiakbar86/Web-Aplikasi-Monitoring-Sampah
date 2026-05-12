<table>
    <thead>
        <tr>
            <th rowspan="2">No</th>
            <th rowspan="2">Bulan</th>
            <th colspan="4">Timbulan Sampah (Kg)</th>
            <th colspan="4">Terkelola (Internal) (Kg)</th>
            <th colspan="4">Tidak Terkelola (Diserahkan) (Kg)</th>
        </tr>
        <tr>
            <th>Organik</th>
            <th>Anorganik</th>
            <th>Residu</th>
            <th>Total</th>
            <th>Organik</th>
            <th>Anorganik</th>
            <th>Residu</th>
            <th>Total</th>
            <th>Organik</th>
            <th>Anorganik</th>
            <th>Residu</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rekapData as $index => $row)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $row['bulan'] }}</td>
            <td>{{ $row['timbulan_organik'] }}</td>
            <td>{{ $row['timbulan_anorganik'] }}</td>
            <td>{{ $row['timbulan_residu'] }}</td>
            <td>{{ $row['total_timbulan'] }}</td>
            <td>{{ $row['terkelola_organik'] }}</td>
            <td>{{ $row['terkelola_anorganik'] }}</td>
            <td>{{ $row['terkelola_residu'] }}</td>
            <td>{{ $row['total_terkelola'] }}</td>
            <td>{{ $row['diserahkan_organik'] }}</td>
            <td>{{ $row['diserahkan_anorganik'] }}</td>
            <td>{{ $row['diserahkan_residu'] }}</td>
            <td>{{ $row['total_diserahkan'] }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2">Total:</th>
            <th>{{ $totals['timbulan_organik'] ?? 0 }}</th>
            <th>{{ $totals['timbulan_anorganik'] ?? 0 }}</th>
            <th>{{ $totals['timbulan_residu'] ?? 0 }}</th>
            <th>{{ $totals['total_timbulan'] ?? 0 }}</th>
            <th>{{ $totals['terkelola_organik'] ?? 0 }}</th>
            <th>{{ $totals['terkelola_anorganik'] ?? 0 }}</th>
            <th>{{ $totals['terkelola_residu'] ?? 0 }}</th>
            <th>{{ $totals['total_terkelola'] ?? 0 }}</th>
            <th>{{ $totals['diserahkan_organik'] ?? 0 }}</th>
            <th>{{ $totals['diserahkan_anorganik'] ?? 0 }}</th>
            <th>{{ $totals['diserahkan_residu'] ?? 0 }}</th>
            <th>{{ $totals['total_diserahkan'] ?? 0 }}</th>
        </tr>
    </tfoot>
</table>