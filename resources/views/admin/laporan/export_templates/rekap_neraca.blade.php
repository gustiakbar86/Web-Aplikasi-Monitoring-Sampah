<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse; width: 100%;">
    <!-- Header: Judul, Periode, Perusahaan -->
    <tr>
        <td colspan="10" style="font-weight: bold; font-size: 14pt; text-align: left;">Rekap Neraca Sampah</td>
    </tr>
    <tr>
        <td colspan="2" style="font-weight: bold;">Periode</td>
        <td colspan="2" style="background-color: #FFFF00; font-weight: bold;">JULI {{ $tahun ?? date('Y') }}</td>
        <td style="font-weight: bold; text-align: center;">-</td>
        <td colspan="5" style="background-color: #FFFF00; font-weight: bold;">JUNI {{ ($tahun ?? date('Y')) + 1 }}</td>
    </tr>
    <tr>
        <td colspan="2" style="font-weight: bold;">Perusahaan</td>
        <td colspan="8" style="background-color: #92D050; font-weight: bold;">PT PELINDO DAYA SEJAHTERA</td>
    </tr>
    <tr><td colspan="10"></td></tr>
    
    <!-- Header Tabel -->
    <tr style="background-color: #FFC000; font-weight: bold; text-align: center;">
        <th rowspan="2" style="vertical-align: middle;">No</th>
        <th rowspan="2" style="vertical-align: middle;">Sumber Sampah</th>
        <th rowspan="2" style="vertical-align: middle;">Jumlah<br>Timbulan<br>Sampah<br>(ton/tahun)</th>
        <th colspan="7" style="text-align: center;">Penanganan Sampah (ton/tahun)</th>
    </tr>
    <tr style="background-color: #FFC000; font-weight: bold; text-align: center;">
        <th>Jumlah<br>Sampah Organik</th>
        <th>Jumlah<br>Sampah Anorganik<br>Terpilah</th>
        <th>Total<br>Sampah Terkelola</th>
        <th>Prosentase<br>Sampah Terkelola</th>
        <th>Jumlah<br>Sampah Lainnya<br>dan/atau residu</th>
        <th>Total<br>Sampah Lainnya<br>dan/atau residu</th>
        <th>Prosentase<br>Sampah Lainnya<br>dan/atau residu</th>
    </tr>
    
    <!-- Data Rows -->
    @foreach($rekapData as $index => $row)
    <tr>
        <td style="text-align: center;">{{ $index + 1 }}</td>
        <td>{{ $row['lokasi'] }}</td>
        <td style="text-align: right;">{{ number_format($row['timbulan'] / 1000, 2, ',', '.') }}</td>
        <td style="text-align: right;">{{ number_format($row['terkelola_organik'] / 1000, 2, ',', '.') }}</td>
        <td style="text-align: right;">{{ number_format($row['terkelola_anorganik'] / 1000, 2, ',', '.') }}</td>
        <td style="text-align: right;">{{ number_format($row['total_terkelola'] / 1000, 2, ',', '.') }}</td>
        <td style="text-align: right;">{{ $row['timbulan'] > 0 ? number_format(($row['total_terkelola'] / $row['timbulan']) * 100, 2, ',', '.') . '%' : '0,00%' }}</td>
        <td style="text-align: right;">{{ number_format($row['residu_dll'] / 1000, 2, ',', '.') }}</td>
        <td style="text-align: right;">{{ number_format($row['residu_dll'] / 1000, 2, ',', '.') }}</td>
        <td style="text-align: right;">{{ $row['timbulan'] > 0 ? number_format(($row['residu_dll'] / $row['timbulan']) * 100, 2, ',', '.') . '%' : '0,00%' }}</td>
    </tr>
    @endforeach
    
    <!-- Total Row -->
    <tr style="background-color: #00B0F0; font-weight: bold;">
        <td colspan="2" style="text-align: center;">Total (ton/tahun)</td>
        <td style="text-align: right;">{{ number_format($totals['timbulan'] / 1000, 2, ',', '.') }}</td>
        <td style="text-align: right;">{{ number_format($totals['terkelola_organik'] / 1000, 2, ',', '.') }}</td>
        <td style="text-align: right;">{{ number_format($totals['terkelola_anorganik'] / 1000, 2, ',', '.') }}</td>
        <td style="text-align: right;">{{ number_format($totals['total_terkelola'] / 1000, 2, ',', '.') }}</td>
        <td style="text-align: right;">{{ $totals['timbulan'] > 0 ? number_format(($totals['total_terkelola'] / $totals['timbulan']) * 100, 2, ',', '.') . '%' : '0,00%' }}</td>
        <td style="text-align: right;">{{ number_format($totals['residu_dll'] / 1000, 2, ',', '.') }}</td>
        <td style="text-align: right;">{{ number_format($totals['residu_dll'] / 1000, 2, ',', '.') }}</td>
        <td style="text-align: right;">{{ $totals['timbulan'] > 0 ? number_format(($totals['residu_dll'] / $totals['timbulan']) * 100, 2, ',', '.') . '%' : '0,00%' }}</td>
    </tr>
    
    <!-- Footer: Tanda Tangan -->
    <tr><td colspan="10"></td></tr>
    <tr>
        <td colspan="2"></td>
        <td colspan="3" style="font-weight: bold;">Mengetahui,</td>
        <td colspan="5" style="font-weight: bold;">(kab/kota), tgl-bulan-tahun</td>
    </tr>
    <tr>
        <td colspan="2"></td>
        <td colspan="3" style="font-weight: bold;">Jabatan Pimpinan</td>
        <td colspan="5" style="font-weight: bold;">Jabatan Penyusun</td>
    </tr>
    <tr><td colspan="10"></td></tr>
    <tr>
        <td colspan="2"></td>
        <td colspan="3">ttd+cap</td>
        <td colspan="5">ttd</td>
    </tr>
    <tr><td colspan="10"></td></tr>
    <tr>
        <td colspan="2"></td>
        <td colspan="3">nama pimpinan</td>
        <td colspan="5">nama penyusun</td>
    </tr>
    <tr>
        <td colspan="2"></td>
        <td colspan="3">nopeg/nip</td>
        <td colspan="5">nopeg/nip</td>
    </tr>
    <tr><td colspan="10"></td></tr>
    <tr>
        <td colspan="10" style="background-color: #FFFF00; font-size: 8pt;">
            <strong>…</strong> : Tahun dan nama perusahaan mohon diisi
        </td>
    </tr>
</table>