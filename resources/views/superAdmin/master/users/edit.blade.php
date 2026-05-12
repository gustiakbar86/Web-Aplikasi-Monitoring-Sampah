@extends('superAdmin.layout')

@section('title', 'Edit User')

@section('content')
<div class="content-area-form">
    
    <div class="card">
        <div class="card-body bg-primary text-white" style="background-color: #1E3F8C !important;">
            <h5 class="mb-0">Edit User</h5>
        </div>
        
        <div class="card-body">
            <form action="{{ route('superadmin.master.users.update', $user->id) }}" method="POST" id="userForm">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah password. Minimal 8 karakter.</small>
                        @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation">
                        @error('password_confirmation')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="id_instansi" class="form-label">Instansi <span class="text-danger">*</span></label>
                        <select class="form-select @error('id_instansi') is-invalid @enderror" id="id_instansi" name="id_instansi" required>
                            <option value="">-- Pilih Instansi --</option>
                            @foreach($instansis as $instansi)
                                <option value="{{ $instansi->id_instansi }}" {{ old('id_instansi', $user->id_instansi) == $instansi->id_instansi ? 'selected' : '' }}>{{ $instansi->nama_instansi }}</option>
                            @endforeach
                        </select>
                        @error('id_instansi')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                
                <div class="d-flex">
                    <a href="{{ route('superadmin.master.users') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary text-white">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    setupFormConfirmation('userForm', 'Apakah Anda yakin ingin menyimpan perubahan data user ini?');
</script>
@endpush

@push('styles')
<style>
    .bg-primary {
        background-color: #1E3F8C !important;
    }
    .btn-primary {
        background-color: #0dcaf0;
        border-color: #0dcaf0;
        color: #fff !important;
    }
    .btn-primary:hover {
        background-color: #0bb5d6;
        border-color: #0bb5d6;
        color: #fff !important;
    }
    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
        color: #fff !important;
    }
</style>
@endpush
