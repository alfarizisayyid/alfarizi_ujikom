@extends('layouts.app')

@section('title', 'Pengumuman - Sistem Pendaftaran Polisi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 sidebar">
            <div class="p-3">
                <h5 class="text-white mb-4"><i class="bi bi-shield-check"></i> Admin</h5>
                <nav class="nav flex-column">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
                    <a href="{{ route('admin.registrations.index') }}" class="nav-link"><i class="bi bi-file-text"></i> Pendaftar</a>
                    <a href="{{ route('admin.schedules.index') }}" class="nav-link"><i class="bi bi-calendar"></i> Jadwal</a>
                    <a href="{{ route('admin.announcements.index') }}" class="nav-link active"><i class="bi bi-megaphone"></i> Pengumuman</a>
                    <hr class="text-white">
                    <a href="{{ route('admin.announcements.create') }}" class="nav-link text-success"><i class="bi bi-plus-circle"></i> Buat Pengumuman</a>
                </nav>
            </div>
        </div>

        <div class="col-md-9 main-content">
            <h1 class="h3 mb-4"><i class="bi bi-megaphone"></i> Pengumuman</h1>

            @if(session('success'))
                <div class="alert alert-success"><i class="bi bi-check-circle"></i> {{ session('success') }}</div>
            @endif

            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Sasaran</th>
                                <th>Status</th>
                                <th>Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($announcements as $ann)
                                <tr>
                                    <td><strong>{{ $ann->title }}</strong></td>
                                    <td>{{ $ann->getAudienceLabel() }}</td>
                                    <td>
                                        @if($ann->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>{{ $ann->created_at->format('d-m-Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('admin.announcements.edit', $ann) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                                        <a href="{{ route('admin.announcements.destroy', $ann) }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus?')"><i class="bi bi-trash"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center">Belum ada pengumuman.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">{{ $announcements->links() }}</div>
        </div>
    </div>
</div>
@endsection
