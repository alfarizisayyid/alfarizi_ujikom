@extends('layouts.app')

@section('title', 'Status Pendaftaran - Sistem Pendaftaran Polisi')

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
                <i class="bi bi-bell"></i> Notifikasi & Status Pendaftaran
            </h1>
        </div>
    </div>

    @if($registration)
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-file-text"></i> Status Pendaftaran Terkini
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Status:</h6>
                                <p>
                                    <span class="badge badge-status-{{ strtolower(str_replace('_', '-', $registration->status)) }} fs-6">
                                        {{ ucfirst(str_replace('_', ' ', $registration->status)) }}
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h6>Tanggal Submission:</h6>
                                <p>{{ $registration->submitted_at ? $registration->submitted_at->format('d-m-Y H:i') : 'Belum disubmit' }}</p>
                            </div>
                        </div>

                        @if($registration->isRejected() && $registration->rejection_reason)
                            <div class="alert alert-danger mt-3">
                                <strong><i class="bi bi-exclamation-triangle"></i> Alasan Penolakan:</strong>
                                <p class="mb-0 mt-2">{{ $registration->rejection_reason }}</p>
                            </div>
                        @endif

                        @if($registration->reviewed_at)
                            <div class="mt-3 pt-3 border-top">
                                <small class="text-muted">
                                    Diverifikasi pada: {{ $registration->reviewed_at->format('d-m-Y H:i') }}
                                    @if($registration->reviewer)
                                        oleh {{ $registration->reviewer->name }}
                                    @endif
                                </small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if($registration->documents->count() > 0)
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-file-earmark"></i> Status Verifikasi Dokumen
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Jenis Dokumen</th>
                                            <th>Status</th>
                                            <th>Catatan</th>
                                            <th>Terverifikasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($registration->documents as $doc)
                                            <tr>
                                                <td>
                                                    <i class="bi bi-file"></i>
                                                    {{ ucfirst(str_replace('_', ' ', $doc->document_type)) }}
                                                </td>
                                                <td>
                                                    @if($doc->verification_status === 'pending')
                                                        <span class="badge bg-warning">Menunggu</span>
                                                    @elseif($doc->verification_status === 'verified')
                                                        <span class="badge bg-success">Terverifikasi</span>
                                                    @else
                                                        <span class="badge bg-danger">Ditolak</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($doc->verification_notes)
                                                        <small>{{ $doc->verification_notes }}</small>
                                                    @else
                                                        <small class="text-muted">-</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($doc->verified_at)
                                                        {{ $doc->verified_at->format('d-m-Y') }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($registration->schedules->count() > 0)
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-calendar"></i> Jadwal Seleksi Anda
                        </div>
                        <div class="card-body">
                            @foreach($registration->schedules as $schedule)
                                <div class="card mb-3 border border-info">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="bi bi-calendar-event"></i> {{ $schedule->title }}
                                        </h6>
                                        <p class="card-text mb-2">
                                            <strong>Tahap:</strong> {{ ucfirst(str_replace('_', ' ', $schedule->stage)) }}<br>
                                            <strong>Tanggal:</strong> {{ $schedule->schedule_date->format('d-m-Y') }}<br>
                                            <strong>Waktu:</strong> {{ $schedule->start_time }} - {{ $schedule->end_time }}<br>
                                            @if($schedule->location)
                                                <strong>Lokasi:</strong> {{ $schedule->location }}<br>
                                            @endif
                                        </p>
                                        @if($schedule->description)
                                            <small class="text-muted d-block">{{ $schedule->description }}</small>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @else
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> Anda belum memiliki data pendaftaran. Silakan mulai proses pendaftaran.
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-bell"></i> Notifikasi ({{ $notifications->total() }})
                </div>
                <div class="card-body">
                    @if($notifications->count() > 0)
                        <div class="list-group">
                            @foreach($notifications as $notification)
                                <a href="#" class="list-group-item list-group-item-action {{ $notification->isUnread() ? 'bg-light' : '' }}">
                                    <div class="d-flex w-100 justify-content-between">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">
                                                @if($notification->type === 'announcement')
                                                    <i class="bi bi-megaphone"></i>
                                                @elseif($notification->type === 'schedule')
                                                    <i class="bi bi-calendar"></i>
                                                @elseif($notification->type === 'acceptance')
                                                    <i class="bi bi-check-circle text-success"></i>
                                                @elseif($notification->type === 'rejection')
                                                    <i class="bi bi-x-circle text-danger"></i>
                                                @else
                                                    <i class="bi bi-info-circle"></i>
                                                @endif
                                                {{ $notification->title }}
                                            </h6>
                                            <p class="mb-1">{{ substr($notification->message, 0, 100) }}{{ strlen($notification->message) > 100 ? '...' : '' }}</p>
                                            <small class="text-muted">
                                                {{ $notification->created_at->diffForHumans() }}
                                                @if($notification->isUnread())
                                                    <span class="badge bg-primary ms-2">Baru</span>
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        {{ $notifications->links() }}
                    @else
                        <div class="alert alert-info mb-0">
                            <i class="bi bi-info-circle"></i> Belum ada notifikasi.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('user.dashboard') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>
</div>

<div class="footer">
    <p>&copy; 2025 Sistem Pendaftaran Polisi Indonesia. Semua hak dilindungi.</p>
</div>
@endsection
