@extends('layouts.master')
@section('title', 'Edit Data Kelas')

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
                <form action="{{ route('admin_kelas.update', $kelas->id) }}" method="POST" enctype="multipart/form-data" class="col-md-8 my-5">
                    @csrf
                    @method('PUT')
                    <div class="form-group mb-5">
                        <h5 class="text-center fw-bold ">Edit Kelas</h5>
                    </div>

                    <div class="form-group mb-3 d-flex justify-content-between">
                        <label for="name" class="fw-bold mb-1">Name</label>
                        <div style="width: 70%;">
                            <input type="text" name="name" id="name" class="form-control rounded-3 @error('name') is-invalid @enderror" placeholder="Contoh: A" value="{{ $kelas->name }}">
                        </div>
                    </div>
                    @error('name')
                    <div class="text-danger mt-2">{{ $message }}
                    </div>
                    @enderror

                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn" style="background-color: #10439F; color: white; width: 100px;">Edit</button>
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