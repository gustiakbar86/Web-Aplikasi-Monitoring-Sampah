<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SampahTerkelola;
use App\Models\SampahDiserahkan;
use App\Models\Jenis;
use App\Models\LokasiAsal;
use App\Models\TujuanSampah;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Helpers\FileUploadHelper;

class PetugasController extends Controller
{
    /**
     * Menampilkan halaman dashboard petugas
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        $user = Auth::guard('web')->user();
        $instansi = $user->instansi; // Ambil data instansi
        
        return view('petugas.dashboard', compact('user', 'instansi'));
    }

    /**
     * Menampilkan daftar sampah terkelola (dari user yang login)
     *
     * @return \Illuminate\View\View
     */
    public function sampahTerkelola()
    {
        $user = Auth::guard('web')->user();
        $userId = $user->id;
        
        // Ambil semua data sampah terkelola dari user yang login
        $sampahTerkelolas = SampahTerkelola::with(['user', 'lokasiAsal', 'jenis'])
            ->where('id_user', $userId)
            ->orderBy('tgl', 'desc')
            ->orderBy('id', 'desc')
            ->get();
        
        return view('petugas.sampah_terkelola', compact('sampahTerkelolas', 'user'));
    }
    
    /**
     * Menampilkan daftar sampah diserahkan (dari user yang login)
     *
     * @return \Illuminate\View\View
     */
    public function sampahDiserahkan()
    {
        $user = Auth::guard('web')->user();
        $userId = $user->id;
        
        // Ambil semua data sampah diserahkan dari user yang login
        $sampahDiserahkans = SampahDiserahkan::with(['user', 'lokasiAsal', 'jenis', 'tujuanSampah'])
            ->where('id_user', $userId)
            ->orderBy('tgl_diserahkan', 'desc')
            ->orderBy('id', 'desc')
            ->get();
        
        return view('petugas.sampah_diserahkan', compact('sampahDiserahkans', 'user'));
    }
    
    /**
     * Menampilkan form input sampah terkelola
     *
     * @return \Illuminate\View\View
     */
    public function inputSampahTerkelola()
    {
        $lokasiAsals = LokasiAsal::all();
        $jenis = Jenis::all();
        // Untuk sampah terkelola, hanya Organik dan Anorganik
        $kategoriJenises = ['organik', 'anorganik'];
        
        return view('petugas.input_sampah_terkelola', compact('lokasiAsals', 'jenis', 'kategoriJenises'));
    }
    
    /**
     * Menyimpan data sampah terkelola baru
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeSampahTerkelola(Request $request)
    {
        $validatedData = $request->validate([
            'id_user' => 'required|exists:users,id',
            'id_lokasi' => 'required|exists:lokasi_asals,id_lokasi',
            'id_jenis' => 'required|exists:jenis,id_jenis',
            'jumlah_berat' => 'required|numeric|min:0',
            'tgl' => 'required|date',
            'foto_kelola' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        if ($request->hasFile('foto_kelola')) {
            $validatedData['foto_kelola'] = FileUploadHelper::uploadFotoSampah($request->file('foto_kelola'));
        }
        
        SampahTerkelola::create($validatedData);
        
        return redirect()->route('petugas.sampah-terkelola')->with('success', 'Data sampah terkelola berhasil disimpan.');
    }
    
    /**
     * Menampilkan form input sampah diserahkan
     *
     * @return \Illuminate\View\View
     */
    public function inputSampahDiserahkan()
    {
        $lokasiAsals = LokasiAsal::all();
        $jenis = Jenis::all();
        $tujuanSampahs = TujuanSampah::all();
        // Untuk sampah diserahkan, hanya Residu
        $kategoriJenises = ['residu'];
        
        return view('petugas.input_sampah_diserahkan', compact('lokasiAsals', 'jenis', 'tujuanSampahs', 'kategoriJenises'));
    }
    
