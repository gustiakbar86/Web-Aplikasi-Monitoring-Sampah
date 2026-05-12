@extends('superAdmin.layout')
@section('title', 'Export Laporan Sampah')

@section('content')
<main class="container px-4 py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-1">
                <i class="fas fa-file-excel me-2 text-success"></i>
                Export Laporan Neraca Limbah
            </h2>
            <p class="text-muted">
                Download laporan dalam format Excel (.xlsx)
            </p>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <ul class="nav nav-tabs nav-fill" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold" id="tahunan-tab" 
                            data-bs-toggle="tab" data-bs-target="#tahunan" 
                            type="button" role="tab">
                        <i class="fas fa-calendar me-2"></i>
                        Laporan Tahunan (Rekap Neraca)
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold" id="bulanan-tab" 
                            data-bs-toggle="tab" data-bs-target="#bulanan" 
                            type="button" role="tab">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Laporan Bulanan (Multi Select)
                    </button>
                </li>
            </ul>
        </div>
    </div>

    <div class="tab-content">

        <div class="tab-pane fade show active" id="tahunan" role="tabpanel">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0 fw-bold">Filter Laporan Tahunan</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('superadmin.laporan.export-baru.process') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="tahunan">

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Pilih Tahun Laporan</label>
                                    <select name="tahun" id="tahun_tahunan" class="form-select form-select-lg" required>
                                        <option value="">-- Pilih Tahun --</option>
                                        @for ($year = now()->year; $year >= now()->year - 5; $year--)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endfor
                                    </select>
                                    <small class="text-muted">
                                        Periode: Juli <span id="periode_start">...</span> - Juni <span id="periode_end">...</span>
                                    </small>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Pilih Instansi / Sumber Data</label>
                                    <div class="card bg-light border">
                                        <div class="card-header py-1">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="checkAllInstansiTahunan" checked>
                                                <label class="form-check-label fw-bold small" for="checkAllInstansiTahunan">Pilih Semua</label>
                                            </div>
                                        </div>
                                        <div class="card-body p-2" style="max-height: 200px; overflow-y: auto;">
                                            @foreach(\App\Models\Instansi::all() as $instansi)
                                                <div class="form-check">
                                                    <input class="form-check-input instansi-checkbox-tahunan" 
                                                           type="checkbox" 
                                                           name="instansi_ids[]" 
                                                           value="{{ $instansi->id_instansi }}" 
                                                           checked>
                                                    <label class="form-check-label small">{{ $instansi->nama_instansi }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="fas fa-download me-2"></i> Download Excel Tahunan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="bulanan" role="tabpanel">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0 fw-bold">Filter Laporan Bulanan</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('superadmin.laporan.export-baru.process') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="bulanan">

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Pilih Tahun</label>
                                    <select name="tahun" class="form-select form-select-lg" required>
                                        <option value="">-- Pilih Tahun --</option>
                                        @for ($year = now()->year; $year >= now()->year - 5; $year--)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endfor
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Pilih Bulan (Bisa Lebih Dari Satu)</label>
                                    <div class="form-check mb-2 border-bottom pb-2">
                                        <input class="form-check-input" type="checkbox" id="checkAllBulan">
                                        <label class="form-check-label fw-bold text-primary" for="checkAllBulan">
                                            Pilih Semua Bulan (Jan-Des)
                                        </label>
                                    </div>
                                    <div class="row g-2">
                                        @php
                                            $bulanList = [
                                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                            ];
                                        @endphp
                                        @foreach($bulanList as $num => $name)
                                            <div class="col-md-4 col-6">
                                                <div class="form-check p-2 border rounded bg-white">
                                                    <input class="form-check-input bulan-checkbox" type="checkbox" name="bulan[]" value="{{ $num }}" id="bln_{{ $num }}">
                                                    <label class="form-check-label w-100" for="bln_{{ $num }}" style="cursor:pointer">{{ $name }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Pilih Instansi</label>
                                    <div class="card bg-light border">
                                        <div class="card-header py-1">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="checkAllInstansiBulan" checked>
                                                <label class="form-check-label fw-bold small" for="checkAllInstansiBulan">Pilih Semua</label>
                                            </div>
                                        </div>
                                        <div class="card-body p-2" style="max-height: 200px; overflow-y: auto;">
                                            @foreach(\App\Models\Instansi::all() as $instansi)
                                                <div class="form-check">
                                                    <input class="form-check-input instansi-checkbox-bulan" 
                                                           type="checkbox" 
                                                           name="instansi_ids[]" 
                                                           value="{{ $instansi->id_instansi }}" 
                                                           checked>
                                                    <label class="form-check-label small">{{ $instansi->nama_instansi }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-info btn-lg text-white">
                                        <i class="fas fa-file-export me-2"></i> Download Excel Bulanan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>

{{-- JAVASCRIPT UNTUK CHECK-ALL DAN PERIODE TEXT --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. UPDATE PERIODE TAHUNAN TEXT
    const tahunSelect = document.getElementById('tahun_tahunan');
    const periodeStart = document.getElementById('periode_start');
    const periodeEnd = document.getElementById('periode_end');

    if(tahunSelect){
        tahunSelect.addEventListener('change', function() {
            const val = this.value;
            if(val) {
                periodeStart.innerText = parseInt(val) - 1; 
                periodeEnd.innerText = val;                 
            } else {
                periodeStart.innerText = '...';
                periodeEnd.innerText = '...';
            }
        });
    }

    // 2. CHECK ALL - INSTANSI TAHUNAN
    const checkAllInstansiTahunan = document.getElementById('checkAllInstansiTahunan');
    const instansiCheckboxesTahunan = document.querySelectorAll('.instansi-checkbox-tahunan');
    if(checkAllInstansiTahunan){
        checkAllInstansiTahunan.addEventListener('change', function() {
            instansiCheckboxesTahunan.forEach(cb => cb.checked = this.checked);
        });
    }

    // 3. CHECK ALL - BULAN
    const checkAllBulan = document.getElementById('checkAllBulan');
    const bulanCheckboxes = document.querySelectorAll('.bulan-checkbox');
    if(checkAllBulan){
        checkAllBulan.addEventListener('change', function() {
            bulanCheckboxes.forEach(cb => cb.checked = this.checked);
        });
    }

    // 4. CHECK ALL - INSTANSI BULANAN
    const checkAllInstansiBulan = document.getElementById('checkAllInstansiBulan');
    const instansiCheckboxesBulan = document.querySelectorAll('.instansi-checkbox-bulan');
    if(checkAllInstansiBulan){
        checkAllInstansiBulan.addEventListener('change', function() {
            instansiCheckboxesBulan.forEach(cb => cb.checked = this.checked);
        });
    }
});
</script>
@endsection
