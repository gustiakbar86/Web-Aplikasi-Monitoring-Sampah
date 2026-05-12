<?php

namespace App\Http\Controllers\Api\V1\Petugas;

use App\Http\Controllers\Controller;
use App\Models\SampahTerkelola;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class SampahTerkelolaController extends Controller
{
    /**
     * GET /api/v1/sampah-terkelola
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $sampah = SampahTerkelola::where('id_user', Auth::id())
            ->with(['lokasiAsal', 'jenis'])
            ->orderBy('tgl', 'desc')
            ->paginate($perPage);

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
     * POST /api/v1/sampah-terkelola
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tgl' => 'required|date',
            'id_lokasi' => 'required|exists:lokasi_asals,id_lokasi',
            'id_jenis' => 'required|exists:jenis,id_jenis',
            'jumlah_berat' => 'required|numeric|min:0.01',
            'foto_kelola' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $sampah = SampahTerkelola::create([
                'tgl' => $request->tgl,
                'id_lokasi' => $request->id_lokasi,
                'id_jenis' => $request->id_jenis,
                'jumlah_berat' => $request->jumlah_berat,
                'id_user' => Auth::id(),
            ]);

            if ($request->hasFile('foto_kelola')) {
                $sampah->foto_kelola = $request->file('foto_kelola')
                    ->store('sampah_terkelola', 'public');
                $sampah->save();
            }

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
     * GET /api/v1/sampah-terkelola/{id}
     */
    public function show($id)
    {
        $sampah = SampahTerkelola::where('id', $id)
            ->where('id_user', Auth::id())
            ->with(['lokasiAsal', 'jenis'])
            ->first();

        if (!$sampah) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan atau bukan milik anda',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $sampah,
        ]);
    }

    /**
     * PUT /api/v1/sampah-terkelola/{id}
     */
    public function update(Request $request, $id)
    {
        $sampah = SampahTerkelola::where('id', $id)
            ->where('id_user', Auth::id())
            ->first();

        if (!$sampah) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan atau bukan milik anda',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'tgl' => 'required|date',
            'id_lokasi' => 'required|exists:lokasi_asals,id_lokasi',
            'id_jenis' => 'required|exists:jenis,id_jenis',
            'jumlah_berat' => 'required|numeric|min:0.01',
            'alasan_edit' => 'nullable|string',
            'foto_kelola' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $sampah->update([
                'tgl' => $request->tgl,
                'id_lokasi' => $request->id_lokasi,
                'id_jenis' => $request->id_jenis,
                'jumlah_berat' => $request->jumlah_berat,
                'alasan_edit' => $request->alasan_edit,
            ]);

            if ($request->hasFile('foto_kelola')) {
                if ($sampah->foto_kelola && Storage::disk('public')->exists($sampah->foto_kelola)) {
                    Storage::disk('public')->delete($sampah->foto_kelola);
                }

                $sampah->foto_kelola = $request->file('foto_kelola')
                    ->store('sampah_terkelola', 'public');

                $sampah->save();
            }

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

    /**
     * DELETE /api/v1/sampah-terkelola/{id}
     */
    public function destroy($id)
    {
        $sampah = SampahTerkelola::where('id', $id)
            ->where('id_user', Auth::id())
            ->first();

        if (!$sampah) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan atau bukan milik anda',
            ], 404);
        }

        try {
            if ($sampah->foto_kelola && Storage::disk('public')->exists($sampah->foto_kelola)) {
                Storage::disk('public')->delete($sampah->foto_kelola);
            }

            $sampah->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
