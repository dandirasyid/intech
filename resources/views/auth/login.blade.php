@extends('layouts.auth')
@section('title', 'Login')

@section('content')
<div style="min-height: 100vh;">
    <div class="container">
        <header>
            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="container-fluid">
                    <img class="navbar-brand" src="{{ asset('images/intech.png') }}" alt="image-intech" width="150px">
                </div>
            </nav>
        </header>
        <div class="col-md-12 d-flex align-items-center justify-content-between" style="margin: 100px 0;">
            <section class="col-md-6">
                <img src="{{ asset('images/login.png') }}" alt="image-register" style="width: 450px; height: 350px;">
            </section>
            <section class="col-md-6 card p-4 border-0 shadow bg-body" style="border-radius: 10px;">
                @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <form action="{{ route('login_user') }}" method="POST">
                    <h3 class="fw-bold mb-2">Login</h3>
                    <p class="mb-4">Selamat datang di E-Learning Teknologi Informasi</p>
                    @csrf
                    <div class="form-group mb-3">
                        <label for="email" class="fw-bold mb-1">Email</label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="Masukan akun e-mail" required>
                    </div>
                    @error('email')
                    <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror

                    <div class="form-group">
                        <div class="input-group">
                            <label for="password" class="fw-bold mb-1">Password</label>
                            <div class="input-group-append">
                                <span class="input-group-text bg-transparent border-0">
                                    <i class="bi bi-eye-slash toggle-password" id="toggleRegisterPassword" style="cursor: pointer; position: absolute; top: 0px;"></i>
                                </span>
                            </div>
                        </div>
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror registerPassword" placeholder="Masukan password" required>
                    </div>
                    @error('password')
                    <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror

                    <div class="d-flex justify-content-center my-4">
                        <button type="submit" class="w-25 btn btn-md text-dark fw-bold text-white" style="background-color: #10439F;">Login</button>
                    </div>

                    <div class="form-group d-flex justify-content-center">
                        <p>Belum punya akun?</p>
                        <a href="{{ route('register') }}" class="ms-1 text-dark fw-bold text-decoration-none">Register</a>
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>
@endsection