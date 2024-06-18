@extends('layouts.auth')
@section('title', 'Register Admin')

@section('content')
<div style="min-height: 100vh;">
    <div class="container">
        <header>
            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="container-fluid">
                    <img class="navbar-brand mt-2" src="{{ asset('images/intech.png') }}" alt="image-intech" width="150px">
                </div>
            </nav>
        </header>
        <div class="col-md-12 d-flex align-items-center justify-content-between my-4">
            <section class="col-md-6">
                <img src="{{ asset('images/register-admin.png') }}" alt="image-register" style="width: 450px; height: 350px;">
            </section>
            <section class="col-md-6 card p-4 border-0 shadow bg-body" style="border-radius: 10px;">
                @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <form action="{{ route('register_admin.post') }}" method="POST">
                    <h3 class="fw-bold mb-2">Register Admin</h3>
                    <p class="mb-4">Silahkan registrasi untuk admin</p>
                    @csrf
                    <div class="form-group mb-3">
                        <label for="username" class="fw-bold mb-1">Name</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Masukan nama lengkap" required>
                    </div>
                    @error('name')
                    <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror

                    <div class="form-group mb-3">
                        <label for="email" class="fw-bold mb-1">Email</label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="Masukan akun e-mail" required>
                    </div>
                    @error('email')
                    <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror

                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="form-group col-md-6">
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

                        <div class="form-group col-md-6">
                            <div class="input-group">
                                <label for="password_confirmation" class="fw-bold">Konfirmasi Password</label>
                                <div class="input-group-append">
                                    <span class="input-group-text bg-transparent border-0">
                                        <i class="bi bi-eye-slash toggle-password" id="toggleConfirmPassword" style="cursor: pointer; position: absolute; top: 0px;"></i>
                                    </span>
                                </div>
                            </div>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Masukan confirm password" required>
                        </div>
                        @error('password_confirmation')
                        <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="no_telepon" class="fw-bold mb-1">Nomor Telepon</label>
                        <input type="number" name="no_telepon" id="no_telepon" class="form-control @error('no_telepon') is-invalid @enderror" placeholder="Masukan nomor telepon" required>
                    </div>
                    @error('no_telepon')
                    <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror

                    <div class="d-flex justify-content-center my-4">
                        <button type="submit" class="w-25 btn btn-md text-dark fw-bold text-white" style="background-color: #10439F;">Register</button>
                    </div>

                    <div class="form-group d-flex justify-content-center">
                        <p>Sudah punya akun?</p>
                        <a href="{{ route('login') }}" class="ms-1 text-dark fw-bold text-decoration-none">Login</a>
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>
@endsection