@extends('layouts.master')
@section('title', 'Detail Materi')

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
            <div class="mb-3">
                <a href="{{ route('mahasiswa_matakuliah.detail', ['matakuliah_id' => $matakuliah->id]) }}" class="btn btn-md text-white" style="background-color: #10439F;">Back</a>
            </div>
            <h2 class="text-center">{{ $materi->judul }}</h2>
            <div class="col-md-12 d-flex justify-content-center gap-3 flex-wrap my-4">
                <div class="card col-md-8 rounded" style="border-color: #10439F;">
                    @if ($materi->image && Storage::disk('public')->exists($materi->image))
                    <img src="{{ Storage::url($materi->image) }}" class="card-img-top" alt="materi-image" style="width: 50%; margin: 0 auto;">
                    @endif
                    @if ($materi->link_video)
                    <div class="mt-4 p-3">
                        <iframe width="100%" height="500" src="{{ $materi->link_video }}" frameborder="0" allowfullscreen></iframe>
                    </div>
                    @endif
                    @if ($materi->file_path && strpos($materi->file_path, '.pdf') !== false)
                    <embed src="{{ Storage::url($materi->file_path) }}" type="application/pdf" width="100%" height="500px" class="mt-4 p-3" />
                    @endif
                    <div class="card-body my-3">
                        <h5 class="card-title">Deskripsi</h5>
                        <p class="card-text">{{ $materi->deskripsi }}</p>
                        <h5 class="card-title">File</h5>
                        @if ($materi->file_path)
                        <a href="{{ route('mahasiswa_download_materi', $materi->id) }}" class="btn btn-primary">Download File</a>
                        @else
                        <p class="text-muted">No file available</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection