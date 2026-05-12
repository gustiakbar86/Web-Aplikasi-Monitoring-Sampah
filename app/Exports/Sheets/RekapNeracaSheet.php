<?php

namespace App\Exports\Sheets;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RekapNeracaSheet implements FromView, WithTitle, ShouldAutoSize
{
    private $data;
    private $totals;
    private $tahun;

    public function __construct(array $data, array $totals, $tahun = null) {
        $this->data = $data;
        $this->totals = $totals;
        $this->tahun = $tahun ?? date('Y');
    }
    
    public function view(): View {
        return view('admin.laporan.export_templates.rekap_neraca', [
            'rekapData' => $this->data,
            'totals' => $this->totals,
            'tahun' => $this->tahun
        ]);
    }
    
    public function title(): string {
        return 'Rekap Neraca Sampah';
    }
}