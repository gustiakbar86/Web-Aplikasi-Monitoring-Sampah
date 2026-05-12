# Quick Test Guide - Export Laporan System

## 🚀 Sistem sudah SIAP!

Semua komponen sudah diimplementasikan dan siap untuk testing:

### ✅ Apa yang Sudah Selesai:
1. ✅ Routes untuk SuperAdmin & Admin
2. ✅ Controller methods (showExportForm & processExport) 
3. ✅ Blade views dengan UI yang bagus
4. ✅ Logika export dengan template injection
5. ✅ Helper method untuk mapping kolom
6. ✅ Template file sudah ada di folder storage

---

## 🧪 Cara Testing

### Test 1: SuperAdmin Export Tahunan

**Step 1: Login & Akses**
```
- Login sebagai SuperAdmin
- Klik menu "Export Laporan (Template)" di navbar
- URL: http://yourapp.local/superadmin/laporan/export-baru
```

**Step 2: Isi Form**
```
- Tab: "Tahunan" (default sudah selected)
- Tahun: 2025 (atau tahun sesuai data yang ada)
- Instansi: Pilih 1-3 instansi (default: semua checked)
- Klik: "Download Excel"
```

**Step 3: Verifikasi**
```
✓ File terdownload dengan nama: Laporan_Neraca_Sampah_2025_*.xlsx
✓ File dapat dibuka di Excel
✓ Sheet "Rekap Neraca Pengelolaan Sampah" ada di awal
✓ Header menunjukkan nama instansi yang dipilih
✓ Sheet Juli, Agustus, ... Juni ada
✓ Data terisi di kolom yang sesuai (C, D, E untuk Kantor, dst)
```

---

### Test 2: SuperAdmin Export Bulanan

**Step 1: Isi Form**
```
- Klik tab "Bulanan"
- Tahun: 2025
- Bulan: Januari
- Instansi: Pilih yang mana saja
- Klik: "Download Excel"
```

**Step 2: Verifikasi**
```
✓ File download
✓ Sheet Januari berisi data
✓ Sheet bulan lain kosong/tidak ada data
```

---

### Test 3: Admin Export

**Step 1: Login & Akses**
```
- Login sebagai Admin (Role 2)
- Klik menu "Export Laporan (Template)" di navbar
- URL: http://yourapp.local/admin/laporan/export-baru
```

**Step 2: Isi Form**
```
- Tab: "Tahunan" atau "Bulanan" (terserah)
- Tahun: 2025
- Bulan: (kosongkan jika tahunan, isi jika bulanan)
- Instansi: Tertulis nama instansi Admin (tidak bisa dipilih)
- Klik: "Download Excel"
```

**Step 3: Verifikasi**
```
✓ File download
✓ Data hanya dari instansi Admin tersebut
✓ Tidak ada instansi lain dalam file
```

---

## 🔍 Troubleshooting

### Error: "File template tidak ditemukan"
**Solusi:**
```
Cek file ada di: storage/app/public/templates/template_master.xlsx

Jika tidak ada, upload file template terlebih dahulu.
Path harus persis: /storage/app/public/templates/template_master.xlsx
```

### Error: "Tahun harus dipilih"
**Solusi:**
```
Pastikan dropdown Tahun sudah dipilih sebelum submit form.
```

### Export lambat / Timeout
**Status:**
```
Normal jika ada banyak data. Sistem sudah diset:
- Timeout: 600 detik (10 menit)
- Memory: 1024 MB

Jika masih timeout, data mungkin terlalu besar.
Coba filter dengan bulan specific (Bulanan mode).
```

### File tidak bisa dibuka di Excel
**Solusi:**
```
1. Download ulang
2. Pastikan browser tidak corrupt file saat download
3. Cek permission folder storage/app/public/templates/
```

---

## 📊 Data Mapping Verification

Saat membuka file Excel, verifikasi mapping ini:

| Sampah Terkelola | Lokasi | Jenis | Expected Column |
|---|---|---|---|
| Organik | Kantor | Organik | C |
| Anorganik | Kantor | Anorganik | D |
| Residu | Kantor | Residu | E |
| Organik | Parkir | Organik | G |
| Organik | Area Lainnya | Organik | W |

| Sampah Diserahkan | Lokasi | Jenis | Expected Column |
|---|---|---|---|
| Apapun | Kantor | Apapun | E (Residu) |
| Apapun | Parkir | Apapun | I (Residu) |
| Apapun | Area Lainnya | Apapun | Y (Residu) |

---

## 🎯 Success Criteria

Export dianggap **BERHASIL** jika:

✅ File berhasil terdownload
✅ File bisa dibuka di Excel  
✅ Header menunjukkan instansi yang benar
✅ Data terisi di sheet yang sesuai (bulannya)
✅ Kolom data sesuai dengan mapping (Kantor=C-E, Parkir=G-I, dst)
✅ Nilai akumulasi jika ada multiple entries tanggal sama
✅ Admin hanya lihat data instansinya sendiri
✅ SuperAdmin bisa melihat multiple instansi yang dipilih

---

## 📋 Checklist Testing

- [ ] SuperAdmin - Test Tahunan Export
- [ ] SuperAdmin - Test Bulanan Export
- [ ] SuperAdmin - Test Multi-Instansi Selection
- [ ] Admin - Test Tahunan Export
- [ ] Admin - Test Bulanan Export  
- [ ] Admin - Verify instansi filter working
- [ ] File dapat dibuka di Excel
- [ ] Kolom mapping verifikasi
- [ ] Data akumulasi working
- [ ] Navbar link berfungsi
- [ ] Form validation working
- [ ] Error handling tested

---

## 🔗 Important URLs

```
SuperAdmin Export:    /superadmin/laporan/export-baru
Admin Export:         /admin/laporan/export-baru
Template Location:    /storage/app/public/templates/template_master.xlsx
Log File:             /storage/logs/laravel.log
```

---

## 💡 Notes

- Sistem menggunakan **hybrid template injection** = membaca template Excel → inject data → download
- Tidak membuat Excel dari nol, tapi menggunakan template existing
- Lebih cepat & akurat dibanding method lama
- Data terkelola & diserahkan di-handle berbeda sesuai business logic
- Admin security enforced di controller level

---

**Status**: ✅ SIAP TESTING
**Version**: 1.0
**Date**: 2025-12-23

