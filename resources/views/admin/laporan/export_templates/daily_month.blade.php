<table>
    {{-- Header Section --}}
    <tr>
        <td style="font-weight: bold;">Perusahaan :</td>
        <td colspan="28" style="font-weight: bold;">PT PELINDO DAYA SEJAHTERA</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Tahun</td>
        <td colspan="28" style="font-weight: bold;">{{ $tahun }}</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">Bulan:</td>
        <td colspan="28" style="font-weight: bold;">{{ $bulan }}</td>
    </tr>

    {{-- Main Header Row 1: REKAMAN TIMBULAN SAMPAH for each area --}}
    <tr style="background-color: #FFA500; font-weight: bold; text-align: center; vertical-align: middle;">
        <td rowspan="3" style="border: 1px solid black; background-color: #D3D3D3;">No</td>
        <td rowspan="3" style="border: 1px solid black; background-color: #D3D3D3;">Tanggal</td>
        @foreach($lokasis as $id => $nama)
        <td colspan="4" style="border: 1px solid black;">REKAMAN TIMBULAN SAMPAH</td>
        @endforeach
        <td rowspan="3" style="border: 1px solid black; background-color: #FFFF00; writing-mode: vertical-rl; text-orientation: upright; font-size: 9pt;">TOTAL TIMBULAN SAMPAH DI SELURUH AREA</td>
        <td rowspan="3" style="border: 1px solid black; background-color: #D3D3D3;">Satuan</td>
    </tr>

    {{-- Header Row 2: (kg/hari) and Sumber sampah --}}
    <tr style="background-color: #FFA500; font-weight: bold; text-align: center; vertical-align: middle;">
        @foreach($lokasis as $id => $nama)
        <td colspan="4" style="border: 1px solid black; font-size: 9pt;">(kg/hari)<br>Sumber sampah</td>
        @endforeach
    </tr>

    {{-- Header Row 3: Area Names and Column Types --}}
    <tr style="background-color: #D2B48C; font-weight: bold; text-align: center; vertical-align: middle; font-size: 8pt;">
        @foreach($lokasis as $id => $nama)
        <td style="border: 1px solid black; background-color: #F5DEB3;">{{ $nama }}</td>
        <td colspan="3" style="border: 1px solid black; background-color: #FAEBD7;">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="border-right: 1px solid black; padding: 2px; text-align: center;">Sampah Organik Terpilah</td>
                    <td style="border-right: 1px solid black; padding: 2px; text-align: center;">Sampah Anorganik Terpilah</td>
                    <td style="padding: 2px; text-align: center;">Sampah Lainnya dan/atau Residu</td>
                </tr>
            </table>
        </td>
        @endforeach
    </tr>

    {{-- Data Rows (White/Light Yellow alternating) --}}
    @foreach($dailyData as $index => $row)
    <tr style="background-color: {{ $index % 2 == 0 ? '#FFFFFF' : '#FFFACD' }};">
        <td style="border: 1px solid black; text-align: center; font-size: 9pt;">{{ $index + 1 }}</td>
        <td style="border: 1px solid black; text-align: center; font-size: 9pt;">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
        @foreach($lokasis as $id => $nama)
        <td style="border: 1px solid black; text-align: right; font-size: 9pt;">{{ number_format($row["lokasi_{$id}_O"] ?? 0, 2, ',', '.') }}</td>
        <td style="border: 1px solid black; text-align: right; font-size: 9pt;">{{ number_format($row["lokasi_{$id}_A"] ?? 0, 2, ',', '.') }}</td>
        <td style="border: 1px solid black; text-align: right; font-size: 9pt;">{{ number_format($row["lokasi_{$id}_R"] ?? 0, 2, ',', '.') }}</td>
        <td style="border: 1px solid black; text-align: right; font-size: 9pt; background-color: #FFE4B5;">{{ number_format($row["lokasi_{$id}_Total"] ?? 0, 2, ',', '.') }}</td>
        @endforeach
        <td style="border: 1px solid black; text-align: right; background-color: #FFFF00; font-weight: bold; font-size: 9pt;">{{ number_format($row['total_harian'], 2, ',', '.') }}</td>
        <td style="border: 1px solid black; text-align: center; font-size: 8pt;">kg/hari</td>
    </tr>
    @endforeach

    {{-- Total Row 1 - Total/jenis (kg/bulan) --}}
    <tr style="background-color: #ADD8E6; font-weight: bold;">
        <td colspan="2" style="border: 1px solid black; text-align: left; font-size: 9pt;">Total/jenis (kg/bulan)</td>
        @foreach($lokasis as $id => $nama)
        <td style="border: 1px solid black; text-align: right; font-size: 9pt;">{{ number_format($totals["lokasi_{$id}_O"] ?? 0, 2, ',', '.') }}</td>
        <td style="border: 1px solid black; text-align: right; font-size: 9pt;">{{ number_format($totals["lokasi_{$id}_A"] ?? 0, 2, ',', '.') }}</td>
        <td style="border: 1px solid black; text-align: right; font-size: 9pt;">{{ number_format($totals["lokasi_{$id}_R"] ?? 0, 2, ',', '.') }}</td>
        <td style="border: 1px solid black; text-align: right; font-size: 9pt; background-color: #87CEEB;">{{ number_format($totals["lokasi_{$id}_Total"] ?? 0, 2, ',', '.') }}</td>
        @endforeach
        <td style="border: 1px solid black; text-align: right; background-color: #FFFF00; font-size: 9pt;">{{ number_format($totals['total_bulanan'], 2, ',', '.') }}</td>
        <td style="border: 1px solid black; text-align: center; font-size: 8pt;">kg/bulan</td>
    </tr>

    {{-- Total Row 2 - Total/jenis (ton/bulan) --}}
    <tr style="background-color: #F0E68C; font-weight: bold;">
        <td colspan="2" style="border: 1px solid black; text-align: left; font-size: 9pt;">Total/jenis (ton/bulan)</td>
        @foreach($lokasis as $id => $nama)
        <td style="border: 1px solid black; text-align: right; font-size: 9pt;">{{ number_format(($totals["lokasi_{$id}_O"] ?? 0) / 1000, 2, ',', '.') }}</td>
        <td style="border: 1px solid black; text-align: right; font-size: 9pt;">{{ number_format(($totals["lokasi_{$id}_A"] ?? 0) / 1000, 2, ',', '.') }}</td>
        <td style="border: 1px solid black; text-align: right; font-size: 9pt;">{{ number_format(($totals["lokasi_{$id}_R"] ?? 0) / 1000, 2, ',', '.') }}</td>
        <td style="border: 1px solid black; text-align: right; font-size: 9pt; background-color: #EEE8AA;">{{ number_format(($totals["lokasi_{$id}_Total"] ?? 0) / 1000, 2, ',', '.') }}</td>
        @endforeach
        <td style="border: 1px solid black; text-align: right; background-color: #FFFF00; font-size: 9pt;">{{ number_format($totals['total_bulanan'] / 1000, 2, ',', '.') }}</td>
        <td style="border: 1px solid black; text-align: center; font-size: 8pt;">ton/bulan</td>
    </tr>

    {{-- Total Row 3 - Rata-rata perhari (kg/hari) --}}
    @php
        $daysInMonth = count($dailyData);
    @endphp
    <tr style="background-color: #FFE4E1; font-weight: bold;">
        <td colspan="2" style="border: 1px solid black; text-align: left; font-size: 9pt;">Rata-rata perhari (kg/hari)</td>
        @foreach($lokasis as $id => $nama)
        <td style="border: 1px solid black; text-align: right; font-size: 9pt;">{{ number_format($daysInMonth > 0 ? ($totals["lokasi_{$id}_O"] ?? 0) / $daysInMonth : 0, 2, ',', '.') }}</td>
        <td style="border: 1px solid black; text-align: right; font-size: 9pt;">{{ number_format($daysInMonth > 0 ? ($totals["lokasi_{$id}_A"] ?? 0) / $daysInMonth : 0, 2, ',', '.') }}</td>
        <td style="border: 1px solid black; text-align: right; font-size: 9pt;">{{ number_format($daysInMonth > 0 ? ($totals["lokasi_{$id}_R"] ?? 0) / $daysInMonth : 0, 2, ',', '.') }}</td>
        <td style="border: 1px solid black; text-align: right; font-size: 9pt; background-color: #FFB6C1;">{{ number_format($daysInMonth > 0 ? ($totals["lokasi_{$id}_Total"] ?? 0) / $daysInMonth : 0, 2, ',', '.') }}</td>
        @endforeach
        <td style="border: 1px solid black; text-align: right; background-color: #FFFF00; font-size: 9pt;">{{ number_format($daysInMonth > 0 ? $totals['total_bulanan'] / $daysInMonth : 0, 2, ',', '.') }}</td>
        <td style="border: 1px solid black; text-align: center; font-size: 8pt;">rata2 perhari<br>(kg/hari)</td>
    </tr>
</table>