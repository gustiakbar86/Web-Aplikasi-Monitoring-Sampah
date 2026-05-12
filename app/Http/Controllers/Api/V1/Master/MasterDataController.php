<?php

namespace App\Http\Controllers\Api\V1\Master;

use App\Http\Controllers\Controller;
use App\Models\Jenis;
use App\Models\LokasiAsal;
use App\Models\TujuanSampah;

class MasterDataController extends Controller
{
    /**
     * GET /api/v1/master-data
     * Get all master data (Jenis, Lokasi Asal, Tujuan Sampah)
     */
    public function index()
    {
        try {
            $jenis = Jenis::all();
            $lokasiAsal = LokasiAsal::all();
            $tujuanSampah = TujuanSampah::all();
            
            // Filter jenis by kategori for terkelola (organik, anorganik) and diserahkan (residu)
            $jenisTerkelola = $jenis->whereIn('kategori_jenis', ['organik', 'anorganik'])->values();
            $jenisDiserahkan = $jenis->where('kategori_jenis', 'residu')->values();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'jenis' => $jenis,
                    'jenis_terkelola' => $jenisTerkelola,
                    'jenis_diserahkan' => $jenisDiserahkan,
                    'lokasi_asal' => $lokasiAsal,
                    'tujuan_sampah' => $tujuanSampah,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /api/v1/master/lokasi-asal
     */
    public function lokasiAsal()
    {
        try {
            $data = LokasiAsal::all();
            
            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /api/v1/master/jenis
     */
    public function jenis()
    {
        try {
            $data = Jenis::all();
            
            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /api/v1/master/tujuan-sampah
     */
    public function tujuanSampah()
    {
        try {
            $data = TujuanSampah::all();
            
            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
