@extends('admin.layout')
@section('title', 'Menu Laporan Neraca Sampah')

@section('content')
<div class="content-area">
    <div class="card" style="background-color: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <div class="card-body bg-primary text-white" style="border-radius: 8px 8px 0 0;">
            <h5 class="mb-0"><i class="fas fa-file-excel me-2"></i>Ekspor Laporan Logbook (Juli - Juni)</h5>
        </div>
        
        <div class="card-body">
            <div class="alert alert-warning" role="alert">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Update:</strong> Gunakan fitur <a href="{{ route('admin.laporan.export-baru') }}" class="alert-link">Export Laporan (Template)</a> untuk mengekspor laporan dengan template yang lebih baik.
            </div>
            
            <form action="{{ route('admin.laporan.export-baru') }}" method="GET" class="mb-4">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="tahun" class="form-label fw-bold">Pilih Tahun Awal Periode (Juli):</label>
                        <input type="number" class="form-control form-control-lg" id="tahun" name="tahun" 
                               min="2020" max="2099" step="1" 
                               value="{{ $tahun ?? date('Y') }}" required>
                        <small class="text-muted">
                            Memilih {{ $tahun ?? date('Y') }} akan mengekspor data Juli {{ $tahun ?? date('Y') }} - Juni {{ ($tahun ?? date('Y'))+1 }}
                        </small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="tipe" class="form-label fw-bold">Pilih Tipe Export:</label>
                        <select class="form-select form-select-lg" id="tipe" name="tipe" required>
                            <option value="lengkap">📊 Lengkap - 15 Sheet (3 Rekap + 12 Bulanan)</option>
                            <option value="bulanan">📅 Bulanan Saja - 12 Sheet (Data Harian per Bulan)</option>
                        </select>
                        <small class="text-muted" id="tipe-info">
                            Export lengkap dengan semua sheet rekap dan data harian
                        </small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-success btn-lg w-100" style="font-size: 1.1rem;">
                            <i class="fas fa-download me-2"></i>Download Excel
                        </button>
                    </div>
                </div>
            </form>

            <hr class="my-4">

            <div class="alert alert-info mb-3">
                <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Informasi Tipe Export</h6>
                
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="p-3 mb-2" style="background: #fff; border-radius: 6px; border-left: 4px solid #198754;">
                            <h6 class="fw-bold mb-2">📊 Export Lengkap (15 Sheet)</h6>
                            <ul class="mb-0 small">
                                <li><strong>Sheet 1:</strong> Rekap Neraca per Area</li>
                                <li><strong>Sheet 2:</strong> Rekap Terkelola per Bulan</li>
                                <li><strong>Sheet 3:</strong> Rekap Area (Pivot)</li>
                                <li><strong>Sheet 4-15:</strong> Data Harian 12 Bulan</li>
                            </ul>
                            <p class="mt-2 mb-0 text-muted small">
                                <i class="fas fa-check-circle text-success"></i> Cocok untuk laporan komprehensif dan analisis lengkap
                            </p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="p-3 mb-2" style="background: #fff; border-radius: 6px; border-left: 4px solid #0dcaf0;">
                            <h6 class="fw-bold mb-2">📅 Export Bulanan (12 Sheet)</h6>
                            <ul class="mb-0 small">
                                <li><strong>Juli:</strong> Data harian bulan Juli</li>
                                <li><strong>Agustus:</strong> Data harian bulan Agustus</li>
                                <li><strong>September - Juni:</strong> 10 sheet lainnya</li>
                            </ul>
                            <p class="mt-2 mb-0 text-muted small">
                                <i class="fas fa-check-circle text-success"></i> Cocok untuk melihat detail harian tanpa rekap tambahan
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card" style="background-color: #f8f9fa;">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="fas fa-calendar-alt me-2"></i>Detail Periode Fiscal Year</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Bulan</th>
                                    <th>Periode</th>
                                    <th>Sheet</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Juli</td>
                                    <td>1 - 31 Juli {{ $tahun ?? date('Y') }}</td>
                                    <td>Sheet 4</td>
                                </tr>
                                <tr>
                                    <td>Agustus</td>
                                    <td>1 - 31 Agustus {{ $tahun ?? date('Y') }}</td>
                                    <td>Sheet 5</td>
                                </tr>
                                <tr>
                                    <td>September - Mei</td>
                                    <td>Bulan 3-11 periode</td>
                                    <td>Sheet 6-14</td>
                                </tr>
                                <tr>
                                    <td>Juni</td>
                                    <td>1 - 30 Juni {{ ($tahun ?? date('Y'))+1 }}</td>
                                    <td>Sheet 15</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipeSelect = document.getElementById('tipe');
    const tipeInfo = document.getElementById('tipe-info');
    
    tipeSelect.addEventListener('change', function() {
        if (this.value === 'lengkap') {
            tipeInfo.textContent = 'Export lengkap dengan 3 sheet rekap + 12 sheet data harian per bulan';
        } else {
            tipeInfo.textContent = 'Export hanya 12 sheet data harian (tanpa sheet rekap)';
        }
    });
});
</script>
@endpush

@push('styles')
<style>
    .bg-primary { 
        background-color: #1E3F8C !important; 
    }
    .card {
        transition: all 0.3s ease;
    }
    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    .alert-info {
        background-color: #e7f3ff;
        border-color: #b3d9ff;
        color: #004085;
    }
    .form-control-lg, .form-select-lg {
        border-radius: 8px;
    }
</style>
@endpush