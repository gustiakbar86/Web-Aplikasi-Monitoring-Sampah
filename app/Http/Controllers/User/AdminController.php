<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Jenis;
use App\Models\LokasiAsal;
use App\Models\SampahDiserahkan;
use App\Models\SampahTerkelola;
use App\Models\TujuanSampah;
use App\Models\User;
use App\Models\Dokumen;
use App\Models\Instansi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Menampilkan dashboard admin dengan statistik data sampah
     */
    public function dashboard(Request $request)
    {
        // Set default filter - use fiscal year like Super Admin untuk consistency
        $filterType = $request->filter_type ?? 'fiscal';
        $year = $request->year ?? date('Y');
        $month = $request->month ?? date('m');
        $week = $request->week ?? 1;
        $day = $request->day ?? date('Y-m-d');
        $idInstansi = $request->id_instansi;
        
        // Determine date range
        $useDateRange = false;
        $startDate = null;
        $endDate = null;
        $periodText = '';
        
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->get('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->get('end_date'))->endOfDay();
            $useDateRange = true;
            $periodText = $startDate->format('d F Y') . ' - ' . $endDate->format('d F Y');
        } elseif ($filterType === 'fiscal') {
            // Fiscal year: Jul-Jun (always default to current fiscal year 2025/2026)
            $fiscalEnd = $request->get('fiscal_year', 2026);
            $startDate = Carbon::create($fiscalEnd - 1, 7, 1)->startOfDay();
            $endDate = Carbon::create($fiscalEnd, 6, 30)->endOfDay();
            $useDateRange = true;
            $periodText = "Fiscal Year " . ($fiscalEnd - 1) . "/" . $fiscalEnd . " (Jul " . ($fiscalEnd - 1) . " - Jun $fiscalEnd)";
        } else {
            // Existing granular filters
            $periodText = '';
        }

        // Query untuk data berdasarkan filter
        $sampahTerkelolaQuery = SampahTerkelola::query()
            ->join('lokasi_asals', 'sampah_terkelolas.id_lokasi', '=', 'lokasi_asals.id_lokasi')
            ->join('jenis', 'sampah_terkelolas.id_jenis', '=', 'jenis.id_jenis')
            ->select(
                'lokasi_asals.nama_lokasi as lokasi',
                'jenis.kategori_jenis as jenis',
                DB::raw('SUM(sampah_terkelolas.jumlah_berat) as total_berat')
            );

        $sampahDiserahkanQuery = SampahDiserahkan::query()
            ->join('lokasi_asals', 'sampah_diserahkans.id_lokasi', '=', 'lokasi_asals.id_lokasi')
            ->join('jenis', 'sampah_diserahkans.id_jenis', '=', 'jenis.id_jenis')
            ->join('tujuan_sampahs', 'sampah_diserahkans.id_tujuan', '=', 'tujuan_sampahs.id_tujuan')
            ->select(
                'lokasi_asals.nama_lokasi as lokasi',
                'jenis.kategori_jenis as jenis',
                'tujuan_sampahs.nama_tujuan as tujuan',
                DB::raw('SUM(sampah_diserahkans.jumlah_berat) as total_berat')
            );
        
        // Filter berdasarkan instansi jika dipilih
        if ($idInstansi) {
            $sampahTerkelolaQuery->whereHas('user', fn($q) => $q->where('id_instansi', $idInstansi));
            $sampahDiserahkanQuery->whereHas('user', fn($q) => $q->where('id_instansi', $idInstansi));
        }

        // Apply date filter
        if ($useDateRange) {
            $sampahTerkelolaQuery->whereBetween('sampah_terkelolas.tgl', [$startDate, $endDate]);
            $sampahDiserahkanQuery->whereBetween('sampah_diserahkans.tgl_diserahkan', [$startDate, $endDate]);
        } else {
            // Filter berdasarkan tipe filter yang dipilih
            switch ($filterType) {
                case 'year':
                    $sampahTerkelolaQuery->whereYear('sampah_terkelolas.tgl', $year);
                    $sampahDiserahkanQuery->whereYear('sampah_diserahkans.tgl_diserahkan', $year);
                    $periodText = "Tahun $year";
                    break;

                case 'month':
                    $sampahTerkelolaQuery->whereYear('sampah_terkelolas.tgl', $year)->whereMonth('sampah_terkelolas.tgl', $month);
                    $sampahDiserahkanQuery->whereYear('sampah_diserahkans.tgl_diserahkan', $year)->whereMonth('sampah_diserahkans.tgl_diserahkan', $month);
                    $periodText = "Bulan " . Carbon::createFromDate($year, $month, 1)->format('F Y');
                    break;

                case 'week':
                    // Hitung tanggal awal dan akhir dari minggu yang dipilih
                    $firstDayOfMonth = Carbon::createFromDate($year, $month, 1);
                    $weekStart = $firstDayOfMonth->copy()->addDays(($week - 1) * 7);       
                    $weekEnd = $weekStart->copy()->addDays(6);

                    $sampahTerkelolaQuery->whereBetween('sampah_terkelolas.tgl', [$weekStart, $weekEnd]);    
                    $sampahDiserahkanQuery->whereBetween('sampah_diserahkans.tgl_diserahkan', [$weekStart, $weekEnd]);   
                    $periodText = "Minggu $week, " . $weekStart->format('d') . " - " . $weekEnd->format('d F Y');
                    break;

                case 'day':
                    $sampahTerkelolaQuery->whereDate('sampah_terkelolas.tgl', $day);
                    $sampahDiserahkanQuery->whereDate('sampah_diserahkans.tgl_diserahkan', $day);
                    $periodText = "Tanggal " . Carbon::parse($day)->format('d F Y');       
                    break;
            }
        }

        // Grup hasil berdasarkan lokasi dan kategori jenis
        $sampahTerkelolaQuery->groupBy('lokasi_asals.nama_lokasi', 'jenis.kategori_jenis');
        $sampahDiserahkanQuery->groupBy('lokasi_asals.nama_lokasi', 'jenis.kategori_jenis', 'tujuan_sampahs.nama_tujuan');

        // Ambil hasil query
        $sampahTerkelola = $sampahTerkelolaQuery->get();
        $sampahDiserahkan = $sampahDiserahkanQuery->get();

        // Olah data untuk chart kategori jenis sampah
        $kategoriJenis = Jenis::distinct('kategori_jenis')->pluck('kategori_jenis');
        
        // Hitung total sampah keseluruhan untuk persentase
        $totalSampahKeseluruhan = 0;
        foreach ($sampahTerkelola as $item) {
            $totalSampahKeseluruhan += $item->total_berat;
        }
        foreach ($sampahDiserahkan as $item) {
            $totalSampahKeseluruhan += $item->total_berat;
        }
        
        $jenisSampahDataTerkelola = [];
        $jenisSampahDataDiserahkan = [];
        $jenisSampahLabels = [];
        $jenisSampahColors = [
            'rgb(255, 0, 0)',    // Red
            'rgb(0, 128, 0)',     // Green
            'rgb(255, 255, 0)',   // Yellow
            'rgb(0, 0, 255)',     // Blue
            'rgb(128, 0, 128)',   // Purple
            'rgb(255, 165, 0)'    // Orange
        ];

        foreach ($kategoriJenis as $index => $kategori) {
            $jenisSampahLabels[] = $kategori;

            // Hitung total sampah terkelola per kategori
            $totalTerkelola = $sampahTerkelola
                ->where('jenis', $kategori)
                ->sum('total_berat');

            $jenisSampahDataTerkelola[] = $totalTerkelola;

            // Hitung total sampah diserahkan per kategori
            $totalDiserahkan = $sampahDiserahkan
                ->where('jenis', $kategori)
                ->sum('total_berat');

            $jenisSampahDataDiserahkan[] = $totalDiserahkan;
        }

        // Olah data untuk chart total sampah per lokasi
        $lokasiSampah = LokasiAsal::all();
        $lokasiSampahLabels = [];
        $lokasiSampahDataTerkelola = [];
        $lokasiSampahDataDiserahkan = [];

        foreach ($lokasiSampah as $lokasi) {
            $lokasiSampahLabels[] = $lokasi->nama_lokasi;

            // Hitung total sampah terkelola per lokasi
            $totalTerkelolaLokasi = $sampahTerkelola
                ->where('lokasi', $lokasi->nama_lokasi)
                ->sum('total_berat');
            
            // Hitung total sampah diserahkan per lokasi
            $totalDiserahkanLokasi = $sampahDiserahkan
                ->where('lokasi', $lokasi->nama_lokasi)
                ->sum('total_berat');

            // Simpan data terpisah
            $lokasiSampahDataTerkelola[] = round($totalTerkelolaLokasi, 2);
            $lokasiSampahDataDiserahkan[] = round($totalDiserahkanLokasi, 2);
        }

        // Data untuk tabel rekap
        $rekapData = [];
        foreach ($lokasiSampah as $lokasi) {
            $lokasiNama = $lokasi->nama_lokasi;

            // Total sampah terkelola
            $totalTerkelola = $sampahTerkelola
                ->where('lokasi', $lokasiNama)
                ->sum('total_berat');

            // Total sampah diserahkan
            $totalDiserahkan = $sampahDiserahkan
                ->where('lokasi', $lokasiNama)
                ->sum('total_berat');

            // Total keseluruhan
            $totalKeseluruhan = $totalTerkelola + $totalDiserahkan;
            
            // Persentase terkelola (dari total keseluruhan)
            $persenTerkelola = $totalKeseluruhan > 0
                ? round(($totalTerkelola / $totalKeseluruhan) * 100, 2)
                : 0;
            
            // Persentase diserahkan (dari total keseluruhan)
            $persenDiserahkan = $totalKeseluruhan > 0
                ? round(($totalDiserahkan / $totalKeseluruhan) * 100, 2)
                : 0;

            $rekapData[] = [
                'lokasi' => $lokasiNama,
                'terkelola' => round($totalTerkelola, 2),
                'persen_terkelola' => $persenTerkelola,
                'diserahkan' => round($totalDiserahkan, 2),
                'persen_diserahkan' => $persenDiserahkan,
                'total_keseluruhan' => round($totalKeseluruhan, 2),
            ];
        }

        // Hitung total keseluruhan
        $totalTerkelola = array_sum(array_column($rekapData, 'terkelola'));
        $totalDiserahkan = array_sum(array_column($rekapData, 'diserahkan'));
        $totalKeseluruhan = array_sum(array_column($rekapData, 'total_keseluruhan'));

        $persenTerkelolaTotal = $totalKeseluruhan > 0
            ? round(($totalTerkelola / $totalKeseluruhan) * 100, 2)
            : 0;

        $persenDiserahkanTotal = $totalKeseluruhan > 0
            ? round(($totalDiserahkan / $totalKeseluruhan) * 100, 2)
            : 0;

        $instansis = Instansi::all();
        
        return view('admin.dashboard', [
            'periodText' => $periodText,
            'jenisSampahLabels' => json_encode($jenisSampahLabels),
            'jenisSampahDataTerkelola' => json_encode($jenisSampahDataTerkelola),
            'jenisSampahDataDiserahkan' => json_encode($jenisSampahDataDiserahkan),
            'jenisSampahColors' => json_encode($jenisSampahColors),
            'lokasiSampahLabels' => json_encode($lokasiSampahLabels),
            'lokasiSampahDataTerkelola' => json_encode($lokasiSampahDataTerkelola),
            'lokasiSampahDataDiserahkan' => json_encode($lokasiSampahDataDiserahkan),
            'rekapData' => $rekapData,
            'totalTerkelola' => round($totalTerkelola, 2),
            'totalDiserahkan' => round($totalDiserahkan, 2),
            'totalKeseluruhan' => round($totalKeseluruhan, 2),
            'persenTerkelolaTotal' => $persenTerkelolaTotal,
            'persenDiserahkanTotal' => $persenDiserahkanTotal,
            'instansis' => $instansis,
            'selectedInstansi' => $idInstansi
        ]);
    }

    /**
     * Menampilkan halaman kelola petugas
     */
    public function kelolaPetugas()
    {
        $petugas = User::with('instansi')->get();
        return view('admin.kelola-petugas.index', compact('petugas'));
    }
    
    /**
     * Menampilkan form tambah petugas
     */
    public function tambahPetugas()
    {
        $instansis = Instansi::all();
        return view('admin.kelola-petugas.tambah', compact('instansis'));
    }
    
    /**
     * Menyimpan data petugas baru
     */
    public function storePetugas(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'id_instansi' => 'required|exists:instansis,id_instansi',
        ]);
        
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'id_instansi' => $request->id_instansi,
        ]);
        
        return redirect()->route('admin.kelola-petugas')
            ->with('success', 'Petugas berhasil ditambahkan');
    }
    
    /**
     * Menampilkan form edit petugas
     */
    public function editPetugas($id)
    {
        $petugas = User::findOrFail($id);
        $instansis = Instansi::all();
        return view('admin.kelola-petugas.edit', compact('petugas', 'instansis'));
    }
    
    /**
     * Memperbarui data petugas
     */
    public function updatePetugas(Request $request, $id)
    {
        $petugas = User::findOrFail($id);
        
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
            'id_instansi' => 'required|exists:instansis,id_instansi',
        ];
        
        if($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }
        
        $request->validate($rules);
        
        $petugas->name = $request->name;
        $petugas->email = $request->email;
        $petugas->id_instansi = $request->id_instansi;
        
        if($request->filled('password')) {
            $petugas->password = bcrypt($request->password);
        }
        
        $petugas->save();
        
        return redirect()->route('admin.kelola-petugas')
            ->with('success', 'Data petugas berhasil diperbarui');
    }
    
    /**
     * Menghapus data petugas
     */
    public function deletePetugas($id)
    {
        $petugas = User::findOrFail($id);
        $petugas->delete();
        
        return redirect()->route('admin.kelola-petugas')
            ->with('success', 'Petugas berhasil dihapus');
    }
    
    // Admin tidak memiliki akses untuk menambah/edit/hapus data sampah
    // Admin hanya dapat melihat data sampah yang sudah ada
    
    /**
     * Menampilkan data sampah terkelola
     */
    public function dataSampahTerkelola(Request $request)
    {
        $perPage = $request->get('per_page', 50);
        
        $sampahTerkelolas = SampahTerkelola::with(['user.instansi', 'lokasiAsal', 'jenis'])
            ->orderBy('sampah_terkelolas.tgl', 'desc')
            ->orderBy('sampah_terkelolas.id', 'desc')
            ->paginate($perPage);
        
        $instansis = Instansi::all();
            
        return view('admin.data.sampah-terkelola', compact('sampahTerkelolas', 'instansis'));
    }
    
    /**
     * Menampilkan data sampah diserahkan
     */
    public function dataSampahDiserahkan(Request $request)
    {
        $perPage = $request->get('per_page', 50);
        
        $sampahDiserahkans = SampahDiserahkan::with(['user.instansi', 'lokasiAsal', 'jenis', 'tujuanSampah'])
            ->orderBy('tgl_diserahkan', 'desc')
            ->orderBy('id', 'desc')
            ->paginate($perPage);
        
        $instansis = Instansi::all();
            
        return view('admin.data.sampah-diserahkan', compact('sampahDiserahkans', 'instansis'));
    }
    
    /**
     * Menampilkan data dokumen (redirect ke dokumenIndex untuk backward compatibility)
     */
    public function dataDokumen(Request $request)
    {
        // Redirect to the new dokumen index page
        return redirect()->route('admin.dokumen.index');
    }
    
    /**
     * Menampilkan semua dokumen
     */
    public function dokumenIndex()
    {
        $dokumens = Dokumen::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.dokumen.dokumen', compact('dokumens'));
    }
    
    /**
     * Menampilkan form tambah dokumen
     */
    public function dokumenCreate()
    {
        return view('admin.dokumen.tambah-dokumen');
    }
    
    /**
     * Menyimpan dokumen baru
     */
    public function dokumenStore(Request $request)
    {
        $request->validate([
            'judul_dokumen' => 'required|string|max:255',
            'file_dokumen' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'berlaku' => 'required|date',
            'berakhir' => 'required|date|after_or_equal:berlaku',
        ]);
        
        $file = $request->file('file_dokumen');
        $filePath = $file->store('dokumen', 'public');
        
        Dokumen::create([
            'id_user' => Auth::id(),
            'no_dokumen' => 'DOK-' . date('YmdHis'),
            'judul_dokumen' => $request->judul_dokumen,
            'file_dokumen' => $filePath,
            'instansi_kerjasama' => 'Pelindo Subregional Banjarmasin',
            'berlaku' => $request->berlaku,
            'berakhir' => $request->berakhir,
            'keterangan_dokumen' => $request->keterangan_dokumen,
        ]);
        
        return redirect()->route('admin.dokumen.index')
            ->with('success', 'Dokumen berhasil ditambahkan');
    }
    
    /**
     * Menampilkan form edit dokumen
     */
    public function dokumenEdit($id)
    {
        $dokumen = Dokumen::findOrFail($id);
        return view('admin.dokumen.edit-dokumen', compact('dokumen'));
    }
    
    /**
     * Update dokumen
     */
    public function dokumenUpdate(Request $request, $id)
    {
        $dokumen = Dokumen::findOrFail($id);
        
        $request->validate([
            'judul_dokumen' => 'required|string|max:255',
            'file_dokumen' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'berlaku' => 'required|date',
            'berakhir' => 'required|date|after_or_equal:berlaku',
        ]);
        
        $data = [
            'judul_dokumen' => $request->judul_dokumen,
            'berlaku' => $request->berlaku,
            'berakhir' => $request->berakhir,
            'keterangan_dokumen' => $request->keterangan_dokumen,
        ];
        
        if ($request->hasFile('file_dokumen')) {
            // Hapus file lama jika ada
            if ($dokumen->file_dokumen && Storage::disk('public')->exists($dokumen->file_dokumen)) {
                Storage::disk('public')->delete($dokumen->file_dokumen);
            }
            
            // Simpan file baru
            $file = $request->file('file_dokumen');
            $data['file_dokumen'] = $file->store('dokumen', 'public');
        }
        
        $dokumen->update($data);
        
        return redirect()->route('admin.dokumen.index')
            ->with('success', 'Dokumen berhasil diperbarui');
    }
    
    /**
     * Hapus dokumen
     */
    public function dokumenDestroy($id)
    {
        $dokumen = Dokumen::findOrFail($id);
        
        // Hapus file jika ada
        if ($dokumen->file_dokumen && Storage::disk('public')->exists($dokumen->file_dokumen)) {
            Storage::disk('public')->delete($dokumen->file_dokumen);
        }
        
        $dokumen->delete();
        
        return redirect()->route('admin.dokumen.index')
            ->with('success', 'Dokumen berhasil dihapus');
    }
    
    /**
     * Menampilkan halaman master data lokasi asal
     */
    public function masterLokasiAsal()
    {
        $lokasiAsal = LokasiAsal::all();
        return view('admin.master.lokasi-asal', compact('lokasiAsal'));
    }
    
    /**
     * Menyimpan data lokasi asal baru
     */
    public function storeLokasiAsal(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:lokasi_asals,nama_lokasi',
        ]);
        
        LokasiAsal::create([
            'nama_lokasi' => $request->nama
        ]);
        
        return redirect()->route('admin.master.lokasi-asal')
            ->with('success', 'Lokasi asal berhasil ditambahkan');
    }
    
    /**
     * Memperbarui data lokasi asal
     */
    public function updateLokasiAsal(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:lokasi_asals,nama_lokasi,'.$id,
        ]);
        
        $lokasiAsal = LokasiAsal::findOrFail($id);
        $lokasiAsal->nama_lokasi = $request->nama;
        $lokasiAsal->save();
        
        return redirect()->route('admin.master.lokasi-asal')
            ->with('success', 'Lokasi asal berhasil diperbarui');
    }
    
    /**
     * Menghapus data lokasi asal
     */
    public function deleteLokasiAsal($id)
    {
        $lokasiAsal = LokasiAsal::findOrFail($id);
        $lokasiAsal->delete();
        
        return redirect()->route('admin.master.lokasi-asal')
            ->with('success', 'Lokasi asal berhasil dihapus');
    }
    
    /**
     * Menampilkan halaman master data jenis sampah
     */
    public function masterJenisSampah()
    {
        $jenisSampah = Jenis::all();
        return view('admin.master.jenis-sampah', compact('jenisSampah'));
    }
    
    /**
     * Menyimpan data jenis sampah baru
     */
    public function storeJenisSampah(Request $request)
    {
        $request->validate([
            'kategori_jenis' => 'required|string|in:Organik,Anorganik,Residu',
            'nama_jenis' => 'required|string|max:255|unique:jenis,nama_jenis',
        ]);
        
        Jenis::create([
            'kategori_jenis' => $request->kategori_jenis,
            'nama_jenis' => $request->nama_jenis
        ]);
        
        return redirect()->route('admin.master.jenis-sampah')
            ->with('success', 'Jenis sampah berhasil ditambahkan');
    }
    
    /**
     * Memperbarui data jenis sampah
     */
    public function updateJenisSampah(Request $request, $id)
    {
        $request->validate([
            'kategori_jenis' => 'required|string|in:Organik,Anorganik,Residu',
            'nama_jenis' => 'required|string|max:255|unique:jenis,nama_jenis,'.$id.',id_jenis',
        ]);
        
        $jenisSampah = Jenis::findOrFail($id);
        $jenisSampah->kategori_jenis = $request->kategori_jenis;
        $jenisSampah->nama_jenis = $request->nama_jenis;
        $jenisSampah->save();
        
        return redirect()->route('admin.master.jenis-sampah')
            ->with('success', 'Jenis sampah berhasil diperbarui');
    }
    
    /**
     * Menghapus data jenis sampah
     */
    public function deleteJenisSampah($id)
    {
        $jenisSampah = Jenis::findOrFail($id);
        $jenisSampah->delete();
        
        return redirect()->route('admin.master.jenis-sampah')
            ->with('success', 'Jenis sampah berhasil dihapus');
    }
    
    /**
     * Menampilkan halaman master data tujuan sampah
     */
    public function masterTujuanSampah()
    {
        $tujuanSampah = TujuanSampah::all();
        return view('admin.master.tujuan-sampah', compact('tujuanSampah'));
    }
    
    /**
     * Menyimpan data tujuan sampah baru
     */
    public function storeTujuanSampah(Request $request)
    {
        $request->validate([
            'nama_tujuan' => 'required|string|max:255|unique:tujuan_sampahs,nama_tujuan',
            'kategori_tujuan' => 'required|string|in:sampah,lb3',
            'alamat' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        TujuanSampah::create([
            'nama_tujuan' => $request->nama_tujuan,
            'kategori_tujuan' => $request->kategori_tujuan,
            'alamat' => $request->alamat,
            'status' => $request->status,
        ]);
        
        return redirect()->route('admin.master.tujuan-sampah')
            ->with('success', 'Tujuan sampah berhasil ditambahkan');
    }
    
    /**
     * Memperbarui data tujuan sampah
     */
    public function updateTujuanSampah(Request $request, $id)
    {
        $request->validate([
            'nama_tujuan' => 'required|string|max:255|unique:tujuan_sampahs,nama_tujuan,'.$id.',id_tujuan',
            'kategori_tujuan' => 'required|string|in:sampah,lb3',
            'alamat' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        $tujuanSampah = TujuanSampah::findOrFail($id);
        $tujuanSampah->nama_tujuan = $request->nama_tujuan;
        $tujuanSampah->kategori_tujuan = $request->kategori_tujuan;
        $tujuanSampah->alamat = $request->alamat;
        $tujuanSampah->status = $request->status;
        $tujuanSampah->save();
        
        return redirect()->route('admin.master.tujuan-sampah')
            ->with('success', 'Tujuan sampah berhasil diperbarui');
    }
    
    /**
     * Menghapus data tujuan sampah
     */
    public function deleteTujuanSampah($id)
    {
        $tujuanSampah = TujuanSampah::findOrFail($id);
        $tujuanSampah->delete();
        
        return redirect()->route('admin.master.tujuan-sampah')
            ->with('success', 'Tujuan sampah berhasil dihapus');
    }

    /**
     * Menampilkan detail sampah terkelola
     */
    public function showSampahTerkelola($id)
    {
        $sampah = SampahTerkelola::with(['user', 'lokasiAsal', 'jenis'])->findOrFail($id);
        return view('admin.data.sampah-terkelola-show', compact('sampah'));
    }

    /**
     * Menampilkan form create sampah terkelola
     */
    public function createSampahTerkelola()
    {
        $users = User::all();
        $lokasiAsals = LokasiAsal::all();
        $jenis = Jenis::all();
        // Untuk sampah terkelola, hanya Organik dan Anorganik
        $kategoriJenises = ['Organik', 'Anorganik'];
        
        return view('admin.data.create-sampah-terkelola', compact('users', 'lokasiAsals', 'jenis', 'kategoriJenises'));
    }

    /**
     * Menyimpan data sampah terkelola baru
     */
    public function storeSampahTerkelola(Request $request)
    {
        $request->validate([
            'id_user' => 'required|exists:users,id',
            'id_lokasi' => 'required|exists:lokasi_asals,id_lokasi',
            'id_jenis' => 'required|exists:jenis,id_jenis',
            'jumlah_berat' => 'required|numeric|min:0',
            'tgl' => 'required|date',
            'foto_kelola' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $data = [
            'id_user' => $request->id_user,
            'id_lokasi' => $request->id_lokasi,
            'id_jenis' => $request->id_jenis,
            'jumlah_berat' => $request->jumlah_berat,
            'tgl' => $request->tgl,
        ];
        
        if($request->hasFile('foto_kelola')) {
            $file = $request->file('foto_kelola');
            $filePath = $file->store('sampah-terkelola', 'public');
            $data['foto_kelola'] = $filePath;
        }
        
        SampahTerkelola::create($data);
        
        return redirect()->route('admin.data.sampah-terkelola')
            ->with('success', 'Data sampah terkelola berhasil ditambahkan');
    }

    /**
     * Menampilkan form edit sampah terkelola
     */
    public function editSampahTerkelola($id)
    {
        $sampah = SampahTerkelola::with(['user', 'lokasiAsal', 'jenis'])->findOrFail($id);
        $lokasiAsals = LokasiAsal::all();
        $jenisAll = Jenis::all();
        // Untuk sampah terkelola, hanya Organik dan Anorganik
        $kategoriJenises = ['Organik', 'Anorganik'];
        
        return view('admin.data.edit-sampah-terkelola', compact('sampah', 'lokasiAsals', 'jenisAll', 'kategoriJenises'));
    }

    /**
     * Memperbarui data sampah terkelola
     */
    public function updateSampahTerkelola(Request $request, $id)
    {
        $sampah = SampahTerkelola::findOrFail($id);
        
        $request->validate([
            'id_lokasi' => 'required|exists:lokasi_asals,id_lokasi',
            'id_jenis' => 'required|exists:jenis,id_jenis',
            'jumlah_berat' => 'required|numeric|min:0',
            'tgl' => 'required|date',
            'foto_kelola' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'alasan_edit' => 'required|string|max:500',
        ]);
        
        $sampah->id_lokasi = $request->id_lokasi;
        $sampah->id_jenis = $request->id_jenis;
        $sampah->jumlah_berat = $request->jumlah_berat;
        $sampah->tgl = $request->tgl;
        $sampah->alasan_edit = $request->alasan_edit;
        
        if($request->hasFile('foto_kelola')) {
            if($sampah->foto_kelola && Storage::exists('public/' . $sampah->foto_kelola)) {
                Storage::delete('public/' . $sampah->foto_kelola);
            }
            
            $file = $request->file('foto_kelola');
            $filePath = $file->store('sampah-terkelola', 'public');
            $sampah->foto_kelola = $filePath;
        }
        
        $sampah->save();
        
        return redirect()->route('admin.data.sampah-terkelola')
            ->with('success', 'Data sampah terkelola berhasil diperbarui');
    }

    /**
     * Menampilkan detail sampah diserahkan
     */
    public function showSampahDiserahkan($id)
    {
        $sampah = SampahDiserahkan::with(['user', 'lokasiAsal', 'jenis', 'tujuanSampah'])->findOrFail($id);
        return view('admin.data.sampah-diserahkan-show', compact('sampah'));
    }

    /**
     * Menampilkan form create sampah diserahkan
     */
    public function createSampahDiserahkan()
    {
        $users = User::all();
        $lokasiAsals = LokasiAsal::all();
        $jenis = Jenis::all();
        $tujuanSampahs = TujuanSampah::all();
        // Untuk sampah diserahkan, hanya Residu
        $kategoriJenises = ['Residu'];
        
        return view('admin.data.create-sampah-diserahkan', compact('users', 'lokasiAsals', 'jenis', 'tujuanSampahs', 'kategoriJenises'));
    }

    /**
     * Menyimpan data sampah diserahkan baru
     */
    public function storeSampahDiserahkan(Request $request)
    {
        $request->validate([
            'id_user' => 'required|exists:users,id',
            'id_lokasi' => 'required|exists:lokasi_asals,id_lokasi',
            'id_jenis' => 'required|exists:jenis,id_jenis',
            'id_tujuan' => 'required|exists:tujuan_sampahs,id_tujuan',
            'jumlah_berat' => 'required|numeric|min:0',
            'tgl_diserahkan' => 'required|date',
            'foto_diserahkan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $data = [
            'id_user' => $request->id_user,
            'id_lokasi' => $request->id_lokasi,
            'id_jenis' => $request->id_jenis,
            'id_tujuan' => $request->id_tujuan,
            'jumlah_berat' => $request->jumlah_berat,
            'tgl_diserahkan' => $request->tgl_diserahkan,
        ];
        
        if($request->hasFile('foto_diserahkan')) {
            $file = $request->file('foto_diserahkan');
            $filePath = $file->store('sampah-diserahkan', 'public');
            $data['foto_diserahkan'] = $filePath;
        }
        
        SampahDiserahkan::create($data);
        
        return redirect()->route('admin.data.sampah-diserahkan')
            ->with('success', 'Data sampah diserahkan berhasil ditambahkan');
    }

    /**
     * Menampilkan form edit sampah diserahkan
     */
    public function editSampahDiserahkan($id)
    {
        $sampah = SampahDiserahkan::with(['user', 'lokasiAsal', 'jenis', 'tujuanSampah'])->findOrFail($id);
        $lokasiAsals = LokasiAsal::all();
        $jenisAll = Jenis::all();
        $tujuanSampahs = TujuanSampah::all();
        // Untuk sampah diserahkan, hanya Residu
        $kategoriJenises = ['Residu'];
        
        return view('admin.data.edit-sampah-diserahkan', compact('sampah', 'lokasiAsals', 'jenisAll', 'tujuanSampahs', 'kategoriJenises'));
    }

    /**
     * Memperbarui data sampah diserahkan
     */
    public function updateSampahDiserahkan(Request $request, $id)
    {
        $sampah = SampahDiserahkan::findOrFail($id);
        
        $request->validate([
            'id_lokasi' => 'required|exists:lokasi_asals,id_lokasi',
            'id_jenis' => 'required|exists:jenis,id_jenis',
            'id_tujuan' => 'required|exists:tujuan_sampahs,id_tujuan',
            'jumlah_berat' => 'required|numeric|min:0',
            'tgl_diserahkan' => 'required|date',
            'foto_diserahkan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'alasan_edit' => 'required|string|max:500',
        ]);
        
        $sampah->id_lokasi = $request->id_lokasi;
        $sampah->id_jenis = $request->id_jenis;
        $sampah->id_tujuan = $request->id_tujuan;
        $sampah->jumlah_berat = $request->jumlah_berat;
        $sampah->tgl_diserahkan = $request->tgl_diserahkan;
        $sampah->alasan_edit = $request->alasan_edit;
        
        if($request->hasFile('foto_diserahkan')) {
            if($sampah->foto_diserahkan && Storage::exists('public/' . $sampah->foto_diserahkan)) {
                Storage::delete('public/' . $sampah->foto_diserahkan);
            }
            
            $file = $request->file('foto_diserahkan');
            $filePath = $file->store('sampah-diserahkan', 'public');
            $sampah->foto_diserahkan = $filePath;
        }
        
        $sampah->save();
        
        return redirect()->route('admin.data.sampah-diserahkan')
            ->with('success', 'Data sampah diserahkan berhasil diperbarui');
    }
}
