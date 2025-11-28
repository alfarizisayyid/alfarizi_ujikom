@extends('layouts.app')

@section('title', 'Edit Jadwal - Sistem Pendaftaran Polisi')

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
            <h1 class="h3 mb-4"><i class="bi bi-pencil"></i> Edit Jadwal Seleksi</h1>

            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.schedules.update', $schedule) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="title" class="form-label">Judul *</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ $schedule->title }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ $schedule->description }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="stage" class="form-label">Tahap *</label>
                                <select class="form-select" id="stage" name="stage" required>
                                    <option value="interview" {{ $schedule->stage === 'interview' ? 'selected' : '' }}>Wawancara</option>
                                    <option value="physical_test" {{ $schedule->stage === 'physical_test' ? 'selected' : '' }}>Tes Fisik</option>
                                    <option value="psychological_test" {{ $schedule->stage === 'psychological_test' ? 'selected' : '' }}>Tes Psikologi</option>
                                    <option value="medical_test" {{ $schedule->stage === 'medical_test' ? 'selected' : '' }}>Tes Medis</option>
                                    <option value="final_selection" {{ $schedule->stage === 'final_selection' ? 'selected' : '' }}>Seleksi Final</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="planned" {{ $schedule->status === 'planned' ? 'selected' : '' }}>Direncanakan</option>
                                    <option value="ongoing" {{ $schedule->status === 'ongoing' ? 'selected' : '' }}>Sedang Berlangsung</option>
                                    <option value="completed" {{ $schedule->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                                    <option value="cancelled" {{ $schedule->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="schedule_date" class="form-label">Tanggal *</label>
                                <input type="date" class="form-control" id="schedule_date" name="schedule_date" value="{{ $schedule->schedule_date->format('Y-m-d') }}" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="start_time" class="form-label">Jam Mulai *</label>
                                <input type="time" class="form-control" id="start_time" name="start_time" value="{{ $schedule->start_time }}" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="end_time" class="form-label">Jam Selesai *</label>
                                <input type="time" class="form-control" id="end_time" name="end_time" value="{{ $schedule->end_time }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="location" class="form-label">Lokasi</label>
                                <input type="text" class="form-control" id="location" name="location" value="{{ $schedule->location }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="capacity" class="form-label">Kapasitas</label>
                                <input type="number" class="form-control" id="capacity" name="capacity" value="{{ $schedule->capacity }}" min="1">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Catatan</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2">{{ $schedule->notes }}</textarea>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
