<?php

namespace App\Exports\Sheets;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class MonthlyDailySheet implements FromView, WithTitle, ShouldAutoSize
{
    private $monthName;
    private $data;
    private $totals;
    private $lokasis;
    private $tahun;
    private $bulan;

    public function __construct(string $monthName, array $data, array $totals, $lokasis, $tahun = null, $bulan = null)
    {
        $this->monthName = $monthName;
        $this->data = $data;
        $this->totals = $totals;
        $this->lokasis = $lokasis;
        $this->tahun = $tahun ?? date('Y');
        $this->bulan = $bulan ?? $monthName;
    }

    public function view(): View
    {
        return view('admin.laporan.export_templates.daily_month', [
            'dailyData' => $this->data,
            'totals' => $this->totals,
            'lokasis' => $this->lokasis,
            'monthName' => $this->monthName,
            'tahun' => $this->tahun,
            'bulan' => $this->bulan
        ]);
    }

    public function title(): string
    {
        return $this->monthName;
    }
}
