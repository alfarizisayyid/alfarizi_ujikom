@extends('layouts.app')

@section('title', 'Formulir Pendaftaran - Sistem Pendaftaran Polisi')

@section('content')
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('user.dashboard') }}">
            <i class="bi bi-shield-check"></i> Polisi Pendaftaran
        </a>
        <div class="d-flex ms-auto">
            <span class="text-white">{{ Auth::user()->name }}</span>
        </div>
    </div>
</nav>

<div class="container mt-4 mb-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3">
                <i class="bi bi-pencil-square"></i> Formulir Pendaftaran
            </h1>
            <small class="text-muted">Tahap 1: Data Pribadi</small>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <strong><i class="bi bi-exclamation-circle"></i> Terjadi Kesalahan!</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-3">
            <div class="list-group">
                <a href="{{ route('user.registration.create') }}" class="list-group-item list-group-item-action active">
                    <i class="bi bi-1-circle"></i> Data Pribadi
                </a>
                <a href="{{ route('user.registration.documents') }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-2-circle"></i> Dokumen
                </a>
            </div>
        </div>

        <div class="col-md-9">
            <form method="POST" action="{{ route('user.registration.store') }}">
                @csrf

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="bi bi-person"></i> Data Pribadi
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="full_name" class="form-label">Nama Lengkap *</label>
                                <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                                       id="full_name" name="full_name" value="{{ old('full_name', $registration?->full_name) }}" required>
                                @error('full_name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="birth_date" class="form-label">Tanggal Lahir *</label>
                                <input type="date" class="form-control @error('birth_date') is-invalid @enderror"
                                       id="birth_date" name="birth_date" value="{{ old('birth_date', $registration?->birth_date) }}" required>
                                @error('birth_date')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">Jenis Kelamin *</label>
                                <select class="form-select @error('gender') is-invalid @enderror"
                                        id="gender" name="gender" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="male" {{ old('gender', $registration?->gender) === 'male' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="female" {{ old('gender', $registration?->gender) === 'female' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('gender')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Nomor Telepon *</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                       id="phone" name="phone" value="{{ old('phone', $registration?->phone) }}" required>
                                @error('phone')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email', $registration?->email) }}" required>
                                @error('email')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="bi bi-geo-alt"></i> Data Alamat
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat Lengkap *</label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                      id="address" name="address" rows="3" required>{{ old('address', $registration?->address) }}</textarea>
                            @error('address')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">Kota/Kabupaten *</label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror"
                                       id="city" name="city" value="{{ old('city', $registration?->city) }}" required>
                                @error('city')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="province" class="form-label">Provinsi *</label>
                                <input type="text" class="form-control @error('province') is-invalid @enderror"
                                       id="province" name="province" value="{{ old('province', $registration?->province) }}" required>
                                @error('province')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="postal_code" class="form-label">Kode Pos *</label>
                            <input type="text" class="form-control @error('postal_code') is-invalid @enderror"
                                   id="postal_code" name="postal_code" value="{{ old('postal_code', $registration?->postal_code) }}" required>
                            @error('postal_code')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="bi bi-card-list"></i> Data Identitas
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="ktp_number" class="form-label">Nomor KTP *</label>
                                <input type="text" class="form-control @error('ktp_number') is-invalid @enderror"
                                       id="ktp_number" name="ktp_number" value="{{ old('ktp_number', $registration?->ktp_number) }}" required>
                                @error('ktp_number')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="ktp_expiry" class="form-label">Berlaku Hingga *</label>
                                <input type="date" class="form-control @error('ktp_expiry') is-invalid @enderror"
                                       id="ktp_expiry" name="ktp_expiry" value="{{ old('ktp_expiry', $registration?->ktp_expiry) }}" required>
                                @error('ktp_expiry')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="bi bi-book"></i> Data Pendidikan
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="education_level" class="form-label">Tingkat Pendidikan *</label>
                                <select class="form-select @error('education_level') is-invalid @enderror"
                                        id="education_level" name="education_level" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="SMA" {{ old('education_level', $registration?->education_level) === 'SMA' ? 'selected' : '' }}>SMA/Sederajat</option>
                                    <option value="D1" {{ old('education_level', $registration?->education_level) === 'D1' ? 'selected' : '' }}>D1</option>
                                    <option value="D2" {{ old('education_level', $registration?->education_level) === 'D2' ? 'selected' : '' }}>D2</option>
                                    <option value="D3" {{ old('education_level', $registration?->education_level) === 'D3' ? 'selected' : '' }}>D3</option>
                                    <option value="S1" {{ old('education_level', $registration?->education_level) === 'S1' ? 'selected' : '' }}>S1</option>
                                    <option value="S2" {{ old('education_level', $registration?->education_level) === 'S2' ? 'selected' : '' }}>S2</option>
                                </select>
                                @error('education_level')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="graduation_year" class="form-label">Tahun Lulus *</label>
                                <input type="number" class="form-control @error('graduation_year') is-invalid @enderror"
                                       id="graduation_year" name="graduation_year" min="1990" max="{{ now()->year }}"
                                       value="{{ old('graduation_year', $registration?->graduation_year) }}" required>
                                @error('graduation_year')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="institution" class="form-label">Nama Institusi Pendidikan *</label>
                            <input type="text" class="form-control @error('institution') is-invalid @enderror"
                                   id="institution" name="institution" value="{{ old('institution', $registration?->institution) }}" required>
                            @error('institution')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="{{ route('user.dashboard') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Simpan & Lanjutkan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="footer">
    <p>&copy; 2025 Sistem Pendaftaran Polisi Indonesia. Semua hak dilindungi.</p>
</div>
@endsection
