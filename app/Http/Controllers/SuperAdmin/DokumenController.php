<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Dokumen;
use App\Traits\WithSweetAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DokumenController extends Controller
{
    use WithSweetAlert;

    public function index()
    {
        $dokumens = Dokumen::orderBy('created_at', 'desc')->paginate(10);
        return view('superAdmin.master.dokumen.index', compact('dokumens'));
    }

    public function create()
    {
        return view('superAdmin.master.dokumen.tambah');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_dokumen' => 'required|string|max:255',
            'instansi_kerjasama' => 'required|string|max:255',
            'file_dokumen' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'keterangan_dokumen' => 'nullable|string'
        ], [
            'nama_dokumen.required' => 'Nama dokumen harus diisi',
            'instansi_kerjasama.required' => 'Instansi kerjasama harus diisi',
            'file_dokumen.required' => 'File dokumen harus diupload',
            'file_dokumen.mimes' => 'File harus berformat PDF, DOC, DOCX, XLS, atau XLSX',
            'file_dokumen.max' => 'Ukuran file maksimal 10MB'
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        try {
            $file = $request->file('file_dokumen');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/dokumen', $filename);

            Dokumen::create([
                'id_user' => auth()->id(),
                'nama_dokumen' => $request->nama_dokumen,
                'instansi_kerjasama' => $request->instansi_kerjasama,
                'file_dokumen' => $filename,
                'berlaku' => true,
                'keterangan_dokumen' => $request->keterangan_dokumen
            ]);

            return $this->createdSuccess('Dokumen', 'superadmin.master.dokumen');
        } catch (\Exception $e) {
            return $this->createdError('Dokumen', $e->getMessage(), 'superadmin.master.dokumen');
        }
    }

    public function edit($id)
    {
        $dokumen = Dokumen::findOrFail($id);
        return view('superAdmin.master.dokumen.edit', compact('dokumen'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_dokumen' => 'required|string|max:255',
            'instansi_kerjasama' => 'required|string|max:255',
            'file_dokumen' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'keterangan_dokumen' => 'nullable|string'
        ], [
            'nama_dokumen.required' => 'Nama dokumen harus diisi',
            'instansi_kerjasama.required' => 'Instansi kerjasama harus diisi',
            'file_dokumen.mimes' => 'File harus berformat PDF, DOC, DOCX, XLS, atau XLSX',
            'file_dokumen.max' => 'Ukuran file maksimal 10MB'
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        try {
            $dokumen = Dokumen::findOrFail($id);
            
            $data = [
                'nama_dokumen' => $request->nama_dokumen,
                'instansi_kerjasama' => $request->instansi_kerjasama,
                'keterangan_dokumen' => $request->keterangan_dokumen
            ];

            if ($request->hasFile('file_dokumen')) {
                // Hapus file lama
                if ($dokumen->file_dokumen) {
                    Storage::delete('public/dokumen/' . $dokumen->file_dokumen);
                }

                // Upload file baru
                $file = $request->file('file_dokumen');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/dokumen', $filename);
                $data['file_dokumen'] = $filename;
            }

            $dokumen->update($data);

            return $this->updatedSuccess('Dokumen', 'superadmin.master.dokumen');
        } catch (\Exception $e) {
            return $this->updatedError('Dokumen', $e->getMessage(), 'superadmin.master.dokumen');
        }
    }

    public function destroy($id)
    {
        try {
            $dokumen = Dokumen::findOrFail($id);
            
            // Hapus file
            if ($dokumen->file_dokumen) {
                Storage::delete('public/dokumen/' . $dokumen->file_dokumen);
            }

            $dokumen->delete();

            return $this->deletedSuccess('Dokumen');
        } catch (\Exception $e) {
            return $this->deletedError('Dokumen', $e->getMessage());
        }
    }
}
