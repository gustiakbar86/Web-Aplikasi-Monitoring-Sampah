<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SampahTerkelola;
use App\Models\SampahDiserahkan;
use App\Models\Jenis;
use App\Models\LokasiAsal;
use App\Models\TujuanSampah;
use App\Models\User;
use App\Models\Dokumen;
use App\Models\Instansi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SuperAdminController extends Controller
{
    /**
     * Menampilkan dashboard super admin dengan data statistik
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function dashboard(Request $request)
    {
        // Mengambil parameter filter - default ke fiscal year saat ini
        $filterType = $request->get('filter_type', 'fiscal');
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));
        $week = $request->get('week', 1);
        $day = $request->get('day', date('Y-m-d'));
        $dataType = $request->get('data_type', 'both'); // both, terkelola, diserahkan
        $idInstansi = $request->get('id_instansi'); // Add instansi filter
        
        // Tentukan tipe data mana yang akan diquery
        $queryTerkelola = null;
        $queryDiserahkan = null;
        
        if ($dataType === 'both' || $dataType === 'terkelola') {
            $queryTerkelola = SampahTerkelola::query();
        }
        if ($dataType === 'both' || $dataType === 'diserahkan') {
            $queryDiserahkan = SampahDiserahkan::query();
        }
        
        // Apply instansi filter if selected
        if ($idInstansi) {
            if ($queryTerkelola) $queryTerkelola->whereHas('user', fn($q) => $q->where('id_instansi', $idInstansi));
            if ($queryDiserahkan) $queryDiserahkan->whereHas('user', fn($q) => $q->where('id_instansi', $idInstansi));
        }
        
        // Determine date range: support explicit start_date/end_date, fiscal (Jul-Jun), or existing filters
        $useDateRange = false;
        $startDate = null;
        $endDate = null;

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->get('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->get('end_date'))->endOfDay();
            $useDateRange = true;
        } elseif ($filterType === 'fiscal') {
            // fiscal_year param expected to be the ending year (e.g., 2026 means Jul 2025 - Jun 2026)
            // Default: current fiscal year
            $now = Carbon::now();
            $defaultFiscalEnd = ($now->month >= 7) ? $now->year + 1 : $now->year;
            $fiscalEnd = $request->get('fiscal_year', $defaultFiscalEnd);
            $startDate = Carbon::create($fiscalEnd - 1, 7, 1)->startOfDay();
            $endDate = Carbon::create($fiscalEnd, 6, 30)->endOfDay();
            $useDateRange = true;
        } elseif (!$request->filled('filter_type')) {
            // When filter_type is not provided, default to fiscal year saat ini
            $now = Carbon::now();
            $defaultFiscalEnd = ($now->month >= 7) ? $now->year + 1 : $now->year;
            $startDate = Carbon::create($defaultFiscalEnd - 1, 7, 1)->startOfDay();
            $endDate = Carbon::create($defaultFiscalEnd, 6, 30)->endOfDay();
            $useDateRange = true;
            // mark filterType so UI can reflect it
            $filterType = 'fiscal';
        } else {
            // existing granular filters (year/month/week/day) will be applied later
        }

        // Apply date range to base queries if needed
        if ($useDateRange) {
            if ($queryTerkelola) $queryTerkelola->whereBetween('tgl', [$startDate, $endDate]);
            if ($queryDiserahkan) $queryDiserahkan->whereBetween('tgl_diserahkan', [$startDate, $endDate]);
        } else {
            // Menerapkan filter berdasarkan pilihan (year/month/week/day)
            if ($filterType == 'year') {
                if ($queryTerkelola) $queryTerkelola->whereYear('tgl', $year);
                if ($queryDiserahkan) $queryDiserahkan->whereYear('tgl_diserahkan', $year);
            } elseif ($filterType == 'month') {
                if ($queryTerkelola) $queryTerkelola->whereYear('tgl', $year)->whereMonth('tgl', $month);
                if ($queryDiserahkan) $queryDiserahkan->whereYear('tgl_diserahkan', $year)->whereMonth('tgl_diserahkan', $month);
            } elseif ($filterType == 'week') {
                $startWeek = Carbon::create($year, $month, 1)->startOfMonth();
                $weekStart = $startWeek->copy()->addDays(($week - 1) * 7);
                $weekEnd = $weekStart->copy()->addDays(6);
                if ($queryTerkelola) $queryTerkelola->whereBetween('tgl', [$weekStart, $weekEnd]);
                if ($queryDiserahkan) $queryDiserahkan->whereBetween('tgl_diserahkan', [$weekStart, $weekEnd]);
            } elseif ($filterType == 'day') {
                if ($queryTerkelola) $queryTerkelola->whereDate('tgl', $day);
                if ($queryDiserahkan) $queryDiserahkan->whereDate('tgl_diserahkan', $day);
            }
        }
        
        // helper to apply date filter - determines column based on model class
        $applyDateFilter = function($query) use ($useDateRange, $startDate, $endDate, $filterType, $year, $month, $week, $day) {
            // Determine date column based on the model's table
            $model = $query->getModel();
            $dateColumn = ($model->getTable() === 'sampah_diserahkans') ? 'tgl_diserahkan' : 'tgl';
            
            if ($useDateRange) {
                $query->whereBetween($dateColumn, [$startDate, $endDate]);
                return;
            }
            if ($filterType == 'year') {
                $query->whereYear($dateColumn, $year);
            } elseif ($filterType == 'month') {
                $query->whereYear($dateColumn, $year)->whereMonth($dateColumn, $month);
            } elseif ($filterType == 'week') {
                $startDateW = Carbon::create($year, $month, 1)->startOfMonth();
                $weekStart = $startDateW->copy()->addDays(($week - 1) * 7);
                $weekEnd = $weekStart->copy()->addDays(6);
                $query->whereBetween($dateColumn, [$weekStart, $weekEnd]);
            } elseif ($filterType == 'day') {
                $query->whereDate($dateColumn, $day);
            }
        };

        // Mendapatkan kategori jenis sampah untuk pie chart (hanya 3 kategori: Organik, Anorganik, Residu)
        $kategoriList = Jenis::distinct('kategori_jenis')->pluck('kategori_jenis')->toArray();
        $jenisSampah = collect($kategoriList)->map(function($kategori) {
            return (object)['kategori_jenis' => $kategori];
        });
        
        $jenisColors = ['#FF0000', '#00FF00', '#FFFF00', '#0000FF', '#FF00FF', '#00FFFF', '#FF9900', '#9900FF', '#009900'];
        $jenisTotals = [];
        
        // Hitung total berdasarkan tipe data yang dipilih
        $totalSampah = 0;
        if ($queryTerkelola) {
            $totalSampah += $queryTerkelola->sum('jumlah_berat');
        }
        if ($queryDiserahkan) {
            $totalSampah += $queryDiserahkan->sum('jumlah_berat');
        }
        
        foreach ($kategoriList as $index => $kategori) {
            $totalJenis = 0;
            
            // Dapatkan semua jenis dengan kategori ini
            $jenisIds = Jenis::where('kategori_jenis', $kategori)->pluck('id_jenis')->toArray();
            
            if ($dataType === 'both' || $dataType === 'terkelola') {
                $query = SampahTerkelola::whereIn('id_jenis', $jenisIds)
                    ->where(function($query) use ($applyDateFilter) {
                        $applyDateFilter($query);
                    });
                if ($idInstansi) {
                    $query->whereHas('user', fn($q) => $q->where('id_instansi', $idInstansi));
                }
                $totalJenis += $query->sum('jumlah_berat');
            }

            if ($dataType === 'both' || $dataType === 'diserahkan') {
                $query = SampahDiserahkan::whereIn('id_jenis', $jenisIds)
                    ->where(function($query) use ($applyDateFilter) {
                        $applyDateFilter($query);
                    });
                if ($idInstansi) {
                    $query->whereHas('user', fn($q) => $q->where('id_instansi', $idInstansi));
                }
                $totalJenis += $query->sum('jumlah_berat');
            }
            
            $jenisTotals[] = $totalSampah > 0 ? round(($totalJenis / $totalSampah) * 100, 1) : 0;
        }
        
        // Mendapatkan semua lokasi asal untuk bar chart
        $lokasiAsals = LokasiAsal::all();
        $lokasiTotals = [];
        
        foreach ($lokasiAsals as $lokasi) {
            $totalLokasi = 0;
            
            if ($dataType === 'both' || $dataType === 'terkelola') {
                $query = SampahTerkelola::where('id_lokasi', $lokasi->id_lokasi)
                    ->where(function($query) use ($applyDateFilter) {
                        $applyDateFilter($query);
                    });
                if ($idInstansi) {
                    $query->whereHas('user', fn($q) => $q->where('id_instansi', $idInstansi));
                }
                $totalLokasi += $query->sum('jumlah_berat');
            }

            if ($dataType === 'both' || $dataType === 'diserahkan') {
                $query = SampahDiserahkan::where('id_lokasi', $lokasi->id_lokasi)
                    ->where(function($query) use ($applyDateFilter) {
                        $applyDateFilter($query);
                    });
                if ($idInstansi) {
                    $query->whereHas('user', fn($q) => $q->where('id_instansi', $idInstansi));
                }
                $totalLokasi += $query->sum('jumlah_berat');
            }

            $lokasiTotals[] = $totalLokasi;
        }
        
        // Data untuk tabel rekap
        $neraca = [];
        $totals = [
            'sampah_kg' => 0,
            'lb3_kg' => 0,
            'total_kg' => 0,
            'terkelola_kg' => 0,
            'persen_terkelola' => 0,
            'diserahkan_kg' => 0,
            'diserahkan_lb3_kg' => 0,
            'persen_diserahkan' => 0
        ];
        
        foreach ($lokasiAsals as $lokasi) {
            // Menghitung total sampah reguler dan LB3 berdasarkan jenis
            $sampahKg = 0;
            $lb3Kg = 0;
            $terkelolaKg = 0;
            $diserahkanKg = 0;
            $diserahkanLb3Kg = 0;

            // Hitung terkelola jika diinclude
            if ($dataType === 'both' || $dataType === 'terkelola') {
                $query = SampahTerkelola::where('id_lokasi', $lokasi->id_lokasi)
                    ->whereHas('jenis', function ($query) {
                        $query->whereNotIn('nama_jenis', ['LB3']);
                    })
                    ->where(function($q) use ($applyDateFilter) {
                        $applyDateFilter($q);
                    });
                if ($idInstansi) {
                    $query->whereHas('user', fn($q) => $q->where('id_instansi', $idInstansi));
                }
                $sampahKg = $query->sum('jumlah_berat');

                $query = SampahTerkelola::where('id_lokasi', $lokasi->id_lokasi)
                    ->whereHas('jenis', function ($query) {
                        $query->where('nama_jenis', 'LB3');
                    })
                    ->where(function($q) use ($applyDateFilter) {
                        $applyDateFilter($q);
                    });
                if ($idInstansi) {
                    $query->whereHas('user', fn($q) => $q->where('id_instansi', $idInstansi));
                }
                $lb3Kg = $query->sum('jumlah_berat');

                $query = SampahTerkelola::where('id_lokasi', $lokasi->id_lokasi)
                    ->where(function($q) use ($applyDateFilter) {
                        $applyDateFilter($q);
                    });
                if ($idInstansi) {
                    $query->whereHas('user', fn($q) => $q->where('id_instansi', $idInstansi));
                }
                $terkelolaKg = $query->sum('jumlah_berat');
            }

            // Hitung diserahkan jika diinclude
            if ($dataType === 'both' || $dataType === 'diserahkan') {
                $query = SampahDiserahkan::where('id_lokasi', $lokasi->id_lokasi)
                    ->whereHas('jenis', function ($query) {
                        $query->whereNotIn('nama_jenis', ['LB3']);
                    })
                    ->where(function($q) use ($applyDateFilter) {
                        $applyDateFilter($q);
                    });
                if ($idInstansi) {
                    $query->whereHas('user', fn($q) => $q->where('id_instansi', $idInstansi));
                }
                $diserahkanKg = $query->sum('jumlah_berat');

                $query = SampahDiserahkan::where('id_lokasi', $lokasi->id_lokasi)
                    ->whereHas('jenis', function ($query) {
                        $query->where('nama_jenis', 'LB3');
                    })
                    ->where(function($q) use ($applyDateFilter) {
                        $applyDateFilter($q);
                    });
                if ($idInstansi) {
                    $query->whereHas('user', fn($q) => $q->where('id_instansi', $idInstansi));
                }
                $diserahkanLb3Kg = $query->sum('jumlah_berat');
            }

            $totalKg = $sampahKg + $lb3Kg;
            $totalDiserahkan = $diserahkanKg + $diserahkanLb3Kg;
            $totalKeseluruhan = $terkelolaKg + $totalDiserahkan;
            
            // Persentase dari total keseluruhan (terkelola + diserahkan)
            $persenTerkelolaFromTotal = $totalKeseluruhan > 0 ? ($terkelolaKg / $totalKeseluruhan) * 100 : 0;
            $persenDiserahkanFromTotal = $totalKeseluruhan > 0 ? ($totalDiserahkan / $totalKeseluruhan) * 100 : 0;
            
            $neraca[] = [
                'sumber' => $lokasi->nama_lokasi,
                'sampah_kg' => $sampahKg,
                'lb3_kg' => $lb3Kg,
                'total_kg' => $totalKg,
                'terkelola_kg' => $terkelolaKg,
                'persen_terkelola' => $totalKeseluruhan > 0 ? ($terkelolaKg / $totalKeseluruhan) * 100 : 0,
                'diserahkan_kg' => $diserahkanKg,
                'diserahkan_lb3_kg' => $diserahkanLb3Kg,
                'persen_diserahkan' => $totalKeseluruhan > 0 ? ($totalDiserahkan / $totalKeseluruhan) * 100 : 0,
                'total_keseluruhan' => $totalKeseluruhan,
                'persen_terkelola_from_total' => $persenTerkelolaFromTotal,
                'persen_diserahkan_from_total' => $persenDiserahkanFromTotal
            ];
            
            // Menambahkan ke total
            $totals['sampah_kg'] += $sampahKg;
            $totals['lb3_kg'] += $lb3Kg;
            $totals['total_kg'] += $totalKg;
            $totals['terkelola_kg'] += $terkelolaKg;
            $totals['diserahkan_kg'] += $diserahkanKg;
            $totals['diserahkan_lb3_kg'] += $diserahkanLb3Kg;
        }
        
        // Menghitung total keseluruhan dan persentase total
        $totalKeseluruhanAll = $totals['terkelola_kg'] + $totals['diserahkan_kg'] + $totals['diserahkan_lb3_kg'];
        $totals['total_keseluruhan'] = $totalKeseluruhanAll;
        $totals['persen_terkelola'] = $totalKeseluruhanAll > 0 ? ($totals['terkelola_kg'] / $totalKeseluruhanAll) * 100 : 0;
        $totals['persen_diserahkan'] = $totalKeseluruhanAll > 0 ? (($totals['diserahkan_kg'] + $totals['diserahkan_lb3_kg']) / $totalKeseluruhanAll) * 100 : 0;
        $totals['persen_terkelola_from_total'] = $totalKeseluruhanAll > 0 ? ($totals['terkelola_kg'] / $totalKeseluruhanAll) * 100 : 0;
        $totals['persen_diserahkan_from_total'] = $totalKeseluruhanAll > 0 ? (($totals['diserahkan_kg'] + $totals['diserahkan_lb3_kg']) / $totalKeseluruhanAll) * 100 : 0;
        
        return view('superAdmin.dashboard', compact(
            'jenisSampah', 
            'jenisColors', 
            'jenisTotals', 
            'lokasiAsals', 
            'lokasiTotals', 
            'neraca', 
            'totals',
            'startDate',
            'endDate',
            'filterType',
            'idInstansi'
        ));
    }

    /**
     * Menampilkan data pengguna/petugas
     *
     * @return \Illuminate\View\View
     */
    public function masterUsers(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $users = User::where('role', '!=', 'superadmin')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
        return view('superAdmin.master.users', compact('users'));
    }

    /**
     * Menampilkan data sampah terkelola
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function masterSampahTerkelola(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $sampahTerkelolas = SampahTerkelola::with(['user.instansi', 'lokasiAsal', 'jenis'])
            ->orderBy('tgl', 'desc')
            ->orderBy('id', 'desc')
            ->paginate($perPage);
        
        $instansis = Instansi::all();
        
        return view('superAdmin.master.sampah_terkelola', compact('sampahTerkelolas', 'instansis'));
    }

    /**
     * Menampilkan data sampah diserahkan
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function masterSampahDiserahkan(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $sampahDiserahkans = SampahDiserahkan::with(['user.instansi', 'lokasiAsal', 'jenis', 'tujuanSampah'])
            ->orderBy('tgl_diserahkan', 'desc')
            ->orderBy('id', 'desc')
            ->paginate($perPage);
        
        $instansis = Instansi::all();
        
        return view('superAdmin.master.sampah_diserahkan', compact('sampahDiserahkans', 'instansis'));
    }

    /**
     * Menampilkan data lokasi asal sampah
     *
     * @return \Illuminate\View\View
     */
    public function masterLokasiAsal()
    {
        $lokasiAsals = LokasiAsal::all();
        return view('superAdmin.master.lokasi_asal', compact('lokasiAsals'));
    }

    /**
     * Menampilkan data jenis sampah
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function masterJenisSampah(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $jenisSampah = Jenis::orderBy('created_at', 'desc')
            ->paginate($perPage);
        return view('superAdmin.master.jenis_sampah', compact('jenisSampah'));
    }

    /**
     * Menampilkan data tujuan sampah
     *
     * @return \Illuminate\View\View
     */
    public function masterTujuanSampah()
    {
        $tujuanSampah = TujuanSampah::all();
        return view('superAdmin.master.tujuan_sampah', compact('tujuanSampah'));
    }
    
    /**
     * Menampilkan data dokumen
     *
     * @return \Illuminate\View\View
     */
    public function masterDokumen(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $dokumen = Dokumen::orderBy('created_at', 'desc')->paginate($perPage);
        return view('superAdmin.master.dokumen', compact('dokumen'));
    }
    
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
            $month = Carbon::parse($diserahkan->tgl)->month;
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

    /**
     * Menampilkan halaman master instansi
     */
    public function masterInstansi(Request $request)
    {
        $search = $request->get('search');
        
        $instansis = \App\Models\Instansi::query()
            ->when($search, function($q) use ($search) {
                $q->where('nama_instansi', 'like', "%{$search}%")
                  ->orWhere('kode_instansi', 'like', "%{$search}%");
            })
            ->orderBy('nama_instansi', 'asc')
            ->paginate(10);
        
        return view('superAdmin.master.instansi.index', compact('instansis'));
    }

    /**
     * Menampilkan form tambah instansi
     */
    public function createInstansi()
    {
        return view('superAdmin.master.instansi.tambah');
    }

    /**
     * Menyimpan instansi baru
     */
    public function storeInstansi(Request $request)
    {
        $validated = $request->validate([
            'nama_instansi' => 'required|string|max:255',
            'kode_instansi' => 'required|string|max:50|unique:instansis,kode_instansi',
        ], [
            'nama_instansi.required' => 'Nama instansi harus diisi',
            'kode_instansi.required' => 'Kode instansi harus diisi',
            'kode_instansi.unique' => 'Kode instansi sudah digunakan',
        ]);

        \App\Models\Instansi::create($validated);

        return redirect()->route('superadmin.master.instansi')
            ->with('success', 'Instansi berhasil ditambahkan');
    }

    /**
     * Menampilkan form edit instansi
     */
    public function editInstansi($id)
    {
        $instansi = \App\Models\Instansi::findOrFail($id);
        return view('superAdmin.master.instansi.edit', compact('instansi'));
    }

    /**
     * Update instansi
     */
    public function updateInstansi(Request $request, $id)
    {
        $instansi = \App\Models\Instansi::findOrFail($id);

        $validated = $request->validate([
            'nama_instansi' => 'required|string|max:255',
            'kode_instansi' => 'required|string|max:50|unique:instansis,kode_instansi,' . $id . ',id_instansi',
        ], [
            'nama_instansi.required' => 'Nama instansi harus diisi',
            'kode_instansi.required' => 'Kode instansi harus diisi',
            'kode_instansi.unique' => 'Kode instansi sudah digunakan',
        ]);

        $instansi->update($validated);

        return redirect()->route('superadmin.master.instansi')
            ->with('success', 'Instansi berhasil diperbarui');
    }

    /**
     * Hapus instansi
     */
    public function deleteInstansi($id)
    {
        $instansi = \App\Models\Instansi::findOrFail($id);
        
        // Check if instansi has users
        if ($instansi->users()->count() > 0) {
            return back()->with('error', 'Instansi tidak dapat dihapus karena masih memiliki user terkait');
        }

        $instansi->delete();

        return redirect()->route('superadmin.master.instansi')
            ->with('success', 'Instansi berhasil dihapus');
    }
}
