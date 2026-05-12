<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\RekapNeracaSheet;
use App\Exports\Sheets\RekapTerkelolaSheet;
use App\Exports\Sheets\RekapAreaSheet;
use App\Exports\Sheets\MonthlyDailySheet;

class LaporanMultiSheetExport implements WithMultipleSheets
{
    protected $rekapNeracaData;
    protected $rekapTerkelolaData;
    protected $rekapAreaData;
    protected $dailyData;
    protected $tahun;

    public function __construct($d1, $d2, $d3, $d4, $tahun = null)
    {
        $this->rekapNeracaData = $d1;
        $this->rekapTerkelolaData = $d2;
        $this->rekapAreaData = $d3;
        $this->dailyData = $d4;
        $this->tahun = $tahun ?? date('Y');
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        // Sheet 1-3: Rekap (hanya jika data tersedia)
        if ($this->rekapNeracaData !== null) {
            $sheets[] = new RekapNeracaSheet(
                $this->rekapNeracaData['data'], 
                $this->rekapNeracaData['totals'],
                $this->tahun
            );
        }

        if ($this->rekapTerkelolaData !== null) {
            $sheets[] = new RekapTerkelolaSheet(
                $this->rekapTerkelolaData['data'], 
                $this->rekapTerkelolaData['totals'],
                $this->tahun
            );
        }
        
        if ($this->rekapAreaData !== null) {
            $sheets[] = new RekapAreaSheet(
                $this->rekapAreaData['data'], 
                $this->rekapAreaData['totals'], 
                $this->rekapAreaData['lokasis'],
                $this->tahun
            );
        }

        // Sheet 4-15 (atau 1-12 jika tidak ada rekap): Data Harian per Bulan
        foreach ($this->dailyData['dailyData'] as $monthName => $data) {
            $sheets[] = new MonthlyDailySheet(
                $monthName, 
                $data['data'], 
                $data['totals'],
                $this->dailyData['lokasis'],
                $data['tahun'] ?? $this->tahun,
                $data['bulan'] ?? $monthName
            );
        }

        return $sheets;
    }
}