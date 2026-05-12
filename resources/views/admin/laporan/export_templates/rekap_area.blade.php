<table>
    {{-- Title Section --}}
    <tr>
        <td colspan="27" style="font-weight: bold; font-size: 12pt; text-align: left;">Rekap Data Pengelolaan Sampah</td>
    </tr>
    <tr>
        <td colspan="27" style="font-weight: bold; font-size: 11pt; text-align: left;">Berdasarkan Per Lokasi Area</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Periode</td>
        <td colspan="2" style="background-color: #FFFF00; font-weight: bold;">JULI {{ $tahun }}</td>
        <td style="font-weight: bold; text-align: center;">-</td>
        <td colspan="2" style="background-color: #FFFF00; font-weight: bold;">JUNI {{ $tahun + 1 }}</td>
        <td colspan="21"></td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Perusahaan</td>
        <td colspan="26" style="background-color: #92D050; font-weight: bold;">PT PELINDO DAYA SEJAHTERA</td>
    </tr>
    <tr><td colspan="27"></td></tr>

    {{-- Header Row 1: Area Names --}}
    <tr style="background-color: #FFC000; font-weight: bold; text-align: center; vertical-align: middle;">
        <td rowspan="2" style="border: 1px solid black;">No</td>
        <td rowspan="2" style="border: 1px solid black;">Bulan</td>
        <td rowspan="2" style="border: 1px solid black;">Tahun</td>
        @foreach($lokasis as $id => $nama)
        <td colspan="4" style="border: 1px solid black;">{{ $nama }} (ton/bulan)</td>
        @endforeach
    </tr>
    
    {{-- Header Row 2: Column Types --}}
    <tr style="background-color: #FFC000; font-weight: bold; text-align: center; vertical-align: middle;">
        @foreach($lokasis as $id => $nama)
        <td style="border: 1px solid black;">Jumlah Sampah Organik Terpilah</td>
        <td style="border: 1px solid black;">Jumlah Sampah Anorganik Terpilah</td>
        <td style="border: 1px solid black;">Jumlah Sampah Lainnya dan/atau Residu</td>
        <td style="border: 1px solid black;">Jumlah Timbulan Sampah</td>
        @endforeach
    </tr>

    {{-- Data Rows (Yellow) --}}
    @foreach($rekapData as $index => $row)
    <tr style="background-color: #FFFF99;">
        <td style="border: 1px solid black; text-align: center;">{{ $index + 1 }}</td>
        <td style="border: 1px solid black;">{{ $row['bulan'] }}</td>
        <td style="border: 1px solid black; text-align: center;">{{ $row['tahun'] }}</td>
        @foreach($lokasis as $id => $nama)
        <td style="border: 1px solid black; text-align: right;">{{ number_format(($row["lokasi_{$id}_O"] ?? 0) / 1000, 2, ',', '.') }}</td>
        <td style="border: 1px solid black; text-align: right;">{{ number_format(($row["lokasi_{$id}_A"] ?? 0) / 1000, 2, ',', '.') }}</td>
        <td style="border: 1px solid black; text-align: right;">{{ number_format(($row["lokasi_{$id}_R"] ?? 0) / 1000, 2, ',', '.') }}</td>
        <td style="border: 1px solid black; text-align: right;">{{ number_format(($row["lokasi_{$id}_Total"] ?? 0) / 1000, 2, ',', '.') }}</td>
        @endforeach
    </tr>
    @endforeach

    {{-- Total Row (Blue) --}}
    <tr style="background-color: #00B0F0; font-weight: bold;">
        <td colspan="3" style="border: 1px solid black; text-align: center;">Total (ton/tahun)</td>
        @foreach($lokasis as $id => $nama)
        <td style="border: 1px solid black; text-align: right;">{{ number_format(($totals["lokasi_{$id}_O"] ?? 0) / 1000, 2, ',', '.') }}</td>
        <td style="border: 1px solid black; text-align: right;">{{ number_format(($totals["lokasi_{$id}_A"] ?? 0) / 1000, 2, ',', '.') }}</td>
        <td style="border: 1px solid black; text-align: right;">{{ number_format(($totals["lokasi_{$id}_R"] ?? 0) / 1000, 2, ',', '.') }}</td>
        <td style="border: 1px solid black; text-align: right;">{{ number_format(($totals["lokasi_{$id}_Total"] ?? 0) / 1000, 2, ',', '.') }}</td>
        @endforeach
    </tr>

    {{-- Empty rows --}}
    <tr><td colspan="27"></td></tr>
    <tr><td colspan="27"></td></tr>

    {{-- Signature Section --}}
    <tr>
        <td colspan="3"></td>
        <td colspan="10" style="font-weight: bold;">Mengetahui,</td>
        <td colspan="14" style="font-weight: bold;">(kab/kota), tgl-bulan-tahun</td>
    </tr>
    <tr>
        <td colspan="3"></td>
        <td colspan="10" style="font-weight: bold;">Jabatan Pimpinan</td>
        <td colspan="14" style="font-weight: bold;">Jabatan Penyusun</td>
    </tr>
    <tr><td colspan="27"></td></tr>
    <tr>
        <td colspan="3"></td>
        <td colspan="10" style="font-weight: bold;">ttd+cap</td>
        <td colspan="14" style="font-weight: bold;">ttd</td>
    </tr>
    <tr><td colspan="27"></td></tr>
    <tr>
        <td colspan="3"></td>
        <td colspan="10" style="font-weight: bold;">nama pimpinan</td>
        <td colspan="14" style="font-weight: bold;">nama penyusun</td>
    </tr>
    <tr>
        <td colspan="3"></td>
        <td colspan="10" style="font-weight: bold;">nopeg/nip</td>
        <td colspan="14" style="font-weight: bold;">nopeg/nip</td>
    </tr>
</table>