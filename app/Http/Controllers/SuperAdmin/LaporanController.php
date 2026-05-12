<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\SampahTerkelola;
use App\Models\SampahDiserahkan;
use App\Models\Instansi;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Menampilkan Index Laporan (Halaman utama laporan)
     */
    public function index()
    {
        return view('superAdmin.laporan.export-laporan');
    }

    /**
     * Menampilkan Halaman Form Export
     */
    public function showExportForm()
    {
        return view('superAdmin.laporan.export-laporan');
    }

    /**
     * PROSES UTAMA EXPORT (Template Injection)
     */
    public function processExport(Request $request)
    {
        set_time_limit(600);
        ini_set('memory_limit', '1024M');

        // --- 1. VALIDASI INPUT ---
        $request->validate([
            'tahun' => 'required',
            'instansi_ids' => 'required|array',
            'type' => 'required|in:tahunan,bulanan'
        ]);

        $type = $request->input('type');
        $tahunInput = $request->input('tahun');
        $instansiIds = $request->input('instansi_ids');

        // --- 2. SIAPKAN NAMA INSTANSI (Untuk Header Excel) ---
        $namaInstansiList = Instansi::whereIn('id_instansi', $instansiIds)
            ->pluck('nama_instansi')
            ->toArray();
            
        $teksInstansi = "";
        if (count($namaInstansiList) == 1) {
            $teksInstansi = strtoupper($namaInstansiList[0]);
        } elseif (count($namaInstansiList) > 1 && count($namaInstansiList) <= 3) {
            $teksInstansi = strtoupper(implode(", ", $namaInstansiList));
        } else {
            $teksInstansi = "SELURUH WILAYAH OPERASIONAL";
        }

        try {
            // =========================================================
            // SKENARIO 1: EXPORT BULANAN (Cloning Sheet)
            // =========================================================
            if ($type == 'bulanan') {
                $request->validate(['bulan' => 'required|array']);
                $bulanDipilih = $request->input('bulan');

                // Load Template
                $templatePath = storage_path('app/public/templates/template_bulanan_master.xlsx');
                if (!file_exists($templatePath)) return back()->with('error', 'File template_bulanan_master.xlsx tidak ditemukan!');
                
                $spreadsheet = IOFactory::load($templatePath);
                $masterSheet = $spreadsheet->getSheetByName('Rekap Bulanan');
                if (!$masterSheet) return back()->with('error', 'Sheet "Rekap Bulanan" tidak ditemukan di template!');

                foreach ($bulanDipilih as $bln) {
                    $bulanNama = $this->getNamaBulan($bln);
                    
                    // 1. Clone Sheet Master
                    $clonedSheet = clone $masterSheet;
                    $clonedSheet->setTitle(substr($bulanNama, 0, 30)); // Max 31 karakter
                    $spreadsheet->addSheet($clonedSheet);
                    
                    // 2. Isi Header (Sesuai Request: Periode->C2, Instansi->C4)
                    $clonedSheet->setCellValue('C2', strtoupper($bulanNama) . ' ' . $tahunInput);
                    $clonedSheet->setCellValue('C4', "Area: " . $teksInstansi);

                    // 3. Isi Data (Start Row 8, Tahun Kalender)
                    $this->isiDataKeSheet($clonedSheet, $tahunInput, $bln, $instansiIds, 8);
                }

                // Hapus Master Sheet (Sisa Cloning)
                $sheetIndex = $spreadsheet->getIndex($masterSheet);
                $spreadsheet->removeSheetByIndex($sheetIndex);
                $spreadsheet->setActiveSheetIndex(0);

                $fileName = 'Laporan_Bulanan_' . $tahunInput . '.xlsx';
            } 
            
            // =========================================================
            // SKENARIO 2: EXPORT TAHUNAN (Full 12 Bulan)
            // =========================================================
            else {
                $templatePath = storage_path('app/public/templates/template_master.xlsx');
                if (!file_exists($templatePath)) return back()->with('error', 'File template_master.xlsx tidak ditemukan!');

                $spreadsheet = IOFactory::load($templatePath);

                // Update Header Rekap Neraca
                $sheetNeraca = $spreadsheet->getSheetByName('Rekap Neraca Pengelolaan Sampah');
                if ($sheetNeraca) {
                    $sheetNeraca->setCellValue('D2', $tahunInput - 1); // Tahun Awal
                    $sheetNeraca->setCellValue('G2', $tahunInput);     // Tahun Akhir
                    $sheetNeraca->setCellValue('C4', $teksInstansi);   // Nama Instansi
                }

                // Loop 12 Bulan (Logika Neraca: Juli - Juni)
                $months = [
                    7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 
                    11 => 'November', 12 => 'Desember',
                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 
                    5 => 'Mei', 6 => 'Juni'
                ];

                foreach ($months as $monthNum => $sheetName) {
                    $sheet = $spreadsheet->getSheetByName($sheetName);
                    if (!$sheet) continue;
                    
                    // Juli-Des = Tahun Lalu, Jan-Juni = Tahun Ini
                    $currentYear = ($monthNum >= 7) ? ($tahunInput - 1) : $tahunInput;
                    
                    // Isi Data (Start Row 8)
                    $this->isiDataKeSheet($sheet, $currentYear, $monthNum, $instansiIds, 8);
                }
                
                $fileName = 'Laporan_Neraca_' . $tahunInput . '.xlsx';
            }

            // OUTPUT DOWNLOAD
            $writer = new Xlsx($spreadsheet);
            return response()->streamDownload(function() use ($writer) {
                $writer->save('php://output');
            }, $fileName, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'System Error: ' . $e->getMessage());
        }
    }

    /**
     * HELPER: QUERY DATABASE & TULIS KE CELL EXCEL
     */
    private function isiDataKeSheet($sheet, $year, $month, $instansiIds, $startRow)
    {
        // 1. DATA SAMPAH TERKELOLA
        // Kolom DB: 'tgl'
        $dataTerkelola = SampahTerkelola::whereYear('tgl', $year)
            ->whereMonth('tgl', $month)
            ->whereHas('user', fn($q) => $q->whereIn('id_instansi', $instansiIds))
            ->get();

        foreach ($dataTerkelola as $row) {
            $tgl = (int)Carbon::parse($row->tgl)->format('d');
            
            // Rumus Baris: StartRow + (Tanggal - 1)
            $baris = $startRow + ($tgl - 1);
            
            $kolom = $this->getExcelColumn($row->id_lokasi, $row->id_jenis, 'terkelola');
            
            if ($kolom) {
                $val = $sheet->getCell($kolom . $baris)->getValue();
                $sheet->setCellValue($kolom . $baris, (float)$val + (float)$row->jumlah_berat);
            }
        }

        // 2. DATA SAMPAH DISERAHKAN
        // Kolom DB: 'tgl_diserahkan'
        $dataDiserahkan = SampahDiserahkan::whereYear('tgl_diserahkan', $year)
            ->whereMonth('tgl_diserahkan', $month)
            ->whereHas('user', fn($q) => $q->whereIn('id_instansi', $instansiIds))
            ->get();

        foreach ($dataDiserahkan as $row) {
            $tgl = (int)Carbon::parse($row->tgl_diserahkan)->format('d');
            $baris = $startRow + ($tgl - 1);
            
            $kolom = $this->getExcelColumn($row->id_lokasi, $row->id_jenis, 'diserahkan');
            
            if ($kolom) {
                $val = $sheet->getCell($kolom . $baris)->getValue();
                $sheet->setCellValue($kolom . $baris, (float)$val + (float)$row->jumlah_berat);
            }
        }
    }

    /**
     * HELPER: MAPPING KOLOM EXCEL
     * Aturan Bisnis:
     * 1. Diserahkan -> Kolom 3 (Lainnya/Residu)
     * 2. Terkelola Organik -> Kolom 1
     * 3. Terkelola Anorganik & Residu -> Kolom 2
     */
    private function getExcelColumn($lokasiId, $jenisId, $sumberData)
    {
        $target = 0;
        
        // --- 1. LOGIKA JENIS SAMPAH ---
        if ($sumberData == 'diserahkan') {
            $target = 3; // Semua 'Diserahkan' masuk ke Kolom 3 (Lainnya)
        } else {
            // Sampah Terkelola
            if ($jenisId == 1) {
                $target = 1; // Organik -> Kolom 1
            } 
            elseif ($jenisId == 2 || $jenisId == 3) {
                $target = 2; // Anorganik DAN Residu Terkelola -> Kolom 2 (Anorganik)
            }
        }

        // --- 2. LOGIKA LOKASI (Mapping ke Huruf Excel) ---
        // KANTOR (1) -> C, D, E
        if ($lokasiId == 1) return ($target==1 ? 'C' : ($target==2 ? 'D' : 'E'));
        
        // PARKIR (2) -> G, H, I
        if ($lokasiId == 2) return ($target==1 ? 'G' : ($target==2 ? 'H' : 'I'));
        
        // RUANG TUNGGU (3) -> K, L, M
        if ($lokasiId == 3) return ($target==1 ? 'K' : ($target==2 ? 'L' : 'M'));
        
        // TEMPAT MAKAN (4) -> O, P, Q
        if ($lokasiId == 4) return ($target==1 ? 'O' : ($target==2 ? 'P' : 'Q'));
        
        // KAPAL (5) -> S, T, U
        if ($lokasiId == 5) return ($target==1 ? 'S' : ($target==2 ? 'T' : 'U'));
        
        // AREA LAIN (6) -> W, X, Y
        if ($lokasiId == 6) return ($target==1 ? 'W' : ($target==2 ? 'X' : 'Y'));

        return null;
    }

    private function getNamaBulan($num)
    {
        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        return $bulan[$num] ?? '';
    }

    // =========================================================
    // LAPORAN HARIAN, MINGGUAN, BULANAN, TAHUNAN
    // =========================================================

    /**
     * Menampilkan laporan harian
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function laporanHarian(Request $request)
    {
        $tanggal = $request->get('tanggal', date('Y-m-d'));
        
        // Query untuk mendapatkan data laporan harian
        $sampahTerkelolas = SampahTerkelola::with(['jenis', 'lokasiAsal', 'user'])
            ->whereDate('tgl', $tanggal)
            ->get();
            
        $sampahDiserahkans = SampahDiserahkan::with(['jenis', 'lokasiAsal', 'tujuanSampah', 'user'])
            ->whereDate('tgl_diserahkan', $tanggal)
            ->get();
        
        return view('superAdmin.laporan.harian', compact('sampahTerkelolas', 'sampahDiserahkans', 'tanggal'));
    }
    
    /**
     * Menampilkan laporan mingguan
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function laporanMingguan(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));
        $week = $request->get('week', 1);
        
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $weekStart = $startDate->copy()->addDays(($week - 1) * 7);
        $weekEnd = $weekStart->copy()->addDays(6);
        
        // Query untuk mendapatkan data laporan mingguan
        $sampahTerkelolas = SampahTerkelola::with(['jenis', 'lokasiAsal', 'user'])
            ->whereBetween('tgl', [$weekStart, $weekEnd])
            ->get();
            
        $sampahDiserahkans = SampahDiserahkan::with(['jenis', 'lokasiAsal', 'tujuanSampah', 'user'])
            ->whereBetween('tgl_diserahkan', [$weekStart, $weekEnd])
            ->get();
        
        return view('superAdmin.laporan.mingguan', compact(
            'sampahTerkelolas', 
            'sampahDiserahkans', 
            'year', 
            'month', 
            'week', 
            'weekStart', 
            'weekEnd'
        ));
    }
    
    /**
     * Menampilkan laporan bulanan
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function laporanBulanan(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));
        
        // Query untuk mendapatkan data laporan bulanan
        $sampahTerkelolas = SampahTerkelola::with(['jenis', 'lokasiAsal', 'user'])
            ->whereYear('tgl', $year)
            ->whereMonth('tgl', $month)
            ->get();
            
        $sampahDiserahkans = SampahDiserahkan::with(['jenis', 'lokasiAsal', 'tujuanSampah', 'user'])
            ->whereYear('tgl_diserahkan', $year)
            ->whereMonth('tgl_diserahkan', $month)
            ->get();
        
        return view('superAdmin.laporan.bulanan', compact(
            'sampahTerkelolas', 
            'sampahDiserahkans', 
            'year', 
            'month'
        ));
    }
    
    /**
     * Menampilkan laporan tahunan
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function laporanTahunan(Request $request)
    {
        $year = $request->get('year', date('Y'));
        
        // Query untuk mendapatkan data laporan tahunan
        $sampahTerkelolas = SampahTerkelola::with(['jenis', 'lokasiAsal', 'user'])
            ->whereYear('tgl', $year)
            ->get();
            
        $sampahDiserahkans = SampahDiserahkan::with(['jenis', 'lokasiAsal', 'tujuanSampah', 'user'])
            ->whereYear('tgl_diserahkan', $year)
            ->get();
        
        // Mengelompokkan data berdasarkan bulan
        $monthlyData = [];
        
        for ($m = 1; $m <= 12; $m++) {
            $monthlyData[$m] = [
                'terkelola' => [
                    'total' => 0,
                    'sampah' => 0,
                    'lb3' => 0
                ],
                'diserahkan' => [
                    'total' => 0,
                    'sampah' => 0,
                    'lb3' => 0
                ]
            ];
        }
        
        foreach ($sampahTerkelolas as $terkelola) {
            $month = Carbon::parse($terkelola->tgl)->month;
            $monthlyData[$month]['terkelola']['total'] += $terkelola->jumlah_berat;
            
            if ($terkelola->jenis && $terkelola->jenis->nama_jenis === 'LB3') {
                $monthlyData[$month]['terkelola']['lb3'] += $terkelola->jumlah_berat;
            } else {
                $monthlyData[$month]['terkelola']['sampah'] += $terkelola->jumlah_berat;
            }
        }
        
        foreach ($sampahDiserahkans as $diserahkan) {
            $month = Carbon::parse($diserahkan->tgl_diserahkan)->month;
            $monthlyData[$month]['diserahkan']['total'] += $diserahkan->jumlah_berat;
            
            if ($diserahkan->jenis && $diserahkan->jenis->nama_jenis === 'LB3') {
                $monthlyData[$month]['diserahkan']['lb3'] += $diserahkan->jumlah_berat;
            } else {
                $monthlyData[$month]['diserahkan']['sampah'] += $diserahkan->jumlah_berat;
            }
        }
        
        return view('superAdmin.laporan.tahunan', compact(
            'year', 
            'monthlyData'
        ));
    }
}
