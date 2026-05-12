<?php

namespace App\Http\Controllers;

use App\Models\SampahTerkelola;
use App\Models\SampahDiserahkan;
use App\Models\LokasiAsal;
use App\Exports\LaporanMultiSheetExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Generate and download Excel report
     */
    public function generateExcel(Request $request)
    {
        $year = $request->input('year', date('Y'));
        
        // Get all lokasi
        $lokasis = LokasiAsal::all();

        // Prepare data arrays
        $rekapNeracaData = $this->prepareRekapNeracaData($year);
        $rekapTerkelolaData = $this->prepareRekapTerkelolaData($year);
        $rekapAreaData = $this->prepareRekapAreaData($year, $lokasis);
        $dailyData = $this->prepareDailyData($year, $lokasis);

        return Excel::download(
            new LaporanMultiSheetExport(
                $rekapNeracaData,
                $rekapTerkelolaData,
                $rekapAreaData,
                $dailyData
            ),
            'Laporan_Pengelolaan_Sampah_' . $year . '.xlsx'
        );
    }

    /**
     * Prepare data for Rekap Neraca sheet
     */
    private function prepareRekapNeracaData($year)
    {
        $data = [];
        $totals = [
            'total_diserahkan' => 0,
            'total_terkelola' => 0
        ];

        $diserahkanData = SampahDiserahkan::whereYear('tanggal', $year)
            ->selectRaw('MONTH(tanggal) as month, sum(jumlah) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $terkelolaData = SampahTerkelola::whereYear('tanggal', $year)
            ->selectRaw('MONTH(tanggal) as month, sum(jumlah) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        for ($month = 1; $month <= 12; $month++) {
            $diserahkan = $diserahkanData->where('month', $month)->first();
            $terkelola = $terkelolaData->where('month', $month)->first();

            $data[] = [
                'bulan' => Carbon::create($year, $month, 1)->format('F'),
                'diserahkan' => $diserahkan ? $diserahkan->total : 0,
                'terkelola' => $terkelola ? $terkelola->total : 0
            ];

            $totals['total_diserahkan'] += $diserahkan ? $diserahkan->total : 0;
            $totals['total_terkelola'] += $terkelola ? $terkelola->total : 0;
        }

        return [
            'data' => $data,
            'totals' => $totals
        ];
    }

    /**
     * Prepare data for Rekap Terkelola sheet
     */
    private function prepareRekapTerkelolaData($year)
    {
        $data = [];
        $totals = [
            'total_recycling' => 0,
            'total_reuse' => 0,
            'total_reduce' => 0,
            'total_tpa' => 0
        ];

        $monthlyData = SampahTerkelola::with('tujuan_sampah')
            ->whereYear('tanggal', $year)
            ->get()
            ->groupBy(function($item) {
                return Carbon::parse($item->tanggal)->format('n');
            });

        for ($month = 1; $month <= 12; $month++) {
            $monthData = $monthlyData->get($month, collect([]));
            
            $recycling = $monthData->where('tujuan_sampah.kategori', 'recycling')->sum('jumlah');
            $reuse = $monthData->where('tujuan_sampah.kategori', 'reuse')->sum('jumlah');
            $reduce = $monthData->where('tujuan_sampah.kategori', 'reduce')->sum('jumlah');
            $tpa = $monthData->where('tujuan_sampah.kategori', 'tpa')->sum('jumlah');

            $data[] = [
                'bulan' => Carbon::create($year, $month, 1)->format('F'),
                'recycling' => $recycling,
                'reuse' => $reuse,
                'reduce' => $reduce,
                'tpa' => $tpa
            ];

            $totals['total_recycling'] += $recycling;
            $totals['total_reuse'] += $reuse;
            $totals['total_reduce'] += $reduce;
            $totals['total_tpa'] += $tpa;
        }

        return [
            'data' => $data,
            'totals' => $totals
        ];
    }

    /**
     * Prepare data for Rekap Area sheet
     */
    private function prepareRekapAreaData($year, $lokasis)
    {
        $data = [];
        $totals = [];

        foreach ($lokasis as $lokasi) {
            $totals[$lokasi->id] = 0;
        }

        $monthlyData = SampahDiserahkan::with('lokasi_asal')
            ->whereYear('tanggal', $year)
            ->get()
            ->groupBy(function($item) {
                return Carbon::parse($item->tanggal)->format('n');
            });

        for ($month = 1; $month <= 12; $month++) {
            $monthData = [
                'bulan' => Carbon::create($year, $month, 1)->format('F')
            ];

            $monthRecords = $monthlyData->get($month, collect([]));

            foreach ($lokasis as $lokasi) {
                $amount = $monthRecords->where('lokasi_asal_id', $lokasi->id)->sum('jumlah');
                $monthData[$lokasi->id] = $amount;
                $totals[$lokasi->id] += $amount;
            }

            $data[] = $monthData;
        }

        return [
            'data' => $data,
            'totals' => $totals,
            'lokasis' => $lokasis
        ];
    }

    /**
     * Prepare data for daily sheets (one per month)
     */
    private function prepareDailyData($year, $lokasis)
    {
        $dailyData = [];

        for ($month = 1; $month <= 12; $month++) {
            $startDate = Carbon::create($year, $month, 1);
            $endDate = $startDate->copy()->endOfMonth();
            
            // Get daily data for the month
            $sampahDiserahkan = SampahDiserahkan::with(['lokasi_asal'])
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->get()
                ->groupBy('tanggal');

            $sampahTerkelola = SampahTerkelola::with(['tujuan_sampah'])
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->get()
                ->groupBy('tanggal');

            $monthData = [];
            $totalsNeraca = [
                'total_diserahkan' => 0,
                'total_terkelola' => 0
            ];
            $totalsArea = [];

            foreach ($lokasis as $lokasi) {
                $totalsArea[$lokasi->id] = 0;
            }

            // Process each day of the month
            for ($day = 1; $day <= $endDate->day; $day++) {
                $currentDate = Carbon::create($year, $month, $day);
                $dateStr = $currentDate->format('Y-m-d');
                
                $dayData = [
                    'tanggal' => $currentDate->format('d F Y'),
                    'diserahkan' => 0,
                    'terkelola' => 0
                ];

                // Add area data
                foreach ($lokasis as $lokasi) {
                    $dayData['area_' . $lokasi->id] = 0;
                }

                // Process diserahkan data
                if ($sampahDiserahkan->has($dateStr)) {
                    $diserahkanAmount = $sampahDiserahkan[$dateStr]->sum('jumlah');
                    $dayData['diserahkan'] = $diserahkanAmount;
                    $totalsNeraca['total_diserahkan'] += $diserahkanAmount;

                    // Process area data
                    foreach ($lokasis as $lokasi) {
                        $areaAmount = $sampahDiserahkan[$dateStr]
                            ->where('lokasi_asal_id', $lokasi->id)
                            ->sum('jumlah');
                        $dayData['area_' . $lokasi->id] = $areaAmount;
                        $totalsArea[$lokasi->id] += $areaAmount;
                    }
                }

                // Process terkelola data
                if ($sampahTerkelola->has($dateStr)) {
                    $terkelolaAmount = $sampahTerkelola[$dateStr]->sum('jumlah');
                    $dayData['terkelola'] = $terkelolaAmount;
                    $totalsNeraca['total_terkelola'] += $terkelolaAmount;
                }

                $monthData[] = $dayData;
            }

            $dailyData[Carbon::create($year, $month, 1)->format('F')] = [
                'data' => $monthData,
                'totals_neraca' => $totalsNeraca,
                'totals_area' => $totalsArea
            ];
        }

        return [
            'dailyData' => $dailyData,
            'lokasis' => $lokasis
        ];
    }
}