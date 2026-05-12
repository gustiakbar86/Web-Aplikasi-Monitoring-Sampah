<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Jenis;
use App\Traits\WithSweetAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JenisController extends Controller
{
    use WithSweetAlert;

    public function index()
    {
        $jenises = Jenis::orderBy('nama_jenis', 'asc')->get();
        return view('superAdmin.master.jenis.index', compact('jenises'));
    }

    public function create()
    {
        return view('superAdmin.master.jenis.tambah');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kategori_jenis' => 'required|in:organik,anorganik,residu',
            'nama_jenis' => 'required|string|max:255|unique:jenis,nama_jenis'
        ], [
            'kategori_jenis.required' => 'Kategori jenis harus dipilih',
            'kategori_jenis.in' => 'Kategori jenis tidak valid',
            'nama_jenis.required' => 'Nama jenis sampah harus diisi',
            'nama_jenis.unique' => 'Nama jenis sampah sudah ada'
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        try {
            Jenis::create([
                'kategori_jenis' => $request->kategori_jenis,
                'nama_jenis' => $request->nama_jenis
            ]);

            return $this->createdSuccess('Jenis Sampah', 'superadmin.master.jenis-sampah');
        } catch (\Exception $e) {
            return $this->createdError('Jenis Sampah', $e->getMessage(), 'superadmin.master.jenis-sampah');
        }
    }

    public function edit($id_jenis)
    {
        $jenis = Jenis::findOrFail($id_jenis);
        $kategoriJenises = ['organik', 'anorganik', 'residu'];

        return view('superAdmin.master.jenis.edit', compact('jenis', 'kategoriJenises'));
    }

    public function update(Request $request, $id_jenis)
    {
        $validator = Validator::make($request->all(), [
            'kategori_jenis' => 'required|in:organik,anorganik,residu',
            'nama_jenis' => 'required|string|max:255|unique:jenis,nama_jenis,' . $id_jenis . ',id_jenis'
        ], [
            'kategori_jenis.required' => 'Kategori jenis harus dipilih',
            'kategori_jenis.in' => 'Kategori jenis tidak valid',
            'nama_jenis.required' => 'Nama jenis sampah harus diisi',
            'nama_jenis.unique' => 'Nama jenis sampah sudah ada'
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        try {
            $jenis = Jenis::findOrFail($id_jenis);
            $jenis->update([
                'kategori_jenis' => $request->kategori_jenis,
                'nama_jenis' => $request->nama_jenis
            ]);

            return $this->updatedSuccess('Jenis Sampah', 'superadmin.master.jenis-sampah');
        } catch (\Exception $e) {
            return $this->updatedError('Jenis Sampah', $e->getMessage(), 'superadmin.master.jenis-sampah');
        }
    }

    public function destroy($id_jenis)
    {
        try {
            $jenis = Jenis::findOrFail($id_jenis);
            $jenis->delete();

            return $this->deletedSuccess('Jenis Sampah');
        } catch (\Exception $e) {
            return $this->deletedError('Jenis Sampah', $e->getMessage());
        }
    }
}
