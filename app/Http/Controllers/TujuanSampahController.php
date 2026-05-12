<?php

namespace App\Http\Controllers;

use App\Models\TujuanSampah; // Pastikan model ini sudah dibuat
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TujuanSampahController extends Controller
{
    /**
     * Menampilkan daftar semua tujuan sampah.
     * Method: GET
     */
    public function index()
    {
        // Mengambil semua data dari yang terbaru tanpa batasan
        $tujuanSampahs = TujuanSampah::latest()->get();

        // Mengembalikan view 'index.blade.php' dan mengirimkan data
        return view('tujuan_sampah.index', ['tujuanSampahs' => $tujuanSampahs]);
    }

    /**
     * Menampilkan form untuk membuat tujuan sampah baru.
     * Method: GET
     */
    public function create()
    {
        // Menampilkan halaman form create.blade.php
        return view('tujuan_sampah.create');
    }

    /**
     * Menyimpan data tujuan sampah baru ke database.
     * Method: POST
     */
    public function store(Request $request)
    {
        // Validasi data yang masuk dari form
        $validatedData = $request->validate([
            'kategori_tujuan' => 'required|in:bank_sampah,tpa',
            'nama_tujuan' => 'required|string|max:255|unique:tujuan_sampahs,nama_tujuan',
            'alamat' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        // Menyimpan data baru ke database
        TujuanSampah::create($validatedData);

        // Mengarahkan kembali ke halaman daftar dengan pesan sukses
        return redirect()->route('tujuan-sampah.index')->with('success', 'Tujuan Sampah berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit tujuan sampah.
     * Method: GET
     */
    public function edit(TujuanSampah $tujuanSampah)
    {
        // Mengirimkan data yang akan diedit ke halaman form edit.blade.php
        return view('tujuan_sampah.edit', ['tujuanSampah' => $tujuanSampah]);
    }

    /**
     * Mengupdate data tujuan sampah di database.
     * Method: PUT/PATCH
     */
    public function update(Request $request, TujuanSampah $tujuanSampah)
    {
        // Validasi data, pastikan nama_tujuan unik tapi abaikan data saat ini
        $validatedData = $request->validate([
            'kategori_tujuan' => 'required|in:bank_sampah,tpa',
            'nama_tujuan' => ['required', 'string', 'max:255', Rule::unique('tujuan_sampahs')->ignore($tujuanSampah->id_tujuan, 'id_tujuan')],
            'alamat' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        // Mengupdate data di database
        $tujuanSampah->update($validatedData);

        // Mengarahkan kembali ke halaman daftar dengan pesan sukses
        return redirect()->route('tujuan-sampah.index')->with('success', 'Tujuan Sampah berhasil diperbarui.');
    }

    /**
     * Menghapus data tujuan sampah dari database.
     * Method: DELETE
     */
    public function destroy(TujuanSampah $tujuanSampah)
    {
        // Menghapus data
        $tujuanSampah->delete();

        // Mengarahkan kembali ke halaman daftar dengan pesan sukses
        return redirect()->route('tujuan-sampah.index')->with('success', 'Tujuan Sampah berhasil dihapus.');
    }
}