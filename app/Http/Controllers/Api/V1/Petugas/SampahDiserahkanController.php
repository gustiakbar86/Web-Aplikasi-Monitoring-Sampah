<?php

namespace App\Http\Controllers\Api\V1\Petugas;

use App\Http\Controllers\Controller;
use App\Models\SampahDiserahkan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SampahDiserahkanController extends Controller
{
    /**
     * GET /api/v1/sampah-diserahkan
     * Get all delivered waste records with pagination
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $page = $request->get('page', 1);

        $userId = Auth::id();
        
        $sampah = SampahDiserahkan::where('id_user', $userId)
            ->with(['lokasiAsal', 'jenis', 'tujuanSampah'])
            ->orderBy('tgl_diserahkan', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'success' => true,
            'data' => $sampah->items(),
            'total' => $sampah->total(),
            'per_page' => $sampah->perPage(),
            'current_page' => $sampah->currentPage(),
            'last_page' => $sampah->lastPage(),
        ]);
    }

    /**
     * POST /api/v1/sampah-diserahkan
     * Create new delivered waste record
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tgl_diserahkan' => 'required|date',
            'id_lokasi' => 'required|exists:lokasi_asals,id_lokasi',
            'id_jenis' => 'required|exists:jenis,id_jenis',
            'id_tujuan' => 'required|exists:tujuan_sampahs,id_tujuan',
            'jumlah_berat' => 'required|numeric|min:0.01',
            'foto_diserahkan' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $sampah = new SampahDiserahkan();
            $sampah->tgl_diserahkan = $request->tgl_diserahkan;
            $sampah->id_lokasi = $request->id_lokasi;
            $sampah->id_jenis = $request->id_jenis;
            $sampah->id_tujuan = $request->id_tujuan;
            $sampah->jumlah_berat = $request->jumlah_berat;
            $sampah->id_user = Auth::id();

            if ($request->hasFile('foto_diserahkan')) {
                $sampah->foto_diserahkan = $request->file('foto_diserahkan')->store('sampah_diserahkan', 'public');
            }

            $sampah->save();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil ditambahkan',
                'data' => $sampah,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /api/v1/sampah-diserahkan/{id}
     * Get single delivered waste record
     */
    public function show($id)
    {
        $sampah = SampahDiserahkan::where('id_user', Auth::id())
            ->with(['lokasiAsal', 'jenis', 'tujuanSampah'])
            ->find($id);

        if (!$sampah) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $sampah,
        ]);
    }

    /**
     * PUT /api/v1/sampah-diserahkan/{id}
     * Update delivered waste record
     */
    public function update(Request $request, $id)
    {
        $sampah = SampahDiserahkan::where('id_user', Auth::id())->find($id);

        if (!$sampah) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'tgl_diserahkan' => 'required|date',
            'id_lokasi' => 'required|exists:lokasi_asals,id_lokasi',
            'id_jenis' => 'required|exists:jenis,id_jenis',
            'id_tujuan' => 'required|exists:tujuan_sampahs,id_tujuan',
            'jumlah_berat' => 'required|numeric|min:0.01',
            'alasan_edit' => 'nullable|string',
            'foto_diserahkan' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $sampah->tgl_diserahkan = $request->tgl_diserahkan;
            $sampah->id_lokasi = $request->id_lokasi;
            $sampah->id_jenis = $request->id_jenis;
            $sampah->id_tujuan = $request->id_tujuan;
            $sampah->jumlah_berat = $request->jumlah_berat;
            $sampah->alasan_edit = $request->alasan_edit;

            if ($request->hasFile('foto_diserahkan')) {
                if ($sampah->foto_diserahkan) {
                    \Storage::disk('public')->delete($sampah->foto_diserahkan);
                }
                $sampah->foto_diserahkan = $request->file('foto_diserahkan')->store('sampah_diserahkan', 'public');
            }

            $sampah->save();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui',
                'data' => $sampah,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
