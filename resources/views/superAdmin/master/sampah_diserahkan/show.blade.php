@extends('superAdmin.layout')

@section('title', 'Detail Sampah Diserahkan')

@section('content')
<div class="content-area-table">
    <div class="text-white p-3 rounded mb-4" style="background-color: #1E3F8C;">
        <h3 class="mb-0">Detail Sampah Diserahkan</h3>
    </div>

    <div class="card">
        <div class="card-body">
            <!-- Data Detail -->
            <div class="row mb-3">
                <div class="col-md-6 pb-3 border-bottom">
                    <label class="form-label fw-bold text-muted">Tanggal</label>
                    <p class="mb-0 fs-5">
                        {{ \Carbon\Carbon::parse($sampahDiserahkan->tgl_diserahkan)->format('d-m-Y') }}
                    </p>
                </div>
                <div class="col-md-6 pb-3 border-bottom">
                    <label class="form-label fw-bold text-muted">Petugas</label>
                    <p class="mb-0 fs-5">
                        {{ $sampahDiserahkan->user->name ?? '-' }}
                    </p>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6 pb-3 border-bottom">
                    <label class="form-label fw-bold text-muted">Lokasi Asal</label>
                    <p class="mb-0 fs-5">
                        {{ $sampahDiserahkan->lokasiAsal->nama_lokasi ?? '-' }}
                    </p>
                </div>
                <div class="col-md-6 pb-3 border-bottom">
                    <label class="form-label fw-bold text-muted">Kategori Jenis</label>
                    <p class="mb-0 fs-5">
                        {{ $sampahDiserahkan->jenis->kategori_jenis ?? '-' }}
                    </p>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6 pb-3 border-bottom">
                    <label class="form-label fw-bold text-muted">Jenis Sampah</label>
                    <p class="mb-0 fs-5">
                        {{ $sampahDiserahkan->jenis->nama_jenis ?? '-' }}
                    </p>
                </div>
                <div class="col-md-6 pb-3 border-bottom">
                    <label class="form-label fw-bold text-muted">Berat (Kg)</label>
                    <p class="mb-0 fs-5">
                        {{ number_format($sampahDiserahkan->jumlah_berat, 2) }}
                    </p>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6 pb-3 border-bottom">
                    <label class="form-label fw-bold text-muted">Tujuan Sampah</label>
                    <p class="mb-0 fs-5">
                        {{ $sampahDiserahkan->tujuanSampah->nama_tujuan ?? '-' }}
                    </p>
                </div>
                <div class="col-md-6 pb-3 border-bottom">
                    <label class="form-label fw-bold text-muted">Foto</label>
                    <p class="mb-2">
                        @if($sampahDiserahkan->foto_serahkan)
                            <a href="{{ asset($sampahDiserahkan->foto_serahkan) }}" target="_blank" class="btn btn-sm btn-light">
                                <i class="fas fa-file-image"></i> Lihat Foto
                            </a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </p>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12 pb-3">
                    @if($sampahDiserahkan->foto_serahkan)
                    <img src="{{ asset($sampahDiserahkan->foto_serahkan) }}" alt="Foto Sampah" style="max-width: 300px; border-radius: 4px;">
                    @endif
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12 pb-3">
                    <label class="form-label fw-bold text-muted">Alasan Edit</label>
                    <div style="background-color: #f8f9fa; border-left: 4px solid #ffc107; padding: 12px; border-radius: 4px;">
                        {{ $sampahDiserahkan->alasan_edit ?? '-' }}
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="d-flex justify-content-start gap-2 mt-4">
                <a href="{{ route('superadmin.master.sampah-diserahkan') }}" class="btn btn-secondary">
                    Kembali
                </a>
                <a href="{{ route('superadmin.master.sampah-diserahkan.edit', $sampahDiserahkan->id) }}" class="btn btn-warning">
                    Edit
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
