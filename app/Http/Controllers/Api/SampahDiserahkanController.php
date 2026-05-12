<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SampahDiserahkan;
use App\Models\Jenis;
use App\Models\LokasiAsal;
use App\Models\TujuanSampah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SampahDiserahkanController extends Controller
{
    /**
     * Menampilkan daftar sampah diserahkan untuk petugas yang login
     * GET /api/petugas/sampah-diserahkan
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::guard('api')->user();
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized',
                    'code' => 401
                ], 401);
            }

            $sampahDiserahkans = SampahDiserahkan::with(['user', 'lokasiAsal', 'jenis', 'tujuanSampah'])
                ->where('id_user', $user->id)
                ->orderBy('tgl_diserahkan', 'desc')
                ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Data sampah diserahkan berhasil diambil',
                'data' => $sampahDiserahkans,
                'count' => count($sampahDiserahkans)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'code' => 500
            ], 500);
        }
    }

    /**
     * Menampilkan detail sampah diserahkan
     * GET /api/petugas/sampah-diserahkan/{id}
     */
    public function show($id)
    {
        try {
            $user = Auth::guard('api')->user();
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized',
                    'code' => 401
                ], 401);
            }

            $sampah = SampahDiserahkan::with(['user', 'lokasiAsal', 'jenis', 'tujuanSampah'])
                ->where('id_sampah_diserahkan', $id)
                ->where('id_user', $user->id)
                ->first();

            if (!$sampah) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data tidak ditemukan',
                    'code' => 404
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Detail sampah diserahkan',
                'data' => $sampah
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'code' => 500
            ], 500);
        }
    }

    /**
     * Menyimpan data sampah diserahkan baru
     * POST /api/petugas/sampah-diserahkan
     */
    public function store(Request $request)
    {
        try {
            $user = Auth::guard('api')->user();
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized',
                    'code' => 401
                ], 401);
            }

            $validator = Validator::make($request->all(), [
                'id_lokasi' => 'required|exists:lokasi_asals,id_lokasi',
                'id_jenis' => 'required|exists:jenis,id_jenis',
                'id_tujuan_sampah' => 'required|exists:tujuan_sampahs,id_tujuan_sampah',
                'jumlah_berat' => 'required|numeric|min:0',
                'tgl_diserahkan' => 'required|date',
                'foto_diserahkan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors(),
                    'code' => 422
                ], 422);
            }

            $fotoPath = null;
            if ($request->hasFile('foto_diserahkan')) {
                $file = $request->file('foto_diserahkan');
                $filename = time() . '_' . $file->getClientOriginalName();
                $fotoPath = $file->storeAs('public/sampah_diserahkan', $filename);
                $fotoPath = str_replace('public/', '', $fotoPath);
            }

            $sampah = SampahDiserahkan::create([
                'id_user' => $user->id,
                'id_lokasi' => $request->id_lokasi,
                'id_jenis' => $request->id_jenis,
                'id_tujuan_sampah' => $request->id_tujuan_sampah,
                'jumlah_berat' => $request->jumlah_berat,
                'tgl_diserahkan' => $request->tgl_diserahkan,
                'foto_diserahkan' => $fotoPath,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Data sampah diserahkan berhasil ditambahkan',
                'data' => $sampah
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'code' => 500
            ], 500);
        }
    }

    /**
     * Mengupdate data sampah diserahkan
     * PUT /api/petugas/sampah-diserahkan/{id}
     */
    public function update(Request $request, $id)
    {
        try {
            $user = Auth::guard('api')->user();
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized',
                    'code' => 401
                ], 401);
            }

            $sampah = SampahDiserahkan::where('id_sampah_diserahkan', $id)
                ->where('id_user', $user->id)
                ->first();

            if (!$sampah) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data tidak ditemukan',
                    'code' => 404
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'id_lokasi' => 'required|exists:lokasi_asals,id_lokasi',
                'id_jenis' => 'required|exists:jenis,id_jenis',
                'id_tujuan_sampah' => 'required|exists:tujuan_sampahs,id_tujuan_sampah',
                'jumlah_berat' => 'required|numeric|min:0',
                'tgl_diserahkan' => 'required|date',
                'foto_diserahkan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors(),
                    'code' => 422
                ], 422);
            }

            if ($request->hasFile('foto_diserahkan')) {
                if ($sampah->foto_diserahkan && Storage::exists('public/' . $sampah->foto_diserahkan)) {
                    Storage::delete('public/' . $sampah->foto_diserahkan);
                }
                
                $file = $request->file('foto_diserahkan');
                $filename = time() . '_' . $file->getClientOriginalName();
                $fotoPath = $file->storeAs('public/sampah_diserahkan', $filename);
                $sampah->foto_diserahkan = str_replace('public/', '', $fotoPath);
            }

            $sampah->update([
                'id_lokasi' => $request->id_lokasi,
                'id_jenis' => $request->id_jenis,
                'id_tujuan_sampah' => $request->id_tujuan_sampah,
                'jumlah_berat' => $request->jumlah_berat,
                'tgl_diserahkan' => $request->tgl_diserahkan,
            ]);

            if ($request->hasFile('foto_diserahkan')) {
                $sampah->save();
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Data sampah diserahkan berhasil diupdate',
                'data' => $sampah
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'code' => 500
            ], 500);
        }
    }

    /**
     * Menghapus data sampah diserahkan
     * DELETE /api/petugas/sampah-diserahkan/{id}
     */
    public function destroy($id)
    {
        try {
            $user = Auth::guard('api')->user();
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized',
                    'code' => 401
                ], 401);
            }

            $sampah = SampahDiserahkan::where('id_sampah_diserahkan', $id)
                ->where('id_user', $user->id)
                ->first();

            if (!$sampah) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data tidak ditemukan',
                    'code' => 404
                ], 404);
            }

            if ($sampah->foto_diserahkan && Storage::exists('public/' . $sampah->foto_diserahkan)) {
                Storage::delete('public/' . $sampah->foto_diserahkan);
            }

            $sampah->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Data sampah diserahkan berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'code' => 500
            ], 500);
        }
    }

    /**
     * Mendapatkan data master untuk input (lokasi asal, jenis sampah, tujuan sampah)
     * GET /api/petugas/sampah-diserahkan/master-data
     */
    public function getMasterData()
    {
        try {
            $lokasiAsals = LokasiAsal::all();
            $jenisSampah = Jenis::all();
            $tujuanSampahs = TujuanSampah::all();
            // Untuk sampah diserahkan, hanya residu
            $kategoriJenis = ['residu'];

            return response()->json([
                'status' => 'success',
                'message' => 'Data master berhasil diambil',
                'data' => [
                    'lokasi_asals' => $lokasiAsals,
                    'jenis_sampah' => $jenisSampah,
                    'tujuan_sampahs' => $tujuanSampahs,
                    'kategori_jenis' => $kategoriJenis
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'code' => 500
            ], 500);
        }
    }
}
