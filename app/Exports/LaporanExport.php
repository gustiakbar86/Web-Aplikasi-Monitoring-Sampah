<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LaporanExport implements FromView, WithTitle, ShouldAutoSize
{
    protected $data;
    protected $totals;

    public function __construct(array $data, array $totals)
    {
        $this->data = $data;
        $this->totals = $totals;
    }

    /**
    * @return \Illuminate\Contracts\View\View
    */
    public function view(): View
    {
        return view('admin.laporan.export_template', [
            'rekapData' => $this->data,
            'totals' => $this->totals
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Rekap Neraca Pengelolaan Sampah';
    }
}