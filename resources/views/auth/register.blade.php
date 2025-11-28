@extends('layouts.app')

@section('title', 'Daftar - Sistem Pendaftaran Polisi')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg">
                <div class="card-body p-5">
                    <h1 class="text-center mb-4" style="color: #003366;">
                        <i class="bi bi-person-plus"></i> Daftar Akun
                    </h1>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Error!</strong>
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">
                                <i class="bi bi-person"></i> Nama Lengkap
                            </label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required autofocus>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope"></i> Email
                            </label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="bi bi-key"></i> Password
                            </label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            <small class="form-text text-muted">
                                Minimal 8 karakter, kombinasi huruf besar, kecil, angka, dan simbol
                            </small>
                            @error('password')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">
                                <i class="bi bi-key"></i> Konfirmasi Password
                            </label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="bi bi-check-circle"></i> Daftar
                        </button>
                    </form>

                    <hr>

                    <p class="text-center mb-0">
                        Sudah memiliki akun? 
                        <a href="{{ route('login') }}" class="text-decoration-none">Login di sini</a>
                    </p>
                </div>
            </div>

            <div class="mt-4 text-center text-muted small">
                <p><i class="bi bi-info-circle"></i> Sistem Pendaftaran Polisi Indonesia</p>
            </div>
        </div>
    </div>
</div>
@endsection
