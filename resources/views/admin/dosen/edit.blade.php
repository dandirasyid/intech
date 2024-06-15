@extends('layouts.master')
@section('title', 'Edit Data Dosen')

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
                <form action="{{ route('admin_dosen.update', $dosen->id) }}" method="POST" enctype="multipart/form-data" class="col-md-8">
                    @csrf
                    @method('PUT')
                    <div class="form-group upload">
                        <img src="{{ $dosen->image ? Storage::url($dosen->image) : asset('images/default_profile.png') }}" id="profileImage" class="rounded-circle mb-2" alt="logo-medsos" width="100px" height="85px">
                        <div class="round rounded-circle bg-light border" style="width: 32px; height: 32px; line-height: 33px; text-align: center; overflow: hidden; position: absolute; bottom: 40px; right: 0;">
                            <input type="file" id="image" name="image" style="position: absolute; transform: scale(2); opacity: 0;" onchange="previewImage(event)">
                            <i class="bi bi-camera-fill" style="color: #10439F;"></i>
                        </div>
                        <p class="text-center fw-bold mt-2">Edit Profile</p>
                    </div>

                    <div class="form-group mb-3 d-flex justify-content-between">
                        <label for="name" class="fw-bold mb-1">Name</label>
                        <div style="width: 70%;">
                            <input type="text" name="name" id="name" class="form-control rounded-3 @error('name') is-invalid @enderror" placeholder="Masukan nama lengkap" value="{{ $dosen->user->name }}" required>
                        </div>
                    </div>
                    @error('name')
                    <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror

                    <div class="form-group d-flex mb-3 justify-content-between">
                        <label for="email" class="fw-bold mb-1">Email</label>
                        <div style="width: 70%;">
                            <input type="email" name="email" id="email" class="form-control rounded-3 @error('email') is-invalid @enderror" placeholder="Masukan email" value="{{ $dosen->user->email }}" required>
                        </div>
                    </div>
                    @error('email')
                    <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror

                    <div class="form-group d-flex mb-3 justify-content-between">
                        <label for="password" class="fw-bold mb-1">Password</label>
                        <div style="width: 70%;">
                            <input type="password" name="password" id="password" class="form-control rounded-3 @error('password') is-invalid @enderror" placeholder="Masukan password">
                        </div>
                    </div>
                    @error('password')
                    <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror

                    <div class="form-group d-flex mb-3 justify-content-between">
                        <label for="no_telepon" class="fw-bold mb-1">Nomor Telepon</label>
                        <div style="width: 70%;">
                            <input type="number" name="no_telepon" id="no_telepon" class="form-control rounded-3 @error('no_telepon') is-invalid @enderror" placeholder="Masukan nomor telepon" value="{{ $dosen->user->no_telepon }}" required>
                        </div>
                    </div>
                    @error('no_telepon')
                    <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror

                    <div class="form-group d-flex mb-3 justify-content-between">
                        <label class="fw-bold mb-1">Kelas</label>
                        <div style="width: 70%;">
                            @foreach($kelas as $kelasItem)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="kelas_id[]" value="{{ $kelasItem->id }}" id="kelas{{ $kelasItem->id }}" {{ in_array($kelasItem->id, $dosen->kelas->pluck('id')->toArray()) ? 'checked' : '' }}>
                                <label class="form-check-label" for="kelas{{ $kelasItem->id }}">{{ $kelasItem->name }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group d-flex mb-3 justify-content-between">
                        <label class="fw-bold mb-1">Matakuliah</label>
                        <div style="width: 70%;">
                            @foreach($matakuliahs as $matakuliah)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="matakuliah_ids[]" value="{{ $matakuliah->id }}" id="matakuliah{{ $matakuliah->id }}" {{ $dosen->dosenMatakuliahs->contains('matakuliah_id', $matakuliah->id) ? 'checked' : '' }}>
                                <label class="form-check-label" for="matakuliah{{ $matakuliah->id }}">{{ $matakuliah->name }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>

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