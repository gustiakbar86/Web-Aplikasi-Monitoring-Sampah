@extends('superAdmin.layout')

@section('title', 'Detail Sampah Terkelola')

@section('content')
<div class="content-area-table">
    <div class="text-white p-3 rounded mb-4" style="background-color: #1E3F8C;">
        <h3 class="mb-0">Detail Sampah Terkelola</h3>
    </div>

    <div class="card">
        <div class="card-body">
            <!-- Data Detail -->
            <div class="row mb-3">
                <div class="col-md-6 pb-3 border-bottom">
                    <label class="form-label fw-bold text-muted">Tanggal</label>
                    <p class="mb-0 fs-5">
                        {{ \Carbon\Carbon::parse($sampahTerkelola->tgl)->format('d-m-Y') }}
                    </p>
                </div>
                <div class="col-md-6 pb-3 border-bottom">
                    <label class="form-label fw-bold text-muted">Petugas</label>
                    <p class="mb-0 fs-5">
                        {{ $sampahTerkelola->user->name ?? '-' }}
                    </p>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6 pb-3 border-bottom">
                    <label class="form-label fw-bold text-muted">Lokasi Asal</label>
                    <p class="mb-0 fs-5">
                        {{ $sampahTerkelola->lokasiAsal->nama_lokasi ?? '-' }}
                    </p>
                </div>
                <div class="col-md-6 pb-3 border-bottom">
                    <label class="form-label fw-bold text-muted">Kategori Jenis</label>
                    <p class="mb-0 fs-5">
                        {{ $sampahTerkelola->jenis->kategori_jenis ?? '-' }}
                    </p>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6 pb-3 border-bottom">
                    <label class="form-label fw-bold text-muted">Jenis Sampah</label>
                    <p class="mb-0 fs-5">
                        {{ $sampahTerkelola->jenis->nama_jenis ?? '-' }}
                    </p>
                </div>
                <div class="col-md-6 pb-3 border-bottom">
                    <label class="form-label fw-bold text-muted">Berat (Kg)</label>
                    <p class="mb-0 fs-5">
                        {{ number_format($sampahTerkelola->jumlah_berat, 2) }}
                    </p>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6 pb-3 border-bottom">
                    <label class="form-label fw-bold text-muted">Foto</label>
                    <p class="mb-2">
                        @if($sampahTerkelola->foto_kelola)
                            <a href="{{ asset($sampahTerkelola->foto_kelola) }}" target="_blank" class="btn btn-sm btn-light">
                                <i class="fas fa-file-image"></i> Lihat Foto
                            </a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </p>
                    @if($sampahTerkelola->foto_kelola)
                    <div>
                        <img src="{{ asset($sampahTerkelola->foto_kelola) }}" alt="Foto Sampah" style="max-width: 300px; border-radius: 4px;">
                    </div>
                    @endif
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12 pb-3">
                    <label class="form-label fw-bold text-muted">Alasan Edit</label>
                    <div style="background-color: #f8f9fa; border-left: 4px solid #ffc107; padding: 12px; border-radius: 4px;">
                        {{ $sampahTerkelola->alasan_edit ?? '-' }}
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="d-flex justify-content-start gap-2 mt-4">
                <a href="{{ route('superadmin.master.sampah-terkelola') }}" class="btn btn-secondary">
                    Kembali
                </a>
                <a href="{{ route('superadmin.master.sampah-terkelola.edit', $sampahTerkelola->id) }}" class="btn btn-warning">
                    Edit
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
