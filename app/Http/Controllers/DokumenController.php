<?php

namespace App\Http\Controllers;

use App\Models\Dokumen; // Pastikan model ini sudah dibuat
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Wajib di-import untuk manajemen file
use App\Helpers\FileUploadHelper;

class DokumenController extends Controller
{
    /**
     * Menampilkan daftar semua dokumen.
     */
    public function index()
    {
        // Mengambil data dengan relasi user untuk menampilkan nama pengunggah
        $dokumens = Dokumen::with('user')->latest()->get();
        return view('dokumen.index', ['dokumens' => $dokumens]);
    }

    /**
     * Menampilkan form untuk membuat dokumen baru.
     */
    public function create()
    {
        return view('dokumen.create');
    }

    /**
     * Menyimpan dokumen baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi semua input dari form
        $validatedData = $request->validate([
            'nama_dokumen' => 'required|string|max:255',
            'instansi_kerjasama' => 'required|string|max:255',
            'berakhir' => 'required|date',
            'keterangan_dokumen' => 'nullable|string',
            'file_dokumen' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:5120', // Wajib, maks 5MB
        ]);

        // 2. Simpan file dan dapatkan path-nya
        $validatedData['file_dokumen'] = FileUploadHelper::uploadDokumen($request->file('file_dokumen'));
        
        // 3. Tambahkan ID user yang sedang login
        $validatedData['id_user'] = auth()->id();
        $validatedData['berlaku'] = true;

        // 4. Simpan ke database
        Dokumen::create($validatedData);

        return redirect()->route('dokumen.index')->with('success', 'Dokumen berhasil diunggah.');
    }

    /**
     * Menampilkan form untuk mengedit dokumen.
     */
    public function edit(Dokumen $dokumen)
    {
        return view('dokumen.edit', ['dokumen' => $dokumen]);
    }

    /**
     * Mengupdate dokumen di database.
     */
    public function update(Request $request, Dokumen $dokumen)
    {
        $validatedData = $request->validate([
            'nama_dokumen' => 'required|string|max:255',
            'instansi_kerjasama' => 'required|string|max:255',
            'berakhir' => 'required|date',
            'keterangan_dokumen' => 'nullable|string',
            'file_dokumen' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:5120', // Tidak wajib saat update
        ]);

        if ($request->hasFile('file_dokumen')) {
            // Hapus file lama jika ada
            FileUploadHelper::deleteFile($dokumen->file_dokumen);
            // Simpan file baru
            $validatedData['file_dokumen'] = FileUploadHelper::uploadDokumen($request->file('file_dokumen'));
        }

        $validatedData['berlaku'] = true;
        $dokumen->update($validatedData);

        return redirect()->route('dokumen.index')->with('success', 'Dokumen berhasil diperbarui.');
    }

    /**
     * Menghapus dokumen dari database.
     */
    public function destroy(Dokumen $dokumen)
    {
        // Hapus file dari public terlebih dahulu
        FileUploadHelper::deleteFile($dokumen->file_dokumen);

        // Hapus record dari database
        $dokumen->delete();

        return redirect()->route('dokumen.index')->with('success', 'Dokumen berhasil dihapus.');
    }
}