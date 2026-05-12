<?php

namespace App\Exports\Sheets;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RekapAreaSheet implements FromView, WithTitle, ShouldAutoSize
{
    private $data;
    private $totals;
    private $lokasis;
    private $tahun;

    public function __construct(array $data, array $totals, $lokasis, $tahun = null) {
        $this->data = $data;
        $this->totals = $totals;
        $this->lokasis = $lokasis;
        $this->tahun = $tahun ?? date('Y');
    }
    public function view(): View {
        return view('admin.laporan.export_templates.rekap_area', [
            'rekapData' => $this->data,
            'totals' => $this->totals,
            'lokasis' => $this->lokasis,
            'tahun' => $this->tahun
        ]);
    }
    public function title(): string {
        return 'Rekap area';
    }
}