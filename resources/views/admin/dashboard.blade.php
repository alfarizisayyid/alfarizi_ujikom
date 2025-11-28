@extends('layouts.app')

@section('title', 'Admin Dashboard - Sistem Pendaftaran Polisi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 sidebar">
            <div class="p-3">
                <h5 class="text-white mb-4">
                    <i class="bi bi-shield-check"></i> Admin Panel
                </h5>
                <nav class="nav flex-column">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link active">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.registrations.index') }}" class="nav-link">
                        <i class="bi bi-file-text"></i> Kelola Pendaftar
                    </a>
                    <a href="{{ route('admin.schedules.index') }}" class="nav-link">
                        <i class="bi bi-calendar"></i> Jadwal Seleksi
                    </a>
                    <a href="{{ route('admin.announcements.index') }}" class="nav-link">
                        <i class="bi bi-megaphone"></i> Pengumuman
                    </a>
                    <hr class="text-white">
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="nav-link text-danger">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 main-content">
            <nav class="navbar navbar-light bg-light mb-4">
                <div class="container-fluid">
                    <span class="navbar-text">
                        Admin: <strong>{{ Auth::user()->name }}</strong>
                    </span>
                </div>
            </nav>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <h1 class="h3 mb-4">
                <i class="bi bi-speedometer2"></i> Dashboard Admin
            </h1>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="card-title text-muted">Total Pendaftar</h6>
                                    <h2 class="mb-0">{{ $stats['total_registrations'] }}</h2>
                                </div>
                                <i class="bi bi-people text-primary" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="card-title text-muted">Menunggu Review</h6>
                                    <h2 class="mb-0 text-warning">{{ $stats['pending_review'] }}</h2>
                                </div>
                                <i class="bi bi-hourglass-split text-warning" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="card-title text-muted">Diterima</h6>
                                    <h2 class="mb-0 text-success">{{ $stats['accepted'] }}</h2>
                                </div>
                                <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="card-title text-muted">Ditolak</h6>
                                    <h2 class="mb-0 text-danger">{{ $stats['rejected'] }}</h2>
                                </div>
                                <i class="bi bi-x-circle text-danger" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Recent Registrations -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-file-text"></i> Pendaftaran Terbaru
                        </div>
                        <div class="card-body p-0">
                            @if($recentRegistrations->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>Nama</th>
                                                <th>Email</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentRegistrations as $reg)
                                                <tr>
                                                    <td>
                                                        <i class="bi bi-person"></i> {{ $reg->full_name }}
                                                    </td>
                                                    <td>{{ $reg->user->email }}</td>
                                                    <td>
                                                        <span class="badge badge-status-{{ strtolower(str_replace('_', '-', $reg->status)) }}">
                                                            {{ ucfirst(str_replace('_', ' ', $reg->status)) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('admin.registrations.show', $reg) }}" class="btn btn-sm btn-outline-primary">
                                                            <i class="bi bi-eye"></i> Lihat
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info mb-0">
                                    Belum ada pendaftaran.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Upcoming Schedules -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-calendar"></i> Jadwal Mendatang
                        </div>
                        <div class="card-body">
                            @if($upcomingSchedules->count() > 0)
                                @foreach($upcomingSchedules as $schedule)
                                    <div class="mb-3 pb-3 border-bottom">
                                        <h6 class="mb-1">{{ $schedule->title }}</h6>
                                        <small class="text-muted d-block">
                                            <i class="bi bi-calendar"></i> {{ $schedule->schedule_date->format('d-m-Y') }}
                                        </small>
                                        <small class="text-muted d-block">
                                            <i class="bi bi-clock"></i> {{ $schedule->start_time }}
                                        </small>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted">Belum ada jadwal mendatang.</p>
                            @endif

                            <a href="{{ route('admin.schedules.index') }}" class="btn btn-sm btn-outline-primary w-100">
                                <i class="bi bi-arrow-right"></i> Lihat Semua Jadwal
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="footer">
    <p>&copy; 2025 Sistem Pendaftaran Polisi Indonesia. Semua hak dilindungi.</p>
</div>
@endsection
