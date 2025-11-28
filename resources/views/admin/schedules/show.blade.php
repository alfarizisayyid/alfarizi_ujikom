@extends('layouts.app')

@section('title', 'Detail Jadwal - Sistem Pendaftaran Polisi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 sidebar">
            <div class="p-3">
                <h5 class="text-white mb-4"><i class="bi bi-shield-check"></i> Admin</h5>
                <nav class="nav flex-column">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
                    <a href="{{ route('admin.schedules.index') }}" class="nav-link active"><i class="bi bi-calendar"></i> Jadwal</a>
                </nav>
            </div>
        </div>

        <div class="col-md-9 main-content">
            <h1 class="h3 mb-4"><i class="bi bi-calendar-event"></i> {{ $schedule->title }}</h1>

            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header"><i class="bi bi-info-circle"></i> Detail Jadwal</div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <tr><th>Judul</th><td>{{ $schedule->title }}</td></tr>
                                <tr><th>Tahap</th><td>{{ ucfirst(str_replace('_', ' ', $schedule->stage)) }}</td></tr>
                                <tr><th>Tanggal</th><td>{{ $schedule->schedule_date->format('d-m-Y') }}</td></tr>
                                <tr><th>Waktu</th><td>{{ $schedule->start_time }} - {{ $schedule->end_time }}</td></tr>
                                <tr><th>Lokasi</th><td>{{ $schedule->location ?? '-' }}</td></tr>
                                <tr><th>Kapasitas</th><td>{{ $schedule->capacity ?? '-' }}</td></tr>
                                <tr><th>Status</th><td><span class="badge badge-status-{{ $schedule->status }}">{{ ucfirst($schedule->status) }}</span></td></tr>
                            </table>
                            @if($schedule->description)
                                <div class="mt-3 pt-3 border-top">
                                    <strong>Deskripsi:</strong>
                                    <p>{{ $schedule->description }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header"><i class="bi bi-people"></i> Peserta ({{ $participants->total() }})</div>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($participants as $p)
                                        <tr>
                                            <td>{{ $p->registration->full_name }}</td>
                                            <td>{{ $p->registration->user->email }}</td>
                                            <td><span class="badge bg-info">{{ ucfirst($p->status) }}</span></td>
                                            <td>{{ $p->notes ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-center">Belum ada peserta.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if($participants->count() > 0)
                            <div class="card-footer">{{ $participants->links() }}</div>
                        @endif
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header"><i class="bi bi-sliders"></i> Aksi</div>
                        <div class="card-body">
                            <a href="{{ route('admin.schedules.edit', $schedule) }}" class="btn btn-warning w-100 mb-2">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary w-100 mb-2">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            @if($schedule->status !== 'cancelled')
                                <form action="{{ route('admin.schedules.destroy', $schedule) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Hapus jadwal?')">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
