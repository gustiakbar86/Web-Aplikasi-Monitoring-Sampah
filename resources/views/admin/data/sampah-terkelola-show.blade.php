@extends('admin.layout')

@section('title', 'Detail Sampah Terkelola')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="p-4 rounded-top" style="background-color: #1E3F8C;">
                <h4 class="text-white mb-0">Detail Sampah Terkelola</h4>
            </div>
            <div class="bg-white p-4 rounded-bottom shadow">

            <div class="bg-white p-4 rounded-bottom shadow">
                <!-- Data Detail -->
                <div class="row mb-3">
                    <div class="col-md-6 pb-3 border-bottom">
                        <label class="form-label fw-bold text-muted">Pengguna</label>
                        <p class="mb-0 fs-5">
                            {{ $sampah->user->instansi->nama_instansi ?? '-' }} - {{ $sampah->user->name ?? '-' }}
                        </p>
                    </div>
                    <div class="col-md-6 pb-3 border-bottom">
                        <label class="form-label fw-bold text-muted">Tanggal</label>
                        <p class="mb-0 fs-5">
                            {{ \Carbon\Carbon::parse($sampah->tgl_kelola)->format('d-m-Y') }}
                        </p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6 pb-3 border-bottom">
                        <label class="form-label fw-bold text-muted">Sumber Sampah</label>
                        <p class="mb-0 fs-5">
                            {{ $sampah->lokasiAsal->nama_lokasi ?? '-' }}
                        </p>
                    </div>
                    <div class="col-md-6 pb-3 border-bottom">
                        <label class="form-label fw-bold text-muted">Kategori Jenis</label>
                        <p class="mb-0 fs-5">
                            {{ $sampah->jenis->kategori_jenis ?? '-' }}
                        </p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6 pb-3 border-bottom">
                        <label class="form-label fw-bold text-muted">Jenis Sampah</label>
                        <p class="mb-0 fs-5">
                            {{ $sampah->jenis->nama_jenis ?? '-' }}
                        </p>
                    </div>
                    <div class="col-md-6 pb-3 border-bottom">
                        <label class="form-label fw-bold text-muted">Berat (Kg)</label>
                        <p class="mb-0 fs-5">
                            {{ number_format($sampah->jumlah_berat, 2) }}
                        </p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6 pb-3 border-bottom">
                        <label class="form-label fw-bold text-muted">Foto</label>
                        <p class="mb-2">
                            @if($sampah->foto_kelola)
                                <a href="{{ asset('storage/' . $sampah->foto_kelola) }}" target="_blank" class="btn btn-sm btn-light">
                                    <i class="fas fa-file-image"></i> Lihat Foto
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </p>
                        @if($sampah->foto_kelola)
                        <div>
                            <img src="{{ asset('storage/' . $sampah->foto_kelola) }}" alt="Foto Sampah" style="max-width: 300px; border-radius: 4px;">
                        </div>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12 pb-3">
                        <label class="form-label fw-bold text-muted">Alasan Edit</label>
                        <div style="background-color: #f8f9fa; border-left: 4px solid #ffc107; padding: 12px; border-radius: 4px;">
                            {{ $sampah->alasan_edit ?? '-' }}
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="d-flex justify-content-start gap-2 mt-4">
                    <a href="{{ route('admin.data.sampah-terkelola') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <a href="{{ route('admin.data.sampah-terkelola.edit', $sampah->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </div>
</div>
@endsection
