@extends('layouts.master')
@section('title', 'Tambah Data Mahasiswa')

@section('content')
@include('sidebar.index')
<div style="min-height: 100vh;">
    <div class="container sticky-top">
        <header>
            <nav class="navbar navbar-expand-lg navbar-light bg-body mt-2 border-0 rounded shadow ">
                <div class="container-fluid">
                    <div>
                        <img class="navbar-brand mt-2" src="{{ asset('images/intech.png') }}" alt="image-intech" width="150px">
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="mx-2 mt-3">
                            <p class="fw-bold">{{ $user->name }}</p>
                        </div>
                        <div>
                            <img src="{{ asset('images/default_profile.png') }}" alt="user" class="rounded-circle" width="50px">
                        </div>
                    </div>
                </div>
            </nav>
        </header>
    </div>
    <div class="container">
        <div class="bg-body my-4 p-3 border-0 rounded shadow">
            <div class="d-flex justify-content-center align-items-center mb-1 col-md-12">
                @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <form action="{{ route('admin_mahasiswa.store') }}" method="POST" enctype="multipart/form-data" class="col-md-8">
                    @csrf
                    <div class="form-group upload">
                        <img src="{{ asset('images/edit-profile.png') }}" id="profileImage" class="rounded-circle mb-2" alt="logo-medsos" width="100px" height="85px">
                        <div class="round rounded-circle bg-light border" style="width: 32px; height: 32px; line-height: 33px; text-align: center; overflow: hidden; position: absolute; bottom: 40px; right: 0;">
                            <input type="file" id="image" name="image" style="position: absolute; transform: scale(2); opacity: 0;" onchange="previewImage(event)">
                            <i class="bi bi-camera-fill" style="color: #10439F;"></i>
                        </div>
                        <p class="text-center fw-bold mt-2">Profile</p>
                    </div>

                    <div class="form-group mb-3 d-flex justify-content-between">
                        <label for="name" class="fw-bold mb-1">Name</label>
                        <div style="width: 70%;">
                            <input type="text" name="name" id="name" class="form-control rounded-3 @error('name') is-invalid @enderror" placeholder="Masukan nama lengkap" required>
                        </div>
                    </div>
                    @error('name')
                    <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror

                    <div class="form-group d-flex mb-3 justify-content-between">
                        <label for="email" class="fw-bold mb-1">Email</label>
                        <div style="width: 70%;">
                            <input type="email" name="email" id="email" class="form-control rounded-3 @error('email') is-invalid @enderror" placeholder="Masukan email" required>
                        </div>
                    </div>
                    @error('email')
                    <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror

                    <div class="form-group d-flex mb-3 justify-content-between">
                        <label for="password" class="fw-bold mb-1">Password</label>
                        <div style="width: 70%;">
                            <input type="password" name="password" id="password" class="form-control rounded-3 @error('password') is-invalid @enderror" placeholder="Masukan password" required>
                        </div>
                    </div>
                    @error('password')
                    <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror

                    <div class="form-group d-flex mb-3 justify-content-between">
                        <label for="password_confirmation" class="fw-bold mb-1">Konfirmasi Password</label>
                        <div style="width: 70%;">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control rounded-3 @error('password_confirmation') is-invalid @enderror" placeholder="Masukan confirm password" required>
                        </div>
                    </div>
                    @error('password_confirmation')
                    <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror

                    <div class="form-group d-flex mb-3 justify-content-between">
                        <label for="no_telepon" class="fw-bold mb-1">Nomor Telepon</label>
                        <div style="width: 70%;">
                            <input type="number" name="no_telepon" id="no_telepon" class="form-control rounded-3 @error('no_telepon') is-invalid @enderror" placeholder="Masukan nomor telepon" required>
                        </div>
                    </div>
                    @error('no_telepon')
                    <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror

                    <div class="form-group mb-3 d-flex justify-content-between">
                        <label for="nim" class="fw-bold mb-1">Nim</label>
                        <div style="width: 70%;">
                            <input type="text" name="nim" id="nim" class="form-control @error('nim') is-invalid @enderror" placeholder="Masukan nim" required>
                        </div>
                    </div>
                    @error('nim')
                    <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror

                    <div class="form-group d-flex mb-3 justify-content-between">
                        <label class="fw-bold mb-1">Kelas</label>
                        <div style="width: 70%;">
                            <select class="form-select rounded-3" name="kelas_id" id="kelas_id">
                                <option selected disabled>Pilih Kelas</option>
                                @foreach($kelas as $kelasItem)
                                <option value="{{ $kelasItem->id }}">{{ $kelasItem->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group d-flex mb-3 justify-content-between">
                        <label class="fw-bold mb-1">Matakuliah</label>
                        <div style="width: 70%;">
                            @foreach($matakuliahs as $matakuliah)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="matakuliah_ids[]" value="{{ $matakuliah->id }}" id="matakuliah{{ $matakuliah->id }}">
                                <label class="form-check-label" for="matakuliah{{ $matakuliah->id }}">{{ $matakuliah->name }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn" style="background-color: #10439F; color: white; width: 100px;">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('profileImage');
            output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection