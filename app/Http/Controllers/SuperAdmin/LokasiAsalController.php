<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\LokasiAsal;
use App\Traits\WithSweetAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LokasiAsalController extends Controller
{
    use WithSweetAlert;

    public function index()
    {
        $lokasiAsals = LokasiAsal::orderBy('nama_lokasi', 'asc')->get();
        return view('superAdmin.master.lokasi_asal.index', compact('lokasiAsals'));
    }

    public function create()
    {
        return view('superAdmin.master.lokasi_asal.tambah');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lokasi' => 'required|string|max:255|unique:lokasi_asals,nama_lokasi'
        ], [
            'nama_lokasi.required' => 'Nama lokasi harus diisi',
            'nama_lokasi.unique' => 'Nama lokasi sudah ada'
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        try {
            LokasiAsal::create([
                'nama_lokasi' => $request->nama_lokasi
            ]);

            return $this->createdSuccess('Lokasi Asal', 'superadmin.master.lokasi-asal');
        } catch (\Exception $e) {
            return $this->createdError('Lokasi Asal', $e->getMessage(), 'superadmin.master.lokasi-asal');
        }
    }

    public function edit($id_lokasi)
    {
        $lokasiAsal = LokasiAsal::findOrFail($id_lokasi);
        return view('superAdmin.master.lokasi_asal.edit', compact('lokasiAsal'));
    }

    public function update(Request $request, $id_lokasi)
    {
        $validator = Validator::make($request->all(), [
            'nama_lokasi' => 'required|string|max:255|unique:lokasi_asals,nama_lokasi,' . $id_lokasi . ',id_lokasi'
        ], [
            'nama_lokasi.required' => 'Nama lokasi harus diisi',
            'nama_lokasi.unique' => 'Nama lokasi sudah ada'
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        try {
            $lokasiAsal = LokasiAsal::findOrFail($id_lokasi);
            $lokasiAsal->update([
                'nama_lokasi' => $request->nama_lokasi
            ]);

            return $this->updatedSuccess('Lokasi Asal', 'superadmin.master.lokasi-asal');
        } catch (\Exception $e) {
            return $this->updatedError('Lokasi Asal', $e->getMessage(), 'superadmin.master.lokasi-asal');
        }
    }

    public function destroy($id_lokasi)
    {
        try {
            $lokasiAsal = LokasiAsal::findOrFail($id_lokasi);
            $lokasiAsal->delete();

            return $this->deletedSuccess('Lokasi Asal');
        } catch (\Exception $e) {
            return $this->deletedError('Lokasi Asal', $e->getMessage());
        }
    }
}
