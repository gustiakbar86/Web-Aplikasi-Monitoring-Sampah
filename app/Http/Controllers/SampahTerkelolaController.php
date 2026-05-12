<?php

namespace App\Http\Controllers;

use App\Models\SampahTerkelola;
use App\Models\User;
use App\Models\LokasiAsal; // Sesuaikan dengan nama model lokasi Anda
use App\Models\Jenis;      // Sesuaikan dengan nama model jenis Anda
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Wajib di-import

class SampahTerkelolaController extends Controller
{
    /**
     * Menampilkan daftar semua data sampah.
     */
    public function index()
    {
        // Mengambil data beserta relasinya untuk efisiensi
        $sampahTerkelolas = SampahTerkelola::with(['user', 'lokasiAsal', 'jenis'])
            ->orderBy('tgl', 'desc')
            ->orderBy('id', 'desc')
            ->get();
        
        return view('sampah_terkelola.index', ['sampahTerkelolas' => $sampahTerkelolas]);
    }

    /**
     * Menampilkan form untuk membuat data baru.
     */
    public function create()
    {
        // Mengambil data untuk mengisi pilihan dropdown di form
        $users = User::all();
        $lokasiAsals = LokasiAsal::all();
        $jenis = Jenis::all();
        $kategoriJenises = ['organik', 'anorganik', 'residu'];

        return view('petugas.input_sampah_terkelola', compact('users', 'lokasiAsals', 'jenis', 'kategoriJenises'));
    }

    /**
     * Menyimpan data baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi semua input dari form
        $validatedData = $request->validate([
            'id_user' => 'required|exists:users,id',
            'id_lokasi' => 'required|exists:lokasi_asals,id_lokasi',
            'id_jenis' => 'required|exists:jenis,id_jenis',
            'jumlah_berat' => 'required|integer|min:0',
            'tgl' => 'required|date',
            'foto_kelola' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Foto tidak wajib, maks 2MB
        ]);

        // 2. Jika ada file foto yang di-upload, simpan file dan path-nya
        if ($request->hasFile('foto_kelola')) {
            // Simpan file ke 'storage/app/public/foto-sampah'
            $path = $request->file('foto_kelola')->store('public/foto-sampah');
            $validatedData['foto_kelola'] = $path; // Simpan path ke array data
        }

        // 3. Simpan seluruh data ke database
        SampahTerkelola::create($validatedData);

        return redirect()->route('sampah-terkelola.index')->with('success', 'Data Sampah berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit data.
     */
    public function edit(SampahTerkelola $sampahTerkelola)
    {
        $users = User::all();
        $lokasiAsals = LokasiAsal::all();
        $jenis = Jenis::all();
        $kategoriJenises = ['organik', 'anorganik', 'residu'];

        return view('petugas.edit_sampah_terkelola', compact('sampahTerkelola', 'users', 'lokasiAsals', 'jenis', 'kategoriJenises'));
    }

    /**
     * Mengupdate data di database.
     */
    public function update(Request $request, SampahTerkelola $sampahTerkelola)
    {
        $validatedData = $request->validate([
            'id_user' => 'required|exists:users,id',
            'id_lokasi' => 'required|exists:lokasi_asals,id_lokasi',
            'id_jenis' => 'required|exists:jenis,id_jenis',
            'jumlah_berat' => 'required|integer|min:0',
            'tgl' => 'required|date',
            'foto_kelola' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto_kelola')) {
            // Hapus foto lama jika ada untuk mencegah penumpukan file
            if ($sampahTerkelola->foto_kelola) {
                Storage::delete($sampahTerkelola->foto_kelola);
            }
            
            // Simpan foto baru dan dapatkan path-nya
            $path = $request->file('foto_kelola')->store('public/foto-sampah');
            $validatedData['foto_kelola'] = $path;
        }

        $sampahTerkelola->update($validatedData);

        return redirect()->route('sampah-terkelola.index')->with('success', 'Data Sampah berhasil diperbarui.');
    }

    /**
     * Menghapus data dari database.
     */
    public function destroy(SampahTerkelola $sampahTerkelola)
    {
        // Hapus file foto dari storage sebelum menghapus record database
        if ($sampahTerkelola->foto_kelola) {
            Storage::delete($sampahTerkelola->foto_kelola);
        }

        // Hapus record dari database
        $sampahTerkelola->delete();
        
        return redirect()->route('sampah-terkelola.index')->with('success', 'Data Sampah berhasil dihapus.');
    }
}