@extends('layouts.app')

@section('title', 'Dashboard - Sistem Pendaftaran Polisi')

@section('content')
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('user.dashboard') }}">
            <i class="bi bi-shield-check"></i> Polisi Pendaftaran
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('user.registration.status') }}">
                        <i class="bi bi-bell"></i> Notifikasi
                        @if($unreadNotifications > 0)
                            <span class="badge bg-danger">{{ $unreadNotifications }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userMenu" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#profileModal">
                                <i class="bi bi-gear"></i> Profil
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid mt-4 mb-5">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3">
                <i class="bi bi-house-fill"></i> Selamat Datang, {{ Auth::user()->name }}!
            </h1>
        </div>
    </div>

    @if(!$registration)
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> Silakan isi formulir pendaftaran untuk melanjutkan proses.
        </div>
        <a href="{{ route('user.registration.create') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-pencil-square"></i> Mulai Mendaftar
        </a>
    @else
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-text"></i> Status Pendaftaran</h5>
                        <p class="card-text">
                            <span class="badge badge-status-{{ strtolower(str_replace('_', '-', $registration->status)) }}">
                                {{ ucfirst(str_replace('_', ' ', $registration->status)) }}
                            </span>
                        </p>
                        <small class="text-muted">
                            Disubmit: {{ $registration->submitted_at ? $registration->submitted_at->format('d-m-Y H:i') : 'Belum disubmit' }}
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-file-earmark"></i> Dokumen</h5>
                        <p class="card-text">
                            {{ $registration->documents->count() }} dokumen diunggah
                        </p>
                        <a href="{{ route('user.registration.documents') }}" class="btn btn-sm btn-outline-primary">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-calendar"></i> Jadwal Seleksi</h5>
                        @if($registration->schedules->count() > 0)
                            <p class="card-text">
                                {{ $registration->schedules->count() }} jadwal
                            </p>
                        @else
                            <p class="card-text text-muted">Belum ada jadwal</p>
                        @endif
                        <a href="{{ route('user.registration.status') }}" class="btn btn-sm btn-outline-primary">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-list"></i> Menu Cepat
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            <div class="col-md-6 col-lg-3">
                                <a href="{{ route('user.registration.create') }}" class="btn btn-outline-primary w-100">
                                    <i class="bi bi-pencil"></i> Edit Data Pribadi
                                </a>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <a href="{{ route('user.registration.documents') }}" class="btn btn-outline-primary w-100">
                                    <i class="bi bi-cloud-upload"></i> Kelola Dokumen
                                </a>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <a href="{{ route('user.registration.status') }}" class="btn btn-outline-primary w-100">
                                    <i class="bi bi-bell"></i> Notifikasi
                                </a>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <a href="#" class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#profileModal">
                                    <i class="bi bi-person"></i> Profil Saya
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Profile Modal -->
<div class="modal fade" id="profileModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-person"></i> Data Profil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <tr>
                        <th>Nama</th>
                        <td>{{ Auth::user()->name }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ Auth::user()->email }}</td>
                    </tr>
                    <tr>
                        <th>Role</th>
                        <td><span class="badge bg-info">{{ ucfirst(Auth::user()->role) }}</span></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if(Auth::user()->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Non-Aktif</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Terakhir Login</th>
                        <td>{{ Auth::user()->last_login_at?->format('d-m-Y H:i') ?? 'Baru pertama kali' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="footer">
    <p>&copy; 2025 Sistem Pendaftaran Polisi Indonesia. Semua hak dilindungi.</p>
</div>
@endsection
