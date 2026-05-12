<?php

namespace App\Http\Controllers;

use App\Models\SampahTerkelola;
use App\Models\SampahDiserahkan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardStatsController extends Controller
{
    public function getStats(Request $request)
    {
        $period = $request->get('period', 'weekly');
        $type = $request->get('type', 'both'); // both, terkelola, diserahkan
        
        // Set date range based on period
        switch ($period) {
            case 'daily':
                $startDate = Carbon::today();
                $endDate = Carbon::today()->endOfDay();
                $groupBy = 'HOUR(tgl)';
                $labels = range(0, 23);
                break;
            case 'weekly':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                $groupBy = 'DATE(tgl)';
                $labels = range(0, 6);
                break;
            case 'monthly':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $groupBy = 'DATE(tgl)';
                $labels = range(1, Carbon::now()->daysInMonth);
                break;
        }

        $distribution = [];
        $trendData = [];

        // Get data based on type filter
        if ($type === 'both' || $type === 'terkelola') {
            $terkelolaDistribution = SampahTerkelola::query()
                ->whereBetween('tgl', [$startDate, $endDate])
                ->join('jenis', 'sampah_terkelolas.id_jenis', '=', 'jenis.id_jenis')
                ->select('jenis.nama_jenis', DB::raw('SUM(jumlah_berat) as total'))
                ->groupBy('jenis.id_jenis', 'jenis.nama_jenis')
                ->pluck('total', 'nama_jenis')
                ->toArray();
            
            foreach ($terkelolaDistribution as $jenis => $total) {
                $distribution[$jenis] = ($distribution[$jenis] ?? 0) + $total;
            }

            $terkelolaTrend = SampahTerkelola::query()
                ->whereBetween('tgl', [$startDate, $endDate])
                ->select(DB::raw(str_replace('tgl', 'sampah_terkelolas.tgl', $groupBy) . " as date"), DB::raw('SUM(jumlah_berat) as total'))
                ->groupBy(DB::raw(str_replace('tgl', 'sampah_terkelolas.tgl', $groupBy)))
                ->get();
            
            $trendData = array_merge($trendData, $terkelolaTrend->toArray());
        }

        if ($type === 'both' || $type === 'diserahkan') {
            $diserahkanDistribution = SampahDiserahkan::query()
                ->whereBetween('tgl_diserahkan', [$startDate, $endDate])
                ->join('jenis', 'sampah_diserahkans.id_jenis', '=', 'jenis.id_jenis')
                ->select('jenis.nama_jenis', DB::raw('SUM(jumlah_berat) as total'))
                ->groupBy('jenis.id_jenis', 'jenis.nama_jenis')
                ->pluck('total', 'nama_jenis')
                ->toArray();
            
            foreach ($diserahkanDistribution as $jenis => $total) {
                $distribution[$jenis] = ($distribution[$jenis] ?? 0) + $total;
            }

            $diserahkanTrend = SampahDiserahkan::query()
                ->whereBetween('tgl_diserahkan', [$startDate, $endDate])
                ->select(DB::raw(str_replace('tgl', 'sampah_diserahkans.tgl_diserahkan', $groupBy) . " as date"), DB::raw('SUM(jumlah_berat) as total'))
                ->groupBy(DB::raw(str_replace('tgl', 'sampah_diserahkans.tgl_diserahkan', $groupBy)))
                ->get();
            
            $trendData = array_merge($trendData, $diserahkanTrend->toArray());
        }

        // Process trend data
        $trend = [
            'labels' => [],
            'values' => array_fill(0, count($labels), 0)
        ];

        // Aggregate trend data by date
        $aggregatedTrend = [];
        foreach ($trendData as $data) {
            $date = $data['date'];
            if (!isset($aggregatedTrend[$date])) {
                $aggregatedTrend[$date] = 0;
            }
            $aggregatedTrend[$date] += $data['total'];
        }

        foreach ($labels as $i => $label) {
            if ($period === 'daily') {
                $trend['labels'][] = sprintf("%02d:00", $label);
                if (isset($aggregatedTrend[$label])) {
                    $trend['values'][$i] = $aggregatedTrend[$label];
                }
            } elseif ($period === 'weekly') {
                $date = Carbon::now()->startOfWeek()->addDays($label);
                $trend['labels'][] = $date->format('D');
                $dateStr = $date->format('Y-m-d');
                if (isset($aggregatedTrend[$dateStr])) {
                    $trend['values'][$i] = $aggregatedTrend[$dateStr];
                }
            } else {
                $date = Carbon::now()->startOfMonth()->addDays($label - 1);
                $trend['labels'][] = $date->format('d M');
                $dateStr = $date->format('Y-m-d');
                if (isset($aggregatedTrend[$dateStr])) {
                    $trend['values'][$i] = $aggregatedTrend[$dateStr];
                }
            }
        }

        return response()->json([
            'distribution' => $distribution,
            'trend' => $trend
        ]);
    }
}