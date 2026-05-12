<?php

namespace App\Http\Controllers;

use App\Models\LokasiAsal; // Pastikan model ini sudah dibuat
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LokasiAsalController extends Controller
{
    /**
     * Menampilkan daftar semua lokasi asal (Method: GET)
     */
    public function index()
    {
        $lokasiAsals = LokasiAsal::latest()->get(); // Mengambil SEMUA data
        return view('lokasi_asal.index', ['lokasiAsals' => $lokasiAsals]);
    }
    /**
     * Menampilkan form untuk membuat lokasi asal baru (Method: GET)
     */
    public function create()
    {
        // Hanya menampilkan halaman form
        return view('lokasi_asal.create');
    }

    /**
     * Menyimpan data lokasi asal baru ke database (Method: POST)
     */
    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'nama_lokasi' => 'required|string|max:255|unique:lokasi_asals',
        ]);

        // Membuat record baru di database
        LokasiAsal::create($validatedData);

        // Arahkan kembali ke halaman daftar dengan pesan sukses
        return redirect()->route('lokasi-asal.index')->with('success', 'Lokasi Asal berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit lokasi asal (Method: GET)
     * Laravel akan otomatis mencari data berdasarkan ID yang ada di URL.
     */
    public function edit(LokasiAsal $lokasiAsal)
    {
        // Mengirimkan data lokasi yang akan diedit ke halaman form
        return view('lokasi_asal.edit', ['lokasiAsal' => $lokasiAsal]);
    }

    /**
     * Mengupdate data lokasi asal di database (Method: PUT/PATCH)
     */
    public function update(Request $request, LokasiAsal $lokasiAsal)
    {
        // Validasi input, pastikan nama lokasi unik tapi abaikan data saat ini
        $validatedData = $request->validate([
            'nama_lokasi' => ['required', 'string', 'max:255', Rule::unique('lokasi_asals')->ignore($lokasiAsal->id_lokasi, 'id_lokasi')],
        ]);

        // Mengupdate record di database
        $lokasiAsal->update($validatedData);

        // Arahkan kembali ke halaman daftar dengan pesan sukses
        return redirect()->route('lokasi-asal.index')->with('success', 'Lokasi Asal berhasil diperbarui.');
    }

    /**
     * Menghapus data lokasi asal dari database (Method: DELETE)
     */
    public function destroy(LokasiAsal $lokasiAsal)
    {
        // Menghapus record
        $lokasiAsal->delete();

        // Arahkan kembali ke halaman daftar dengan pesan sukses
        return redirect()->route('lokasi-asal.index')->with('success', 'Lokasi Asal berhasil dihapus.');
    }
}