    /**
     * Menyimpan data sampah diserahkan baru
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeSampahDiserahkan(Request $request)
    {
        $validatedData = $request->validate([
            'id_user' => 'required|exists:users,id',
            'id_lokasi' => 'required|exists:lokasi_asals,id_lokasi',
            'id_jenis' => 'required|exists:jenis,id_jenis',
            'jumlah_berat' => 'required|numeric|min:0',
            'tgl_diserahkan' => 'required|date',
            'id_tujuan' => 'required|exists:tujuan_sampahs,id_tujuan',
            'foto_diserahkan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        if ($request->hasFile('foto_diserahkan')) {
            $validatedData['foto_diserahkan'] = FileUploadHelper::uploadFotoSampah($request->file('foto_diserahkan'));
        }
        
        SampahDiserahkan::create($validatedData);
        
        return redirect()->route('petugas.sampah-diserahkan')->with('success', 'Data sampah diserahkan berhasil disimpan.');
    }
    
    /**
     * API untuk statistik dashboard (filter by user id)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStatistik(Request $request)
    {
        $user = Auth::guard('web')->user();
        $userId = $user->id;
        $period = $request->get('period', 'weekly');
        $type = $request->get('type', 'both');
        
        // Setup date range based on period
        $dateRange = $this->getDateRange($period);
        
        // Query data berdasarkan user id (bukan instansi)
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
        
        // Hitung distribusi per jenis dengan kategori
        $distribution = [];
        
        // Ambil kategori unik dari database (selalu 3: Organik, Anorganik, Residu)
        $categories = Jenis::distinct('kategori_jenis')
            ->pluck('kategori_jenis')
            ->sort()
            ->values()
            ->toArray();
        
        // Hitung total berat per kategori
        foreach ($categories as $category) {
            $total = $data->filter(function($item) use ($category) {
                return $item->jenis->kategori_jenis === $category;
            })->sum('jumlah_berat');
            
            $distribution[$category] = (float)round($total, 2);
        }
        
        // Hitung trend berat per hari/tanggal
        $trendData = $this->getTrendData($data, $period);
        
        return response()->json([
            'distribution' => $distribution,
            'trend' => $trendData
        ]);
    }
    
    /**
     * Helper: Get date range based on period
     */
    private function getDateRange($period)
    {
        $now = now();
        
        switch ($period) {
            case 'daily':
                return [$now->copy()->startOfDay(), $now->copy()->endOfDay()];
            case 'monthly':
                return [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()];
            case 'yearly':
                return [$now->copy()->startOfYear(), $now->copy()->endOfYear()];
            case 'weekly':
            default:
                // Senin adalah hari pertama minggu (dayOfWeek = 1)
                $startOfWeek = $now->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
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
            } elseif ($period === 'yearly') {
                $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                $labels = $months;
                $values = array_fill(0, 12, 0);
            } else { // weekly
                $dayNames = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                $labels = $dayNames;
                $values = array_fill(0, 7, 0);
            }
            return ['labels' => $labels, 'values' => $values];
        }
        
        $groupedData = $data->groupBy(function($item) use ($period) {
            $date = $item->tgl ?? $item->tgl_diserahkan;
            $carbonDate = \Carbon\Carbon::parse($date);
            
            if ($period === 'daily') {
                return $carbonDate->format('H:00');
            } elseif ($period === 'monthly') {
                return $carbonDate->format('d');
            } elseif ($period === 'yearly') {
                return $carbonDate->format('m'); // 01-12
            } else { // weekly - use day of week (1=Monday, 7=Sunday)
                return $carbonDate->dayOfWeek; // 1=Senin, 7=Minggu
            }
        });
        
