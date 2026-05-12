<?php

namespace App\Helpers;

/**
 * Helper untuk upload file
 * Single source of truth untuk semua proses upload
 */
class FileUploadHelper
{
    /**
     * Upload foto sampah
     * 
     * @param $file File dari request
     * @return string Path relatif file (contoh: 'foto-sampah/xyz123.jpg')
     */
    public static function uploadFotoSampah($file)
    {
        if (!$file) {
            return null;
        }

        $nama = uniqid() . '.' . $file->getClientOriginalExtension();
        // Simpan langsung di public_html (document root), bukan di public/
        $direktori = base_path('../public_html/foto-sampah');
        
        // Buat direktori jika belum ada
        if (!is_dir($direktori)) {
            mkdir($direktori, 0755, true);
        }

        $file->move($direktori, $nama);
        
        return 'foto-sampah/' . $nama;
    }

    /**
     * Upload dokumen
     * 
     * @param $file File dari request
     * @return string Path relatif file (contoh: 'dokumen-kerjasama/xyz123.pdf')
     */
    public static function uploadDokumen($file)
    {
        if (!$file) {
            return null;
        }

        $nama = uniqid() . '.' . $file->getClientOriginalExtension();
        // Simpan langsung di public_html (document root), bukan di public/
        $direktori = base_path('../public_html/dokumen-kerjasama');
        
        // Buat direktori jika belum ada
        if (!is_dir($direktori)) {
            mkdir($direktori, 0755, true);
        }

        $file->move($direktori, $nama);
        
        return 'dokumen-kerjasama/' . $nama;
    }

    /**
     * Hapus file dari public
     * 
     * @param string $path Path relatif file
     * @return bool
     */
    public static function deleteFile($path)
    {
        if (!$path) {
            return true;
        }

        $fullPath = base_path('../public_html/' . $path);
        
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }

        return true;
    }

    /**
     * Get direktori upload
     * Gunakan ini kalau perlu lihat path lengkap
     */
    public static function getFotoSampahPath()
    {
        return base_path('../public_html/foto-sampah');
    }

    public static function getDokumenPath()
    {
        return base_path('../public_html/dokumen-kerjasama');
    }
}
