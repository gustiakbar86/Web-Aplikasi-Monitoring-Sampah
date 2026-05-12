<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\TujuanSampah;
use App\Traits\WithSweetAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TujuanSampahController extends Controller
{
    use WithSweetAlert;

    public function index()
    {
        $tujuanSampahs = TujuanSampah::orderBy('nama_tujuan', 'asc')->get();
        return view('superAdmin.master.tujuan.index', compact('tujuanSampahs'));
    }

    public function create()
    {
        return view('superAdmin.master.tujuan.tambah');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kategori_tujuan' => 'required|in:bank_sampah,tpa',
            'nama_tujuan' => 'required|string|max:255|unique:tujuan_sampahs,nama_tujuan',
            'alamat' => 'nullable|string',
            'status' => 'required|in:0,1'
        ], [
            'kategori_tujuan.required' => 'Kategori tujuan harus diisi',
            'kategori_tujuan.in' => 'Kategori tujuan tidak valid',
            'nama_tujuan.required' => 'Nama tujuan sampah harus diisi',
            'nama_tujuan.unique' => 'Nama tujuan sampah sudah ada',
            'status.required' => 'Status harus diisi'
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        try {
            TujuanSampah::create([
                'kategori_tujuan' => $request->kategori_tujuan,
                'nama_tujuan' => $request->nama_tujuan,
                'alamat' => $request->alamat,
                'status' => $request->status
            ]);

            return $this->createdSuccess('Tujuan Sampah', 'superadmin.master.tujuan-sampah');
        } catch (\Exception $e) {
            return $this->createdError('Tujuan Sampah', $e->getMessage(), 'superadmin.master.tujuan-sampah');
        }
    }

    public function edit($id_tujuan)
    {
        $tujuanSampah = TujuanSampah::findOrFail($id_tujuan);
        return view('superAdmin.master.tujuan.edit', compact('tujuanSampah'));
    }

    public function update(Request $request, $id_tujuan)
    {
        $validator = Validator::make($request->all(), [
            'kategori_tujuan' => 'required|in:bank_sampah,tpa',
            'nama_tujuan' => 'required|string|max:255|unique:tujuan_sampahs,nama_tujuan,' . $id_tujuan . ',id_tujuan',
            'alamat' => 'nullable|string',
            'status' => 'required|in:0,1'
        ], [
            'kategori_tujuan.required' => 'Kategori tujuan harus diisi',
            'kategori_tujuan.in' => 'Kategori tujuan tidak valid',
            'nama_tujuan.required' => 'Nama tujuan sampah harus diisi',
            'nama_tujuan.unique' => 'Nama tujuan sampah sudah ada',
            'status.required' => 'Status harus diisi'
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        try {
            $tujuanSampah = TujuanSampah::findOrFail($id_tujuan);
            $tujuanSampah->update([
                'kategori_tujuan' => $request->kategori_tujuan,
                'nama_tujuan' => $request->nama_tujuan,
                'alamat' => $request->alamat,
                'status' => $request->status
            ]);

            return $this->updatedSuccess('Tujuan Sampah', 'superadmin.master.tujuan-sampah');
        } catch (\Exception $e) {
            return $this->updatedError('Tujuan Sampah', $e->getMessage(), 'superadmin.master.tujuan-sampah');
        }
    }

    public function destroy($id_tujuan)
    {
        try {
            $tujuanSampah = TujuanSampah::findOrFail($id_tujuan);
            $tujuanSampah->delete();

            return $this->deletedSuccess('Tujuan Sampah');
        } catch (\Exception $e) {
            return $this->deletedError('Tujuan Sampah', $e->getMessage());
        }
    }
}
