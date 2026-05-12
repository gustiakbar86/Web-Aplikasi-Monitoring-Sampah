<?php

namespace App\Http\Controllers;

use App\Models\Jenis; // Pastikan Anda sudah membuat model dengan nama Jenis.php
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JenisController extends Controller
{
    /**
     * Menampilkan daftar semua jenis sampah.
     * Method: GET
     */
    public function index()
    {
        // Mengambil semua data jenis sampah dari yang terbaru
        $jenises = Jenis::latest()->get();

        // Mengembalikan view dan mengirimkan data ke dalamnya
        return view('jenis.index', ['jenises' => $jenises]);
    }

    /**
     * Menampilkan form untuk membuat jenis sampah baru.
     * Method: GET
     */
    public function create()
    {
        // Menampilkan halaman form create.blade.php
        return view('jenis.create');
    }

    /**
     * Menyimpan data jenis sampah baru ke database.
     * Method: POST
     */
    public function store(Request $request)
    {
        // Validasi data yang masuk
        $validatedData = $request->validate([
            'nama_jenis' => 'required|string|max:255|unique:jenis,nama_jenis',
        ]);

        // Menyimpan data baru ke database
        Jenis::create($validatedData);

        // Mengarahkan kembali ke halaman daftar dengan pesan sukses
        return redirect()->route('jenis.index')->with('success', 'Jenis Sampah berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit jenis sampah.
     * Method: GET
     */
    public function edit(Jenis $jeni)
    {
        // Mengirimkan data yang akan diedit ke halaman form edit.blade.php
        return view('jenis.edit', ['jeni' => $jeni]);
    }

    /**
     * Mengupdate data jenis sampah di database.
     * Method: PUT/PATCH
     */
    public function update(Request $request, Jenis $jeni)
    {
        // Validasi data, pastikan nama_jenis unik tapi abaikan data saat ini
        $validatedData = $request->validate([
            'nama_jenis' => ['required', 'string', 'max:255', Rule::unique('jenis')->ignore($jeni->id_jenis, 'id_jenis')],
        ]);

        // Mengupdate data di database
        $jeni->update($validatedData);

        // Mengarahkan kembali ke halaman daftar dengan pesan sukses
        return redirect()->route('jenis.index')->with('success', 'Jenis Sampah berhasil diperbarui.');
    }

    /**
     * Menghapus data jenis sampah dari database.
     * Method: DELETE
     */
    public function destroy(Jenis $jeni)
    {
        // Menghapus data
        $jeni->delete();

        // Mengarahkan kembali ke halaman daftar dengan pesan sukses
        return redirect()->route('jenis.index')->with('success', 'Jenis Sampah berhasil dihapus.');
    }
}