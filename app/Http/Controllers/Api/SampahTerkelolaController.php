<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SampahTerkelola;
use App\Models\Jenis;
use App\Models\LokasiAsal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SampahTerkelolaController extends Controller
{
    /**
     * Menampilkan daftar sampah terkelola untuk petugas yang login
     * GET /api/petugas/sampah-terkelola
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

            $sampahTerkelolas = SampahTerkelola::with(['user', 'lokasiAsal', 'jenis'])
                ->where('id_user', $user->id)
                ->orderBy('tgl', 'desc')
                ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Data sampah terkelola berhasil diambil',
                'data' => $sampahTerkelolas,
                'count' => count($sampahTerkelolas)
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
     * Menampilkan detail sampah terkelola
     * GET /api/petugas/sampah-terkelola/{id}
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

            $sampah = SampahTerkelola::with(['user', 'lokasiAsal', 'jenis'])
                ->where('id_sampah_terkelola', $id)
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
                'message' => 'Detail sampah terkelola',
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
     * Menyimpan data sampah terkelola baru
     * POST /api/petugas/sampah-terkelola
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
                'jumlah_berat' => 'required|numeric|min:0',
                'tgl' => 'required|date',
                'foto_kelola' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
            if ($request->hasFile('foto_kelola')) {
                $file = $request->file('foto_kelola');
                $filename = time() . '_' . $file->getClientOriginalName();
                $fotoPath = $file->storeAs('public/sampah_terkelola', $filename);
                $fotoPath = str_replace('public/', '', $fotoPath);
            }

            $sampah = SampahTerkelola::create([
                'id_user' => $user->id,
                'id_lokasi' => $request->id_lokasi,
                'id_jenis' => $request->id_jenis,
                'jumlah_berat' => $request->jumlah_berat,
                'tgl' => $request->tgl,
                'foto_kelola' => $fotoPath,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Data sampah terkelola berhasil ditambahkan',
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
     * Mengupdate data sampah terkelola
     * PUT /api/petugas/sampah-terkelola/{id}
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

            $sampah = SampahTerkelola::where('id_sampah_terkelola', $id)
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
                'jumlah_berat' => 'required|numeric|min:0',
                'tgl' => 'required|date',
                'foto_kelola' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors(),
                    'code' => 422
                ], 422);
            }

            if ($request->hasFile('foto_kelola')) {
                if ($sampah->foto_kelola && Storage::exists('public/' . $sampah->foto_kelola)) {
                    Storage::delete('public/' . $sampah->foto_kelola);
                }
                
                $file = $request->file('foto_kelola');
                $filename = time() . '_' . $file->getClientOriginalName();
                $fotoPath = $file->storeAs('public/sampah_terkelola', $filename);
                $sampah->foto_kelola = str_replace('public/', '', $fotoPath);
            }

            $sampah->update([
                'id_lokasi' => $request->id_lokasi,
                'id_jenis' => $request->id_jenis,
                'jumlah_berat' => $request->jumlah_berat,
                'tgl' => $request->tgl,
            ]);

            if ($request->hasFile('foto_kelola')) {
                $sampah->save();
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Data sampah terkelola berhasil diupdate',
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
     * Menghapus data sampah terkelola
     * DELETE /api/petugas/sampah-terkelola/{id}
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

            $sampah = SampahTerkelola::where('id_sampah_terkelola', $id)
                ->where('id_user', $user->id)
                ->first();

            if (!$sampah) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data tidak ditemukan',
                    'code' => 404
                ], 404);
            }

            if ($sampah->foto_kelola && Storage::exists('public/' . $sampah->foto_kelola)) {
                Storage::delete('public/' . $sampah->foto_kelola);
            }

            $sampah->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Data sampah terkelola berhasil dihapus'
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
     * Mendapatkan data master untuk input (lokasi asal, jenis sampah)
     * GET /api/petugas/sampah-terkelola/master-data
     */
    public function getMasterData()
    {
        try {
            $lokasiAsals = LokasiAsal::all();
            $jenisSampah = Jenis::all();
            // Untuk sampah terkelola, hanya organik dan anorganik
            $kategoriJenis = ['organik', 'anorganik'];

            return response()->json([
                'status' => 'success',
                'message' => 'Data master berhasil diambil',
                'data' => [
                    'lokasi_asals' => $lokasiAsals,
                    'jenis_sampah' => $jenisSampah,
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
