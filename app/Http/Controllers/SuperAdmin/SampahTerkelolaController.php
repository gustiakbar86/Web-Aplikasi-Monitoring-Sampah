<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SampahTerkelola;
use App\Models\LokasiAsal;
use App\Models\Jenis;
use App\Models\User;
use App\Traits\WithSweetAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SampahTerkelolaController extends Controller
{
    use WithSweetAlert;

    public function index()
    {
        $sampahTerkelolas = SampahTerkelola::with(['user', 'lokasiAsal', 'jenis'])
            ->orderBy('tgl', 'desc')
            ->orderBy('id', 'desc')
            ->get();
        return view('superAdmin.master.sampah_terkelola.index', compact('sampahTerkelolas'));
    }

    public function create()
    {
        $users = User::all();
        $lokasiAsals = LokasiAsal::all();
        $jenises = Jenis::all();
        // Untuk sampah terkelola, hanya Organik dan Anorganik
        $kategoriJenises = ['Organik', 'Anorganik'];
        return view('superAdmin.master.sampah_terkelola.tambah', compact('users', 'lokasiAsals', 'jenises', 'kategoriJenises'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tgl' => 'required|date',
            'id_user' => 'required|exists:users,id',
            'id_lokasi' => 'required|exists:lokasi_asals,id_lokasi',
            'id_jenis' => 'required|exists:jenis,id_jenis',
            'jumlah_berat' => 'required|numeric|min:0'
        ], [
            'tgl.required' => 'Tanggal harus diisi',
            'id_user.required' => 'Petugas harus dipilih',
            'id_lokasi.required' => 'Lokasi asal harus dipilih',
            'id_jenis.required' => 'Jenis sampah harus dipilih',
            'jumlah_berat.required' => 'Jumlah berat harus diisi',
            'jumlah_berat.numeric' => 'Jumlah berat harus berupa angka',
            'jumlah_berat.min' => 'Jumlah berat minimal 0'
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        try {
            SampahTerkelola::create([
                'tgl' => $request->tgl,
                'id_user' => $request->id_user,
                'id_lokasi' => $request->id_lokasi,
                'id_jenis' => $request->id_jenis,
                'jumlah_berat' => $request->jumlah_berat
            ]);

            return $this->createdSuccess('Sampah Terkelola', 'superadmin.master.sampah-terkelola');
        } catch (\Exception $e) {
            return $this->createdError('Sampah Terkelola', $e->getMessage(), 'superadmin.master.sampah-terkelola');
        }
    }

    public function show($id)
    {
        $sampahTerkelola = SampahTerkelola::with(['user', 'lokasiAsal', 'jenis'])->findOrFail($id);
        return view('superAdmin.master.sampah_terkelola.show', compact('sampahTerkelola'));
    }

    public function edit($id)
    {
        $sampahTerkelola = SampahTerkelola::findOrFail($id);
        $users = User::all();
        $lokasiAsals = LokasiAsal::all();
        $jenises = Jenis::all();
        // Untuk sampah terkelola, hanya Organik dan Anorganik
        $kategoriJenises = ['Organik', 'Anorganik'];
        return view('superAdmin.master.sampah_terkelola.edit', compact('sampahTerkelola', 'users', 'lokasiAsals', 'jenises', 'kategoriJenises'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tgl' => 'required|date',
            'id_user' => 'required|exists:users,id',
            'id_lokasi' => 'required|exists:lokasi_asals,id_lokasi',
            'id_jenis' => 'required|exists:jenis,id_jenis',
            'jumlah_berat' => 'required|numeric|min:0',
            'alasan_edit' => 'required|string|max:500'
        ], [
            'tgl.required' => 'Tanggal harus diisi',
            'id_user.required' => 'Petugas harus dipilih',
            'id_lokasi.required' => 'Lokasi asal harus dipilih',
            'id_jenis.required' => 'Jenis sampah harus dipilih',
            'jumlah_berat.required' => 'Jumlah berat harus diisi',
            'jumlah_berat.numeric' => 'Jumlah berat harus berupa angka',
            'jumlah_berat.min' => 'Jumlah berat minimal 0',
            'alasan_edit.required' => 'Alasan edit harus diisi',
            'alasan_edit.max' => 'Alasan edit maksimal 500 karakter'
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        try {
            $sampahTerkelola = SampahTerkelola::findOrFail($id);
            $sampahTerkelola->update([
                'tgl' => $request->tgl,
                'id_user' => $request->id_user,
                'id_lokasi' => $request->id_lokasi,
                'id_jenis' => $request->id_jenis,
                'jumlah_berat' => $request->jumlah_berat,
                'alasan_edit' => $request->alasan_edit
            ]);

            return $this->updatedSuccess('Sampah Terkelola', 'superadmin.master.sampah-terkelola');
        } catch (\Exception $e) {
            return $this->updatedError('Sampah Terkelola', $e->getMessage(), 'superadmin.master.sampah-terkelola');
        }
    }

    public function destroy($id)
    {
        try {
            $sampahTerkelola = SampahTerkelola::findOrFail($id);
            $sampahTerkelola->delete();

            return $this->deletedSuccess('Sampah Terkelola');
        } catch (\Exception $e) {
            return $this->deletedError('Sampah Terkelola', $e->getMessage());
        }
    }
}
