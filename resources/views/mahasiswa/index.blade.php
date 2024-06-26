@extends('layouts.master')
@section('title', 'Dashboard Mahasiswa')

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
            <div class="d-flex align-items-center justify-content-between" style="margin: 0 100px;">
                <div>
                    <h4 class="fw-bold">Selamat Datang {{ $user->name }}</h4>
                    <p>Dashboard mahasiswa untuk kegiatan perkuliahan</p>
                </div>
                <div>
                    <img src="{{ asset('images/dosen-dashboard.png') }}" alt="image-admin" width="400px">
                </div>
            </div>

            <div class="col-md-12 d-flex justify-content-center gap-3 my-4">
                <div class="card col-md-2 rounded" style="border-color: #10439F;">
                    <img src="{{ asset('images/people.png') }}" class="card-img-top" alt="card" height="150px">
                    <div class="card-body">
                        <h5 class="card-title text-center">Total Dosen</h5>
                        <h2 class="card-title text-center" style="color:  #10439F;">{{ $totalDosen }}</h2>
                    </div>
                </div>
                <div class="card col-md-2 rounded" style="border-color: #10439F;">
                    <img src="{{ asset('images/class.png') }}" class="card-img-top" alt="card" height="150px">
                    <div class="card-body">
                        <h5 class="card-title text-center">Total Kelas</h5>
                        <h2 class="card-title text-center" style="color:  #10439F;">{{ $totalKelas }}</h2>
                    </div>
                </div>
                <div class="card col-md-2 rounded" style="border-color: #10439F;">
                    <img src="{{ asset('images/matakuliah.png') }}" class="card-img-top" alt="card" height="150px">
                    <div class="card-body">
                        <h5 class="card-title text-center">Total Matakuliah</h5>
                        <h2 class="card-title text-center" style="color:  #10439F;">{{ $totalMatakuliah }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection