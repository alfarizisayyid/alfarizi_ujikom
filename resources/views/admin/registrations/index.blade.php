@extends('layouts.app')

@section('title', 'Kelola Pendaftar - Sistem Pendaftaran Polisi')

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
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.registrations.index') }}" class="nav-link active">
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
                <i class="bi bi-file-text"></i> Kelola Pendaftar
            </h1>

            <!-- Filter Status -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <form method="GET" action="{{ route('admin.registrations.index') }}" class="d-flex gap-2">
                                <select name="status" class="form-select" style="max-width: 250px;" onchange="this.form.submit()">
                                    <option value="">-- Semua Status --</option>
                                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Disubmit</option>
                                    <option value="pending_review" {{ request('status') === 'pending_review' ? 'selected' : '' }}>Menunggu Review</option>
                                    <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>Diterima</option>
                                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Registrations Table -->
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Nama Lengkap</th>
                                <th>Email</th>
                                <th>No. KTP</th>
                                <th>Status</th>
                                <th>Dokumen</th>
                                <th>Disubmit</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($registrations as $reg)
                                <tr>
                                    <td>
                                        <i class="bi bi-person"></i> {{ $reg->full_name }}
                                    </td>
                                    <td>{{ $reg->user->email }}</td>
                                    <td>{{ $reg->ktp_number }}</td>
                                    <td>
                                        <span class="badge badge-status-{{ strtolower(str_replace('_', '-', $reg->status)) }}">
                                            {{ ucfirst(str_replace('_', ' ', $reg->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $reg->documents->count() }}</span>
                                    </td>
                                    <td>
                                        @if($reg->submitted_at)
                                            {{ $reg->submitted_at->format('d-m-Y H:i') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.registrations.show', $reg) }}" class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @if($reg->status === 'pending_review' || $reg->status === 'submitted')
                                                <a href="{{ route('admin.registrations.verify', $reg) }}" class="btn btn-sm btn-outline-warning" title="Verifikasi">
                                                    <i class="bi bi-check-circle"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="bi bi-inbox"></i> Tidak ada data pendaftar.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $registrations->links() }}
            </div>
        </div>
    </div>
</div>

<div class="footer">
    <p>&copy; 2025 Sistem Pendaftaran Polisi Indonesia. Semua hak dilindungi. </p>
</div>
@endsection
