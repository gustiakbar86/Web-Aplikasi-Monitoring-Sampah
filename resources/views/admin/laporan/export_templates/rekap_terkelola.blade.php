<table>
    {{-- Title Section --}}
    <tr>
        <td colspan="6" style="font-weight: bold; font-size: 12pt; text-align: left;">Rekap Jumlah Sampah Terkelola</td>
    </tr>
    <tr>
        <td colspan="6" style="font-weight: bold; font-size: 11pt; text-align: left;">Berdasarkan Jenis Sampah Terpilah</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Periode</td>
        <td colspan="2" style="background-color: #FFFF00; font-weight: bold;">JULI {{ $tahun }}</td>
        <td style="font-weight: bold; text-align: center;">-</td>
        <td colspan="2" style="background-color: #FFFF00; font-weight: bold;">JUNI {{ $tahun + 1 }}</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Perusahaan</td>
        <td colspan="5" style="background-color: #92D050; font-weight: bold;">PT PELINDO DAYA SEJAHTERA</td>
    </tr>
    <tr><td colspan="6"></td></tr>

    {{-- Header Table --}}
    <tr style="background-color: #FFC000; font-weight: bold; text-align: center; vertical-align: middle;">
        <td rowspan="2" style="border: 1px solid black;">No</td>
        <td rowspan="2" style="border: 1px solid black;">Bulan</td>
        <td rowspan="2" style="border: 1px solid black;">Tahun</td>
        <td colspan="3" style="border: 1px solid black;">Sampah Terkelola</td>
    </tr>
    <tr style="background-color: #FFC000; font-weight: bold; text-align: center; vertical-align: middle;">
        <td style="border: 1px solid black;">Jumlah<br>Sampah Organik Terpilah<br>(ton/bulan)</td>
        <td style="border: 1px solid black;">Jumlah<br>Sampah Anorganik Terpilah<br>(ton/bulan)</td>
        <td style="border: 1px solid black;">Jumlah<br>Sampah Lainnya dan/atau Residu<br>(ton/bulan)</td>
    </tr>

    {{-- Data Rows (Yellow rows) --}}
    @foreach($rekapData as $index => $row)
    <tr style="background-color: #FFFF99;">
        <td style="border: 1px solid black; text-align: center;">{{ $index + 1 }}</td>
        <td style="border: 1px solid black;">{{ $row['bulan'] }}</td>
        <td style="border: 1px solid black; text-align: center;">{{ $row['tahun'] }}</td>
        <td style="border: 1px solid black; text-align: right;">{{ number_format($row['organik'] / 1000, 2, ',', '.') }}</td>
        <td style="border: 1px solid black; text-align: right;">{{ number_format($row['anorganik'] / 1000, 2, ',', '.') }}</td>
        <td style="border: 1px solid black; text-align: right;">{{ number_format($row['residu_dll'] / 1000, 2, ',', '.') }}</td>
    </tr>
    @endforeach

    {{-- Total Row (Blue) --}}
    <tr style="background-color: #00B0F0; font-weight: bold;">
        <td colspan="3" style="border: 1px solid black; text-align: center;">Total (ton/tahun)</td>
        <td style="border: 1px solid black; text-align: right;">{{ number_format($totals['organik'] / 1000, 2, ',', '.') }}</td>
        <td style="border: 1px solid black; text-align: right;">{{ number_format($totals['anorganik'] / 1000, 2, ',', '.') }}</td>
        <td style="border: 1px solid black; text-align: right;">{{ number_format($totals['residu_dll'] / 1000, 2, ',', '.') }}</td>
    </tr>

    {{-- Empty rows --}}
    <tr><td colspan="6"></td></tr>
    <tr><td colspan="6"></td></tr>

    {{-- Signature Section --}}
    <tr>
        <td colspan="2"></td>
        <td colspan="2" style="font-weight: bold;">Mengetahui,</td>
        <td colspan="2" style="font-weight: bold;">(kab/kota), tgl-bulan-tahun</td>
    </tr>
    <tr>
        <td colspan="2"></td>
        <td colspan="2" style="font-weight: bold;">Jabatan Pimpinan</td>
        <td colspan="2" style="font-weight: bold;">Jabatan Penyusun</td>
    </tr>
    <tr><td colspan="6"></td></tr>
    <tr>
        <td colspan="2"></td>
        <td colspan="2" style="font-weight: bold;">ttd+cap</td>
        <td colspan="2" style="font-weight: bold;">ttd</td>
    </tr>
    <tr><td colspan="6"></td></tr>
    <tr>
        <td colspan="2"></td>
        <td colspan="2" style="font-weight: bold;">nama pimpinan</td>
        <td colspan="2" style="font-weight: bold;">nama penyusun</td>
    </tr>
    <tr>
        <td colspan="2"></td>
        <td colspan="2" style="font-weight: bold;">nopeg/nip</td>
        <td colspan="2" style="font-weight: bold;">nopeg/nip</td>
    </tr>
</table>