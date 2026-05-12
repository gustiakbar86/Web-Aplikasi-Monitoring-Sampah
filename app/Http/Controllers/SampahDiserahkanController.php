<?php

namespace App\Http\Controllers;

use App\Models\SampahDiserahkan;
use App\Models\User;
use App\Models\LokasiAsal; // Model lokasi asal
use App\Models\Jenis; // Model jenis sampah
use App\Models\TujuanSampah; // Model tujuan sampah
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SampahDiserahkanController extends Controller
{
    /**
     * Menampilkan daftar semua data sampah yang diserahkan.
     */
    public function index()
    {
        $sampahDiserahkans = SampahDiserahkan::with(['user', 'lokasiAsal', 'jenis', 'tujuanSampah'])
            ->orderBy('tgl_diserahkan', 'desc')
            ->orderBy('id', 'desc')
            ->get();
        return view('sampah_diserahkan.index', ['sampahDiserahkans' => $sampahDiserahkans]);
    }

    /**
     * Menampilkan form untuk membuat data baru.
     */
    public function create()
    {
        // Data untuk mengisi dropdown di form
        $users = User::all();
        $lokasiAsals = LokasiAsal::all();
        $jenis = Jenis::all();
        $tujuanSampahs = TujuanSampah::all();
        $kategoriJenises = ['organik', 'anorganik', 'residu'];

        return view('petugas.input_sampah_diserahkan', compact('users', 'lokasiAsals', 'jenis', 'tujuanSampahs', 'kategoriJenises'));
    }

    /**
     * Menyimpan data baru ke database.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_user' => 'required|exists:users,id',
            'id_lokasi' => 'required|exists:lokasi_asals,id_lokasi',
            'id_jenis' => 'required|exists:jenis,id_jenis',
            'id_tujuan' => 'required|exists:tujuan_sampahs,id_tujuan',
            'jumlah_berat' => 'required|integer|min:0',
            'tgl_diserahkan' => 'required|date',
            'foto_diserahkan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto_diserahkan')) {
            $path = $request->file('foto_diserahkan')->store('public/foto-diserahkan');
            $validatedData['foto_diserahkan'] = $path;
        }

        SampahDiserahkan::create($validatedData);

        return redirect()->route('sampah-diserahkan.index')->with('success', 'Data Sampah Diserahkan berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit data.
     */
    public function edit(SampahDiserahkan $sampahDiserahkan)
    {
        $users = User::all();
        $lokasiAsals = LokasiAsal::all();
        $jenis = Jenis::all();
        $tujuanSampahs = TujuanSampah::all();
        $kategoriJenises = ['organik', 'anorganik', 'residu'];

        return view('petugas.edit_sampah_diserahkan', compact('sampahDiserahkan', 'users', 'lokasiAsals', 'jenis', 'tujuanSampahs', 'kategoriJenises'));
    }

    /**
     * Mengupdate data di database.
     */
    public function update(Request $request, SampahDiserahkan $sampahDiserahkan)
    {
        $validatedData = $request->validate([
            'id_user' => 'required|exists:users,id',
            'id_lokasi' => 'required|exists:lokasi_asals,id_lokasi',
            'id_jenis' => 'required|exists:jenis,id_jenis',
            'id_tujuan' => 'required|exists:tujuan_sampahs,id_tujuan',
            'jumlah_berat' => 'required|integer|min:0',
            'tgl_diserahkan' => 'required|date',
            'foto_diserahkan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto_diserahkan')) {
            if ($sampahDiserahkan->foto_diserahkan) {
                Storage::delete($sampahDiserahkan->foto_diserahkan);
            }
            $path = $request->file('foto_diserahkan')->store('public/foto-diserahkan');
            $validatedData['foto_diserahkan'] = $path;
        }

        $sampahDiserahkan->update($validatedData);

        return redirect()->route('sampah-diserahkan.index')->with('success', 'Data Sampah Diserahkan berhasil diperbarui.');
    }

    /**
     * Menghapus data dari database.
     */
    public function destroy(SampahDiserahkan $sampahDiserahkan)
    {
        if ($sampahDiserahkan->foto_diserahkan) {
            Storage::delete($sampahDiserahkan->foto_diserahkan);
        }
        $sampahDiserahkan->delete();

        return redirect()->route('sampah-diserahkan.index')->with('success', 'Data Sampah Diserahkan berhasil dihapus.');
    }
}