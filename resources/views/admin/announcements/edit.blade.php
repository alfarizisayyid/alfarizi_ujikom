@extends('layouts.app')

@section('title', 'Edit Pengumuman - Sistem Pendaftaran Polisi')

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
            <h1 class="h3 mb-4"><i class="bi bi-pencil"></i> Edit Pengumuman</h1>

            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.announcements.update', $announcement) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="title" class="form-label">Judul *</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ $announcement->title }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Isi Pengumuman *</label>
                            <textarea class="form-control" id="content" name="content" rows="6" required>{{ $announcement->content }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="audience" class="form-label">Sasaran</label>
                            <select class="form-select" id="audience" name="audience">
                                <option value="all" {{ $announcement->audience === 'all' ? 'selected' : '' }}>Semua</option>
                                <option value="registered" {{ $announcement->audience === 'registered' ? 'selected' : '' }}>Pendaftar</option>
                                <option value="accepted" {{ $announcement->audience === 'accepted' ? 'selected' : '' }}>Diterima</option>
                                <option value="rejected" {{ $announcement->audience === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" {{ $announcement->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Aktifkan Pengumuman</label>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('admin.announcements.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
