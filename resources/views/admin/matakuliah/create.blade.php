@extends('layouts.master')
@section('title', 'Tambah Data Matakuliah')

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
                <form action="{{ route('admin_matakuliah.store') }}" method="POST" enctype="multipart/form-data" class="col-md-8">
                    @csrf
                    <div class="form-group upload">
                        <img src="{{ asset('images/edit-profile.png') }}" id="profileImage" class="rounded-circle mb-2" alt="logo-medsos" width="100px" height="85px">
                        <div class="round rounded-circle bg-light border" style="width: 32px; height: 32px; line-height: 33px; text-align: center; overflow: hidden; position: absolute; bottom: 40px; right: 0;">
                            <input type="file" id="image" name="image" style="position: absolute; transform: scale(2); opacity: 0;" onchange="previewImage(event)">
                            <i class="bi bi-camera-fill" style="color: #10439F;"></i>
                        </div>
                        <p class="text-center fw-bold mt-2">Gambar</p>
                    </div>

                    <div class="form-group mb-3 d-flex justify-content-between">
                        <label for="name" class="fw-bold mb-1">Name</label>
                        <div style="width: 70%;">
                            <input type="text" name="name" id="name" class="form-control rounded-3 @error('name') is-invalid @enderror" placeholder="Masukan nama matakuliah" required>
                        </div>
                    </div>
                    @error('name')
                    <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror

                    <div class="form-group d-flex mb-3 justify-content-between">
                        <label for="deskripsi" class="fw-bold mb-1">Deskripsi</label>
                        <div style="width: 70%;">
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" name="deskripsi" id="deskripsi" rows="3" placeholder="Masukan deskripsi"></textarea>
                        </div>
                    </div>
                    @error('deskripsi')
                    <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror

                    <div class="form-group d-flex mb-3 justify-content-between">
                        <label for="semester" class="fw-bold mb-1">Semester</label>
                        <div style="width: 70%;">
                            <input type="text" name="semester" id="semester" class="form-control rounded-3 @error('semester') is-invalid @enderror" placeholder="Contoh: 1" required>
                        </div>
                    </div>
                    @error('semester')
                    <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror

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