        if ($period === 'daily') {
            // Pastikan semua jam dari 0-23 muncul
            for ($i = 0; $i < 24; $i++) {
                $label = sprintf('%02d:00', $i);
                $labels[] = $label;
                $values[] = isset($groupedData[$label]) ? round($groupedData[$label]->sum('jumlah_berat'), 2) : 0;
            }
        } elseif ($period === 'monthly') {
            // Urutkan berdasarkan tanggal 1-31
            for ($i = 1; $i <= 31; $i++) {
                $labels[] = sprintf('%02d', $i);
                $values[] = isset($groupedData[(string)$i]) ? round($groupedData[(string)$i]->sum('jumlah_berat'), 2) : 0;
            }
        } elseif ($period === 'yearly') {
            // Urutkan berdasarkan bulan 1-12
            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            for ($i = 1; $i <= 12; $i++) {
                $labels[] = $months[$i - 1];
                $monthStr = sprintf('%02d', $i);
                $values[] = isset($groupedData[$monthStr]) ? round($groupedData[$monthStr]->sum('jumlah_berat'), 2) : 0;
            }
        } else { // weekly
            // Urutkan dari Senin (1) sampai Minggu (7)
            $dayNames = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
            
            for ($i = 1; $i <= 7; $i++) {
                $dayIndex = ($i === 7) ? 6 : ($i - 1); // Convert 1-7 to 0-6 for array index
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
     * Form edit sampah terkelola
     */
    public function editSampahTerkelola($id)
    {
        $sampah = SampahTerkelola::with('jenis')->findOrFail($id);
        
        // Ambil user yang login
        $user = Auth::guard('web')->user();
        
        // Filter data berdasarkan instansi user yang login
        $lokasiAsals = LokasiAsal::all();
        $jenis = Jenis::all();
        // Untuk sampah terkelola, hanya Organik dan Anorganik
        $kategoriJenises = ['organik', 'anorganik'];
        
        return view('petugas.edit_sampah_terkelola', compact('sampah', 'lokasiAsals', 'jenis', 'kategoriJenises', 'user'));
    }
    
    /**
     * Update sampah terkelola
     */
    public function updateSampahTerkelola(Request $request, $id)
    {
        $sampah = SampahTerkelola::findOrFail($id);
        
        $validatedData = $request->validate([
            'id_user' => 'required|exists:users,id',
            'id_lokasi' => 'required|exists:lokasi_asals,id_lokasi',
            'kategori_jenis' => 'required|string',
            'id_jenis' => 'required|exists:jenis,id_jenis',
            'jumlah_berat' => 'required|numeric|min:0',
            'tgl' => 'required|date',
            'foto_kelola' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'alasan_edit' => 'required|string|max:500',
        ]);
        
        // Remove kategori_jenis from validated data as it's not a database field
        unset($validatedData['kategori_jenis']);
        
        if ($request->hasFile('foto_kelola')) {
            // Hapus foto lama jika ada
            FileUploadHelper::deleteFile($sampah->foto_kelola);
            
            $sampah->foto_kelola = FileUploadHelper::uploadFotoSampah($request->file('foto_kelola'));
        }
        
        $sampah->update($validatedData);
        
        return redirect()->route('petugas.sampah-terkelola')
            ->with('success', 'Data sampah terkelola berhasil diperbarui.');
    }
    
    /**
     * Form edit sampah diserahkan
     */
    public function editSampahDiserahkan($id)
    {
        $sampah = SampahDiserahkan::with('jenis')->findOrFail($id);
        
        // Ambil user yang login
        $user = Auth::guard('web')->user();
        
        // Filter data berdasarkan instansi user yang login
        $lokasiAsals = LokasiAsal::all();
        $jenis = Jenis::all();
        $tujuanSampahs = TujuanSampah::all();
        // Untuk sampah diserahkan, hanya Residu
        $kategoriJenises = ['residu'];
        
        return view('petugas.edit_sampah_diserahkan', compact('sampah', 'lokasiAsals', 'jenis', 'tujuanSampahs', 'kategoriJenises', 'user'));
    }
    
    /**
     * Update sampah diserahkan
     */
    public function updateSampahDiserahkan(Request $request, $id)
    {
        $sampah = SampahDiserahkan::findOrFail($id);
        
        $validatedData = $request->validate([
            'id_user' => 'required|exists:users,id',
            'id_lokasi' => 'required|exists:lokasi_asals,id_lokasi',
            'kategori_jenis' => 'required|string',
            'id_jenis' => 'required|exists:jenis,id_jenis',
            'id_tujuan' => 'required|exists:tujuan_sampahs,id_tujuan',
            'jumlah_berat' => 'required|numeric|min:0',
            'tgl_diserahkan' => 'required|date',
            'foto_diserahkan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'alasan_edit' => 'required|string|max:500',
        ]);
        
        // Remove kategori_jenis from validated data as it's not a database field
        unset($validatedData['kategori_jenis']);
        
        if ($request->hasFile('foto_diserahkan')) {
            // Hapus foto lama jika ada
            FileUploadHelper::deleteFile($sampah->foto_diserahkan);
            
            $sampah->foto_diserahkan = FileUploadHelper::uploadFotoSampah($request->file('foto_diserahkan'));
        }
        
        $sampah->update($validatedData);
        
        return redirect()->route('petugas.sampah-diserahkan')
            ->with('success', 'Data sampah diserahkan berhasil diperbarui.');
    }
}
