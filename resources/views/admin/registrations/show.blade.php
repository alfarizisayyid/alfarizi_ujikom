@extends('layouts.app')

@section('title', 'Detail Pendaftar - Sistem Pendaftaran Polisi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 sidebar">
            <div class="p-3">
                <h5 class="text-white mb-4"><i class="bi bi-shield-check"></i> Admin Panel</h5>
                <nav class="nav flex-column">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
                    <a href="{{ route('admin.registrations.index') }}" class="nav-link active"><i class="bi bi-file-text"></i> Kelola Pendaftar</a>
                    <a href="{{ route('admin.schedules.index') }}" class="nav-link"><i class="bi bi-calendar"></i> Jadwal Seleksi</a>
                    <a href="{{ route('admin.announcements.index') }}" class="nav-link"><i class="bi bi-megaphone"></i> Pengumuman</a>
                    <hr class="text-white">
                    <form method="POST" action="{{ route('logout') }}"><@csrf<button type="submit" class="nav-link text-danger"><i class="bi bi-box-arrow-right"></i> Logout</button></form>
                </nav>
            </div>
        </div>

        <div class="col-md-9 main-content">
            <h1 class="h3 mb-4"><i class="bi bi-file-text"></i> Detail Pendaftar</h1>

            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header"><i class="bi bi-person"></i> Informasi Pribadi</div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <tr><th>Nama Lengkap</th><td>{{ $registration->full_name }}</td></tr>
                                <tr><th>Email</th><td>{{ $registration->user->email }}</td></tr>
                                <tr><th>Telepon</th><td>{{ $registration->phone }}</td></tr>
                                <tr><th>TTL</th><td>{{ $registration->birth_date->format('d-m-Y') }}</td></tr>
                                <tr><th>Jenis Kelamin</th><td>{{ $registration->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
                                <tr><th>Alamat</th><td>{{ $registration->address }}, {{ $registration->city }}, {{ $registration->province }} {{ $registration->postal_code }}</td></tr>
                            </table>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header"><i class="bi bi-card-list"></i> Identitas & Pendidikan</div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <tr><th>No. KTP</th><td>{{ $registration->ktp_number }}</td></tr>
                                <tr><th>KTP Berlaku Hingga</th><td>{{ $registration->ktp_expiry->format('d-m-Y') }}</td></tr>
                                <tr><th>Pendidikan</th><td>{{ $registration->education_level }}</td></tr>
                                <tr><th>Institusi</th><td>{{ $registration->institution }}</td></tr>
                                <tr><th>Tahun Lulus</th><td>{{ $registration->graduation_year }}</td></tr>
                            </table>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header"><i class="bi bi-file-earmark"></i> Dokumen</div>
                        <div class="card-body">
                            @forelse($registration->documents as $doc)
                                <div class="document-item">
                                    <h6>{{ ucfirst(str_replace('_', ' ', $doc->document_type)) }}</h6>
                                    <small class="text-muted">{{ $doc->original_filename }} • {{ number_format($doc->file_size / 1024, 2) }} KB</small><br>
                                    <small>Status: <span class="badge badge-status-{{ $doc->verification_status }}">{{ ucfirst($doc->verification_status) }}</span></small>
                                </div>
                            @empty
                                <div class="alert alert-info mb-0">Tidak ada dokumen.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header"><i class="bi bi-info-circle"></i> Status</div>
                        <div class="card-body">
                            <p><strong>Status:</strong> <span class="badge badge-status-{{ strtolower(str_replace('_', '-', $registration->status)) }}">{{ ucfirst(str_replace('_', ' ', $registration->status)) }}</span></p>
                            <p><strong>Disubmit:</strong> {{ $registration->submitted_at?->format('d-m-Y H:i') ?? '-' }}</p>
                            <p><strong>Diverifikasi:</strong> {{ $registration->reviewed_at?->format('d-m-Y H:i') ?? '-' }}</p>
                            @if($registration->isRejected() && $registration->rejection_reason)
                                <div class="alert alert-danger"><strong>Alasan Penolakan:</strong><p class="mb-0 mt-2">{{ $registration->rejection_reason }}</p></div>
                            @endif
                        </div>
                    </div>

                    @if($registration->isPendingReview() || $registration->status === 'submitted')
                        <a href="{{ route('admin.registrations.verify', $registration) }}" class="btn btn-primary w-100 mt-3">
                            <i class="bi bi-check-circle"></i> Verifikasi
                        </a>
                    @endif

                    <a href="{{ route('admin.registrations.index') }}" class="btn btn-secondary w-100 mt-2">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="footer"><p>&copy; 2025 Sistem Pendaftaran Polisi Indonesia.</p></div>
@endsection
