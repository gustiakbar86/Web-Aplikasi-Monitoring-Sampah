@extends('superAdmin.layout')

@section('title', 'Lihat Semua Data')

@section('content')
<div class="content-area">
    <div class="bg-primary text-white p-3 rounded mb-4">
        <h3 class="mb-0">Semua Data Dokumen</h3>
    </div>
    
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th width="25%" class="text-center">Judul</th>
                            <th width="15%" class="text-center">File</th>
                            <th width="15%" class="text-center">Tanggal Mulai</th>
                            <th width="15%" class="text-center">Tanggal Akhir</th>
                            <th width="15%" class="text-center">Waktu Upload</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dokumen as $index => $dok)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $dok->judul_dokumen }}</td>
                            <td class="text-center">
                                @if($dok->file_dokumen)
                                    <a href="{{ asset($dok->file_dokumen) }}" target="_blank" class="btn btn-sm btn-light">
                                        <i class="fas fa-file-pdf"></i> Lihat File
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($dok->berlaku)->format('Y-m-d') }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($dok->berakhir)->format('Y-m-d') }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($dok->created_at)->format('H:i:s') }}</td>
                        </tr>
                        @empty
                        <!-- Data contoh jika tidak ada data dari database -->
                        <tr>
                            <td class="text-center">1</td>
                            <td>Rekap Pengelolaan Sampah</td>
                            <td class="text-center">
                                <a href="#" class="btn btn-sm btn-light">
                                    <i class="fas fa-file-pdf"></i> Lihat File
                                </a>
                            </td>
                            <td class="text-center">2025-09-23</td>
                            <td class="text-center">2025-09-23</td>
                            <td class="text-center">16:11:07</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        {{ $dokumen->links() }}
                    </ul>
                </nav>
            </div>
            
            <div class="dropdown d-flex justify-content-center mt-2">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    10 Baris
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item" href="#">10 Baris</a></li>
                    <li><a class="dropdown-item" href="#">25 Baris</a></li>
                    <li><a class="dropdown-item" href="#">50 Baris</a></li>
                    <li><a class="dropdown-item" href="#">100 Baris</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .pagination {
        justify-content: center;
    }
    .bg-primary {
        background-color: #1E3F8C !important;
    }
    .table th {
        vertical-align: middle;
    }
    .btn-light {
        background-color: #f8f9fa;
        border-color: #ddd;
    }
</style>
@endpush
