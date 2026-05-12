<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SampahTerkelola;
use App\Models\SampahDiserahkan;
use App\Models\Jenis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Get dashboard summary untuk petugas
     * GET /api/petugas/dashboard
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::guard('api')->user();
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized',
                    'code' => 401
                ], 401);
            }

            $userId = $user->id;
            
            // Get data untuk dashboard hari ini
            $today = Carbon::now()->toDateString();
            $thisWeekStart = Carbon::now()->startOfWeek(Carbon::MONDAY);
            $thisWeekEnd = Carbon::now()->endOfWeek(Carbon::SUNDAY);
            
            // Data sampah terkelola
            $sampahTerkelolaHari = SampahTerkelola::where('id_user', $userId)
                ->whereDate('tgl', $today)
                ->sum('jumlah_berat');
            
            $sampahTerkelolaMingguan = SampahTerkelola::where('id_user', $userId)
                ->whereBetween('tgl', [$thisWeekStart, $thisWeekEnd])
                ->sum('jumlah_berat');
            
            $sampahTerkelolaTotal = SampahTerkelola::where('id_user', $userId)
                ->sum('jumlah_berat');
            
            $sampahTerkelolaCount = SampahTerkelola::where('id_user', $userId)->count();
            
            // Data sampah diserahkan
            $sampahDiserahkanHari = SampahDiserahkan::where('id_user', $userId)
                ->whereDate('tgl_diserahkan', $today)
                ->sum('jumlah_berat');
            
            $sampahDiserahkanMingguan = SampahDiserahkan::where('id_user', $userId)
                ->whereBetween('tgl_diserahkan', [$thisWeekStart, $thisWeekEnd])
                ->sum('jumlah_berat');
            
            $sampahDiserahkanTotal = SampahDiserahkan::where('id_user', $userId)
                ->sum('jumlah_berat');
            
            $sampahDiserahkanCount = SampahDiserahkan::where('id_user', $userId)->count();
            
            // Data keseluruhan
            $totalHari = $sampahTerkelolaHari + $sampahDiserahkanHari;
            $totalMingguan = $sampahTerkelolaMingguan + $sampahDiserahkanMingguan;
            $totalKeseluruhan = $sampahTerkelolaTotal + $sampahDiserahkanTotal;
            $totalData = $sampahTerkelolaCount + $sampahDiserahkanCount;

            return response()->json([
                'status' => 'success',
                'message' => 'Dashboard summary berhasil diambil',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'instansi' => $user->instansi ? $user->instansi->nama_instansi : null,
                    ],
                    'summary' => [
                        'sampah_terkelola' => [
                            'hari_ini' => (float) round($sampahTerkelolaHari, 2),
                            'minggu_ini' => (float) round($sampahTerkelolaMingguan, 2),
                            'total' => (float) round($sampahTerkelolaTotal, 2),
                            'jumlah_data' => $sampahTerkelolaCount,
                        ],
                        'sampah_diserahkan' => [
                            'hari_ini' => (float) round($sampahDiserahkanHari, 2),
                            'minggu_ini' => (float) round($sampahDiserahkanMingguan, 2),
                            'total' => (float) round($sampahDiserahkanTotal, 2),
                            'jumlah_data' => $sampahDiserahkanCount,
                        ],
                        'keseluruhan' => [
                            'hari_ini' => (float) round($totalHari, 2),
                            'minggu_ini' => (float) round($totalMingguan, 2),
                            'total' => (float) round($totalKeseluruhan, 2),
                            'total_data' => $totalData,
                        ]
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'code' => 500
            ], 500);
        }
    }

    /**
     * Get statistik dengan filter
     * GET /api/petugas/dashboard/statistik?period=weekly&type=both
     * 
     * period: daily, weekly, monthly (default: weekly)
     * type: both, terkelola, diserahkan (default: both)
     */
    public function statistik(Request $request)
    {
        try {
            $user = Auth::guard('api')->user();
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized',
                    'code' => 401
                ], 401);
            }

            $userId = $user->id;
            $period = $request->get('period', 'weekly');
            $type = $request->get('type', 'both');
            
            // Validasi period
            if (!in_array($period, ['daily', 'weekly', 'monthly'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Period harus: daily, weekly, atau monthly',
                    'code' => 422
                ], 422);
            }
            
            // Validasi type
            if (!in_array($type, ['both', 'terkelola', 'diserahkan'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Type harus: both, terkelola, atau diserahkan',
                    'code' => 422
                ], 422);
            }

            // Setup date range based on period
            $dateRange = $this->getDateRange($period);
            
            // Query data berdasarkan user id
            $sampahTerkelola = SampahTerkelola::with('jenis')
                ->where('id_user', $userId)
                ->whereBetween('tgl', $dateRange)
                ->get();
                
            $sampahDiserahkan = SampahDiserahkan::with('jenis')
                ->where('id_user', $userId)
                ->whereBetween('tgl_diserahkan', $dateRange)
                ->get();
            
            // Pilih data berdasarkan type filter
            $data = collect();
            if ($type === 'both' || $type === 'terkelola') {
                $data = $data->merge($sampahTerkelola);
            }
            if ($type === 'both' || $type === 'diserahkan') {
                $data = $data->merge($sampahDiserahkan);
            }
            
            // Hitung distribusi per kategori
            $distribution = [];
            $categories = Jenis::distinct('kategori_jenis')
                ->pluck('kategori_jenis')
                ->sort()
                ->values()
                ->toArray();
            
            foreach ($categories as $category) {
                $total = $data->filter(function($item) use ($category) {
                    return $item->jenis->kategori_jenis === $category;
                })->sum('jumlah_berat');
                
                $distribution[$category] = (float)round($total, 2);
            }
            
            // Hitung trend berat per hari/periode
            $trendData = $this->getTrendData($data, $period);
            
            // Total summary
            $totalBerat = $data->sum('jumlah_berat');
            $totalCount = count($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Statistik berhasil diambil',
                'data' => [
                    'period' => $period,
                    'type' => $type,
                    'date_range' => [
                        'start' => $dateRange[0]->format('Y-m-d'),
                        'end' => $dateRange[1]->format('Y-m-d'),
                    ],
                    'summary' => [
                        'total_berat' => (float)round($totalBerat, 2),
                        'total_data' => $totalCount,
                    ],
                    'distribution' => $distribution,
                    'trend' => $trendData,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'code' => 500
            ], 500);
        }
    }

    /**
     * Helper: Get date range based on period
     */
    private function getDateRange($period)
    {
        $now = Carbon::now();
        
        switch ($period) {
            case 'daily':
                return [$now->copy()->startOfDay(), $now->copy()->endOfDay()];
            case 'monthly':
                return [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()];
            case 'weekly':
            default:
                $startOfWeek = $now->copy()->startOfWeek(Carbon::MONDAY);
                $endOfWeek = $startOfWeek->copy()->addDays(6)->endOfDay();
                return [$startOfWeek, $endOfWeek];
        }
    }

    /**
     * Helper: Get trend data for chart
     */
    private function getTrendData($data, $period)
    {
        $labels = [];
        $values = [];
        
        if ($data->isEmpty()) {
            // Return empty data struktur jika tidak ada data
            if ($period === 'daily') {
                for ($i = 0; $i < 24; $i++) {
                    $labels[] = sprintf('%02d:00', $i);
                    $values[] = 0;
                }
            } elseif ($period === 'monthly') {
                for ($i = 1; $i <= 31; $i++) {
                    $labels[] = sprintf('%02d', $i);
                    $values[] = 0;
                }
            } else { // weekly
                $dayNames = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                $labels = $dayNames;
                $values = array_fill(0, 7, 0);
            }
            return ['labels' => $labels, 'values' => $values];
        }
        
        $groupedData = $data->groupBy(function($item) use ($period) {
            $date = $item->tgl ?? $item->tgl_diserahkan;
            $carbonDate = Carbon::parse($date);
            
            if ($period === 'daily') {
                return $carbonDate->format('H:00');
            } elseif ($period === 'monthly') {
                return $carbonDate->format('d');
            } else { // weekly
                return $carbonDate->dayOfWeek;
            }
        });
        
        if ($period === 'daily') {
            for ($i = 0; $i < 24; $i++) {
                $label = sprintf('%02d:00', $i);
                $labels[] = $label;
                $values[] = isset($groupedData[$label]) ? round($groupedData[$label]->sum('jumlah_berat'), 2) : 0;
            }
        } elseif ($period === 'monthly') {
            for ($i = 1; $i <= 31; $i++) {
                $labels[] = sprintf('%02d', $i);
                $values[] = isset($groupedData[(string)$i]) ? round($groupedData[(string)$i]->sum('jumlah_berat'), 2) : 0;
            }
        } else { // weekly
            $dayNames = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
            
            for ($i = 1; $i <= 7; $i++) {
                $dayIndex = ($i === 7) ? 6 : ($i - 1);
                $labels[] = $dayNames[$dayIndex];
                $values[] = isset($groupedData[$i]) ? round($groupedData[$i]->sum('jumlah_berat'), 2) : 0;
            }
        }
        
        return [
            'labels' => $labels,
            'values' => $values
        ];
    }

    /**
     * Get recent data (data terbaru)
     * GET /api/petugas/dashboard/recent?limit=10
     */
    public function recent(Request $request)
    {
        try {
            $user = Auth::guard('api')->user();
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized',
                    'code' => 401
                ], 401);
            }

            $limit = $request->get('limit', 10);
            $limit = min($limit, 100); // Max 100

            $userId = $user->id;
            
            // Get recent sampah terkelola
            $sampahTerkelola = SampahTerkelola::with(['jenis', 'lokasiAsal'])
                ->where('id_user', $userId)
                ->orderBy('tgl', 'desc')
                ->limit($limit)
                ->get()
                ->map(function($item) {
                    return [
                        'id' => $item->id_sampah_terkelola,
                        'type' => 'terkelola',
                        'jenis' => $item->jenis->nama_jenis,
                        'berat' => $item->jumlah_berat,
                        'lokasi' => $item->lokasiAsal->nama_lokasi,
                        'tanggal' => $item->tgl,
                        'created_at' => $item->created_at,
                    ];
                });
            
            // Get recent sampah diserahkan
            $sampahDiserahkan = SampahDiserahkan::with(['jenis', 'lokasiAsal', 'tujuanSampah'])
                ->where('id_user', $userId)
                ->orderBy('tgl_diserahkan', 'desc')
                ->limit($limit)
                ->get()
                ->map(function($item) {
                    return [
                        'id' => $item->id_sampah_diserahkan,
                        'type' => 'diserahkan',
                        'jenis' => $item->jenis->nama_jenis,
                        'berat' => $item->jumlah_berat,
                        'lokasi' => $item->lokasiAsal->nama_lokasi,
                        'tujuan' => $item->tujuanSampah->nama_tujuan,
                        'tanggal' => $item->tgl_diserahkan,
                        'created_at' => $item->created_at,
                    ];
                });
            
            // Merge dan urutkan by created_at descending
            $recent = $sampahTerkelola->merge($sampahDiserahkan)
                ->sortByDesc('created_at')
                ->take($limit)
                ->values();

            return response()->json([
                'status' => 'success',
                'message' => 'Data terbaru berhasil diambil',
                'data' => $recent,
                'count' => count($recent)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'code' => 500
            ], 500);
        }
    }
}
