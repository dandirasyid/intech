@extends('layouts.master')
@section('title', 'Daftar Matakuliah Mahasiswa')

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
                    <a href="{{ route('profile') }}" class="text-decoration-none text-dark">
                        <div class="d-flex align-items-center">
                            <div class="mx-2 mt-3">
                                <p class="fw-bold">{{ $user->name }}</p>
                            </div>
                            <div>
                                <img src="{{ asset('images/default_profile.png') }}" alt="user" class="rounded-circle" width="50px">
                            </div>
                        </div>
                    </a>
                </div>
            </nav>
        </header>
    </div>
    <div class="container">
        <div class="bg-body my-4 p-3 border-0 rounded shadow">
            <div class="col-md-12 d-flex justify-content-center gap-3 flex-wrap my-4">
                @foreach($matakuliahs as $matakuliah)
                <div class="card col-md-3 rounded" style="border-color: #10439F;">
                    <a href="{{ route('mahasiswa_matakuliah.detail', ['matakuliah_id' => $matakuliah->id]) }}" class="text-decoration-none text-dark">
                        <img src="{{ $matakuliah->image ? Storage::url($matakuliah->image) : asset('images/default-matkul.png') }}" class="card-img-top" alt="card" height="200px">
                        <div class="card-body my-3">
                            <h5 class="card-title text-center">{{ $matakuliah->name }}</h5>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection