@extends('layouts.app')

@section('title', 'Jadwal Seleksi - Sistem Pendaftaran Polisi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 sidebar">
            <div class="p-3">
                <h5 class="text-white mb-4"><i class="bi bi-shield-check"></i> Admin Panel</h5>
                <nav class="nav flex-column">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
                    <a href="{{ route('admin.registrations.index') }}" class="nav-link"><i class="bi bi-file-text"></i> Kelola Pendaftar</a>
                    <a href="{{ route('admin.schedules.index') }}" class="nav-link active"><i class="bi bi-calendar"></i> Jadwal Seleksi</a>
                    <a href="{{ route('admin.announcements.index') }}" class="nav-link"><i class="bi bi-megaphone"></i> Pengumuman</a>
                    <hr class="text-white">
                    <a href="{{ route('admin.schedules.create') }}" class="nav-link text-success"><i class="bi bi-plus-circle"></i> Buat Jadwal Baru</a>
                </nav>
            </div>
        </div>

        <div class="col-md-9 main-content">
            <h1 class="h3 mb-4"><i class="bi bi-calendar"></i> Jadwal Seleksi</h1>

            @if(session('success'))
                <div class="alert alert-success"><i class="bi bi-check-circle"></i> {{ session('success') }}</div>
            @endif

            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Tahap</th>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Kapasitas</th>
                                <th>Peserta</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($schedules as $schedule)
                                <tr>
                                    <td><strong>{{ $schedule->title }}</strong></td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $schedule->stage)) }}</td>
                                    <td>{{ $schedule->schedule_date->format('d-m-Y') }}</td>
                                    <td>{{ $schedule->start_time }} - {{ $schedule->end_time }}</td>
                                    <td>{{ $schedule->capacity ?? '-' }}</td>
                                    <td><span class="badge bg-info">{{ $schedule->participants->count() }}</span></td>
                                    <td><span class="badge badge-status-{{ str_replace('_', '-', $schedule->status) }}">{{ ucfirst($schedule->status) }}</span></td>
                                    <td>
                                        <a href="{{ route('admin.schedules.show', $schedule) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                                        <a href="{{ route('admin.schedules.edit', $schedule) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                                        <a href="{{ route('admin.schedules.destroy', $schedule) }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus?')"><i class="bi bi-trash"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center">Belum ada jadwal.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">{{ $schedules->links() }}</div>
        </div>
    </div>
</div>
<div class="footer"><p>&copy; 2025 Sistem Pendaftaran Polisi Indonesia.</p></div>
@endsection
