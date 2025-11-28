@extends('layouts.app')

@section('title', 'Buat Pengumuman - Sistem Pendaftaran Polisi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 sidebar">
            <div class="p-3">
                <h5 class="text-white mb-4"><i class="bi bi-shield-check"></i> Admin</h5>
                <nav class="nav flex-column">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
                    <a href="{{ route('admin.announcements.index') }}" class="nav-link active"><i class="bi bi-megaphone"></i> Pengumuman</a>
                </nav>
            </div>
        </div>

        <div class="col-md-9 main-content">
            <h1 class="h3 mb-4"><i class="bi bi-plus-circle"></i> Buat Pengumuman Baru</h1>

            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.announcements.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="title" class="form-label">Judul *</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" required>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Isi Pengumuman *</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="6" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="audience" class="form-label">Sasaran Pengumuman *</label>
                            <select class="form-select @error('audience') is-invalid @enderror" id="audience" name="audience" required>
                                <option value="">-- Pilih --</option>
                                <option value="all">Semua Pengguna</option>
                                <option value="registered">Pendaftar Terdaftar</option>
                                <option value="accepted">Pendaftar Diterima</option>
                                <option value="rejected">Pendaftar Ditolak</option>
                                <option value="schedule_participants">Peserta Jadwal Seleksi</option>
                            </select>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="publish_immediately" name="publish_immediately" checked>
                            <label class="form-check-label" for="publish_immediately">
                                Publikasikan Segera
                            </label>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('admin.announcements.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i> Buat Pengumuman</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
