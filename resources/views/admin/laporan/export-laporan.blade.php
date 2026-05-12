@extends('admin.layout')
@section('title', 'Export Laporan Sampah')

@section('content')
<main class="container-fluid py-4">
    <div class="row mb-4 justify-content-center">
        <div class="col-lg-6">
            <h2 class="mb-1">
                <i class="fas fa-file-excel me-2 text-success"></i>
                Export Laporan Neraca Limbah
            </h2>
            <p class="text-muted">
                Download laporan dalam format Excel (.xlsx) dengan Template Resmi.
            </p>
        </div>
    </div>

    <div class="row mb-3 justify-content-center">
        <div class="col-lg-6">
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
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0 fw-bold">Filter Laporan Tahunan</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.laporan.export-baru.process') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="tahunan">

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Pilih Periode Laporan</label>
                                    <select name="tahun" id="tahun_tahunan" class="form-select form-select-lg" required>
                                        <option value="">-- Pilih Periode --</option>
                                        @for ($year = now()->year; $year >= now()->year - 5; $year--)
                                            <option value="{{ $year }}">Juli {{ $year }} - Juni {{ $year + 1 }}</option>
                                        @endfor
                                    </select>
                                    <small class="text-muted">
                                        Periode: Juli <span id="periode_start">...</span> - Juni <span id="periode_end">...</span>
                                    </small>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Pilih Instansi (Bisa Lebih Dari Satu)</label>
                                    <div class="form-check mb-2 border-bottom pb-2">
                                        <input class="form-check-input" type="checkbox" id="checkAllInstansi">
                                        <label class="form-check-label fw-bold text-primary" for="checkAllInstansi">
                                            Pilih Semua Instansi
                                        </label>
                                    </div>
                                    <div class="row g-2">
                                        @forelse($instansiList as $instansi)
                                            <div class="col-md-6 col-12">
                                                <div class="form-check p-2 border rounded bg-white">
                                                    <input class="form-check-input instansi-checkbox" type="checkbox" name="id_instansi[]" value="{{ $instansi->id_instansi }}" id="inst_{{ $instansi->id_instansi }}">
                                                    <label class="form-check-label w-100" for="inst_{{ $instansi->id_instansi }}" style="cursor:pointer">{{ $instansi->nama_instansi }}</label>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-muted">Tidak ada data instansi</p>
                                        @endforelse
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
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0 fw-bold">Filter Laporan Bulanan</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.laporan.export-baru.process') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="bulanan">

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Pilih Tahun</label>
                                    <select name="tahun" id="tahun_bulanan" class="form-select form-select-lg" required>
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
                                    <label class="form-label fw-bold">Pilih Instansi (Bisa Lebih Dari Satu)</label>
                                    <div class="form-check mb-2 border-bottom pb-2">
                                        <input class="form-check-input" type="checkbox" id="checkAllInstansiBulanan">
                                        <label class="form-check-label fw-bold text-primary" for="checkAllInstansiBulanan">
                                            Pilih Semua Instansi
                                        </label>
                                    </div>
                                    <div class="row g-2">
                                        @forelse($instansiList as $instansi)
                                            <div class="col-md-6 col-12">
                                                <div class="form-check p-2 border rounded bg-white">
                                                    <input class="form-check-input instansi-checkbox-bulanan" type="checkbox" name="id_instansi[]" value="{{ $instansi->id_instansi }}" id="inst_bulanan_{{ $instansi->id_instansi }}">
                                                    <label class="form-check-label w-100" for="inst_bulanan_{{ $instansi->id_instansi }}" style="cursor:pointer">{{ $instansi->nama_instansi }}</label>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-muted">Tidak ada data instansi</p>
                                        @endforelse
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
                periodeStart.innerText = 'Juli ' + val; 
                periodeEnd.innerText = 'Juni ' + (parseInt(val) + 1);                 
            } else {
                periodeStart.innerText = '...';
                periodeEnd.innerText = '...';
            }
        });
    }

    // 2. CHECK ALL - INSTANSI TAHUNAN
    const checkAllInstansi = document.getElementById('checkAllInstansi');
    const instansiCheckboxes = document.querySelectorAll('.instansi-checkbox');
    if(checkAllInstansi){
        checkAllInstansi.addEventListener('change', function() {
            instansiCheckboxes.forEach(cb => cb.checked = this.checked);
        });
        // Uncheck "Pilih Semua" jika ada yang di-uncheck
        instansiCheckboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                const allChecked = Array.from(instansiCheckboxes).every(c => c.checked);
                checkAllInstansi.checked = allChecked;
            });
        });
    }

    // 3. CHECK ALL - BULAN BULANAN
    const checkAllBulan = document.getElementById('checkAllBulan');
    const bulanCheckboxes = document.querySelectorAll('.bulan-checkbox');
    if(checkAllBulan){
        checkAllBulan.addEventListener('change', function() {
            bulanCheckboxes.forEach(cb => cb.checked = this.checked);
        });
        bulanCheckboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                const allChecked = Array.from(bulanCheckboxes).every(c => c.checked);
                checkAllBulan.checked = allChecked;
            });
        });
    }

    // 4. CHECK ALL - INSTANSI BULANAN
    const checkAllInstansiBulanan = document.getElementById('checkAllInstansiBulanan');
    const instansiCheckboxesBulanan = document.querySelectorAll('.instansi-checkbox-bulanan');
    if(checkAllInstansiBulanan){
        checkAllInstansiBulanan.addEventListener('change', function() {
            instansiCheckboxesBulanan.forEach(cb => cb.checked = this.checked);
        });
        instansiCheckboxesBulanan.forEach(cb => {
            cb.addEventListener('change', function() {
                const allChecked = Array.from(instansiCheckboxesBulanan).every(c => c.checked);
                checkAllInstansiBulanan.checked = allChecked;
            });
        });
    }
});
</script>
@endsection