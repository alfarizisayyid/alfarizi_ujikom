@extends('layouts.app')

@section('title', 'Dashboard - Sistem Pendaftaran Polisi')

@section('content')
<style>
    :root {
        --primary: #1e3c72;
        --secondary: #2a5298;
        --accent: #ff6b6b;
        --success: #51cf66;
        --warning: #ffa94d;
        --danger: #ff6b6b;
    }

    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
    }

    /* Navbar Styling */
    .navbar {
        background: linear-gradient(90deg, var(--primary) 0%, var(--secondary) 100%) !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        border-bottom: 3px solid var(--accent);
    }

    .navbar-brand {
        font-weight: 700;
        font-size: 1.5rem;
        background: linear-gradient(90deg, #fff 0%, #f0f0f0 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        letter-spacing: 1px;
    }

    .nav-link {
        color: rgba(255, 255, 255, 0.9) !important;
        font-weight: 500;
        transition: all 0.3s ease;
        margin: 0 5px;
    }

    .nav-link:hover {
        color: var(--accent) !important;
        transform: translateY(-2px);
    }

    .badge {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    /* Hero Section */
    .hero-section {
        position: relative;
        height: 500px;
        overflow: hidden;
        border-radius: 20px;
        margin-bottom: 40px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
    }

    .hero-background {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url('/images/ajat.jpg');
        background-size: cover;
        background-position: center;
        filter: brightness(0.7);
        z-index: 1;
    }

    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(30, 60, 114, 0.7) 0%, rgba(42, 82, 152, 0.7) 100%);
        z-index: 2;
    }

    .hero-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        z-index: 3;
        width: 100%;
        color: white;
    }

    .hero-content h1 {
        font-size: 3.5rem;
        font-weight: 800;
        margin-bottom: 20px;
        text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5);
        animation: slideDown 0.8s ease-out;
    }

    .hero-content p {
        font-size: 1.3rem;
        margin-bottom: 30px;
        text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.5);
        animation: slideUp 0.8s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Status Cards */
    .status-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
    }

    .status-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        border-color: var(--secondary);
    }

    .status-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(180deg, var(--primary) 0%, var(--secondary) 100%);
    }

    .status-card-icon {
        font-size: 2.5rem;
        margin-bottom: 15px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .status-card-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 10px;
    }

    .status-card-value {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--secondary);
        margin-bottom: 10px;
    }

    .status-card-text {
        color: #666;
        font-size: 0.95rem;
        line-height: 1.6;
    }

    /* Action Buttons */
    .action-btn {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        border: none;
        color: white;
        padding: 12px 25px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(30, 60, 114, 0.3);
    }

    .action-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(30, 60, 114, 0.5);
        color: white;
        text-decoration: none;
    }

    .action-btn-outline {
        background: transparent;
        border: 2px solid var(--primary);
        color: var(--primary);
    }

    .action-btn-outline:hover {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
    }

    /* Quick Menu */
    .quick-menu {
        background: white;
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .quick-menu-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .quick-menu-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 25px 15px;
        background: linear-gradient(135deg, rgba(30, 60, 114, 0.1) 0%, rgba(42, 82, 152, 0.1) 100%);
        border-radius: 12px;
        border: 2px solid transparent;
        transition: all 0.3s ease;
        text-decoration: none;
        color: var(--primary);
        font-weight: 600;
    }

    .quick-menu-btn:hover {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
        transform: translateY(-5px);
        border-color: var(--accent);
    }

    .quick-menu-btn i {
        font-size: 2rem;
        margin-bottom: 10px;
    }

    /* Alert Styling */
    .alert {
        border-radius: 10px;
        border: none;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        font-weight: 500;
    }

    .alert-success {
        background: linear-gradient(135deg, rgba(81, 207, 102, 0.2) 0%, rgba(81, 207, 102, 0.1) 100%);
        color: #2f7d42;
        border-left: 4px solid #51cf66;
    }

    .alert-danger {
        background: linear-gradient(135deg, rgba(255, 107, 107, 0.2) 0%, rgba(255, 107, 107, 0.1) 100%);
        color: #e03131;
        border-left: 4px solid #ff6b6b;
    }

    .alert-info {
        background: linear-gradient(135deg, rgba(42, 82, 152, 0.2) 0%, rgba(42, 82, 152, 0.1) 100%);
        color: #1e3c72;
        border-left: 4px solid #2a5298;
    }

    /* Footer */
    .footer {
        background: linear-gradient(90deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
        text-align: center;
        padding: 30px 20px;
        margin-top: 50px;
        border-top: 3px solid var(--accent);
    }

    /* Badge Styling */
    .badge-status-draft {
        background: linear-gradient(135deg, #ffa94d 0%, #fd7e14 100%);
    }

    .badge-status-submitted {
        background: linear-gradient(135deg, #74c0fc 0%, #4dabf7 100%);
    }

    .badge-status-pending-review {
        background: linear-gradient(135deg, #ffd43b 0%, #fab005 100%);
    }

    .badge-status-accepted {
        background: linear-gradient(135deg, #51cf66 0%, #37b24d 100%);
    }

    .badge-status-rejected {
        background: linear-gradient(135deg, #ff6b6b 0%, #fa5252 100%);
    }

    /* Container */
    .dashboard-container {
        padding: 30px 15px;
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .hero-content h1 {
            font-size: 2.5rem;
        }

        .hero-content p {
            font-size: 1rem;
        }

        .hero-section {
            height: 350px;
        }

        .status-card {
            margin-bottom: 15px;
        }
    }
</style>

<!-- Navbar -->
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

<!-- Main Content -->
<div class="dashboard-container">
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

    @if(!$registration)
        <!-- Hero Section - No Registration -->
        <div class="hero-section">
            <div class="hero-background"></div>
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <h1>👮 Selamat Datang</h1>
                <p>Bergabunglah dengan Kepolisian Negara Republik Indonesia</p>
                <a href="{{ route('user.registration.create') }}" class="action-btn" style="display: inline-block;">
                    <i class="bi bi-pencil-square"></i> Mulai Mendaftar Sekarang
                </a>
            </div>
        </div>

        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> <strong>Perhatian!</strong> Silakan isi formulir pendaftaran untuk melanjutkan proses seleksi.
        </div>
    @else
        <!-- Hero Section - With Registration -->
        <div class="hero-section">
            <div class="hero-background"></div>
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <h1>Selamat Datang, {{ Auth::user()->name }}!</h1>
                <p>📋 Status Pendaftaran Anda: <strong>{{ ucfirst(str_replace('_', ' ', $registration->status)) }}</strong></p>
            </div>
        </div>

        <!-- Status Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="status-card">
                    <div class="status-card-icon">
                        <i class="bi bi-file-text"></i>
                    </div>
                    <div class="status-card-title">Status Pendaftaran</div>
                    <div class="status-card-value">
                        <span class="badge badge-status-{{ strtolower(str_replace('_', '-', $registration->status)) }}">
                            {{ ucfirst(str_replace('_', ' ', $registration->status)) }}
                        </span>
                    </div>
                    <div class="status-card-text">
                        <small>
                            Disubmit: <strong>{{ $registration->submitted_at ? $registration->submitted_at->format('d-m-Y H:i') : 'Belum disubmit' }}</strong>
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="status-card">
                    <div class="status-card-icon">
                        <i class="bi bi-file-earmark"></i>
                    </div>
                    <div class="status-card-title">Dokumen</div>
                    <div class="status-card-value">{{ $registration->documents->count() }}</div>
                    <div class="status-card-text">
                        dokumen telah diunggah
                        <br>
                        <a href="{{ route('user.registration.documents') }}" class="action-btn action-btn-outline" style="display: inline-block; margin-top: 10px; padding: 8px 15px; font-size: 0.9rem;">
                            <i class="bi bi-file-earmark"></i> Kelola
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="status-card">
                    <div class="status-card-icon">
                        <i class="bi bi-calendar2"></i>
                    </div>
                    <div class="status-card-title">Jadwal Seleksi</div>
                    <div class="status-card-value">{{ $registration->schedules->count() }}</div>
                    <div class="status-card-text">
                        @if($registration->schedules->count() > 0)
                            jadwal tersedia untuk Anda
                        @else
                            Belum ada jadwal
                        @endif
                        <br>
                        <a href="{{ route('user.registration.status') }}" class="action-btn action-btn-outline" style="display: inline-block; margin-top: 10px; padding: 8px 15px; font-size: 0.9rem;">
                            <i class="bi bi-calendar"></i> Lihat
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Menu -->
        <div class="quick-menu">
            <div class="quick-menu-title">
                <i class="bi bi-lightning-fill"></i> Menu Cepat
            </div>
            <div class="row g-3">
                <div class="col-md-6 col-lg-3">
                    <a href="{{ route('user.registration.create') }}" class="quick-menu-btn">
                        <i class="bi bi-pencil-lg"></i>
                        <span>Edit Data Pribadi</span>
                    </a>
                </div>
                <div class="col-md-6 col-lg-3">
                    <a href="{{ route('user.registration.documents') }}" class="quick-menu-btn">
                        <i class="bi bi-cloud-upload"></i>
                        <span>Kelola Dokumen</span>
                    </a>
                </div>
                <div class="col-md-6 col-lg-3">
                    <a href="{{ route('user.registration.status') }}" class="quick-menu-btn">
                        <i class="bi bi-bell"></i>
                        <span>Notifikasi & Jadwal</span>
                    </a>
                </div>
                <div class="col-md-6 col-lg-3">
                    <a href="#" class="quick-menu-btn" data-bs-toggle="modal" data-bs-target="#profileModal">
                        <i class="bi bi-person"></i>
                        <span>Profil Saya</span>
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Profile Modal -->
<div class="modal fade" id="profileModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); color: white; border: none;">
                <h5 class="modal-title"><i class="bi bi-person-circle"></i> Data Profil Anda</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-borderless">
                    <tr>
                        <th style="color: var(--primary); font-weight: 700;">Nama</th>
                        <td>{{ Auth::user()->name }}</td>
                    </tr>
                    <tr style="background: rgba(0, 0, 0, 0.02);">
                        <th style="color: var(--primary); font-weight: 700;">Email</th>
                        <td>{{ Auth::user()->email }}</td>
                    </tr>
                    <tr>
                        <th style="color: var(--primary); font-weight: 700;">Role</th>
                        <td><span class="badge" style="background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);">{{ ucfirst(Auth::user()->role) }}</span></td>
                    </tr>
                    <tr style="background: rgba(0, 0, 0, 0.02);">
                        <th style="color: var(--primary); font-weight: 700;">Status</th>
                        <td>
                            @if(Auth::user()->is_active)
                                <span class="badge" style="background: linear-gradient(135deg, #51cf66 0%, #37b24d 100%);">Aktif</span>
                            @else
                                <span class="badge" style="background: linear-gradient(135deg, #ff6b6b 0%, #fa5252 100%);">Non-Aktif</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th style="color: var(--primary); font-weight: 700;">Terakhir Login</th>
                        <td>{{ Auth::user()->last_login_at?->format('d-m-Y H:i') ?? 'Baru pertama kali' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<div class="footer">
    <p>&copy; 2025 Sistem Pendaftaran Polisi Indonesia. Semua hak dilindungi.</p>
</div>
@endsection
