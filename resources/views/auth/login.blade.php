@extends('layouts.app')

@section('title', 'Login - Sistem Pendaftaran Polisi')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg">
                <div class="card-body p-5">
                    <h1 class="text-center mb-4" style="color: #045afaff;">
                        <i class="bi bi-shield-lock"></i> Login
                    </h1>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Error!</strong>
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope"></i> Email
                            </label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required autofocus>
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
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Ingat saya
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </button>
                    </form>

                    <hr>

                    <p class="text-center mb-0">
                        Belum memiliki akun? 
                        <a href="{{ route('register') }}" class="text-decoration-none">Daftar di sini</a>
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
