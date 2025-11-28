@extends('layouts.app')

@section('title', 'Buat Jadwal - Sistem Pendaftaran Polisi')

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
            <h1 class="h3 mb-4"><i class="bi bi-plus-circle"></i> Buat Jadwal Seleksi Baru</h1>

            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.schedules.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="title" class="form-label">Judul Jadwal *</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="stage" class="form-label">Tahap *</label>
                                <select class="form-select @error('stage') is-invalid @enderror" id="stage" name="stage" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="interview">Wawancara</option>
                                    <option value="physical_test">Tes Fisik</option>
                                    <option value="psychological_test">Tes Psikologi</option>
                                    <option value="medical_test">Tes Medis</option>
                                    <option value="final_selection">Seleksi Final</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="schedule_date" class="form-label">Tanggal *</label>
                                <input type="date" class="form-control @error('schedule_date') is-invalid @enderror" id="schedule_date" name="schedule_date" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_time" class="form-label">Jam Mulai *</label>
                                <input type="time" class="form-control @error('start_time') is-invalid @enderror" id="start_time" name="start_time" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="end_time" class="form-label">Jam Selesai *</label>
                                <input type="time" class="form-control @error('end_time') is-invalid @enderror" id="end_time" name="end_time" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="location" class="form-label">Lokasi</label>
                                <input type="text" class="form-control" id="location" name="location">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="capacity" class="form-label">Kapasitas</label>
                                <input type="number" class="form-control" id="capacity" name="capacity" min="1">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Catatan</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Buat Jadwal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
