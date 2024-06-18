@extends('layouts.master')
@section('title', 'Detail Tugas')

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
            <div class="mb-3">
                <a href="{{ route('mahasiswa_matakuliah.detail', ['matakuliah_id' => $matakuliah->id]) }}" class="btn btn-md text-white" style="background-color: #10439F;">Back</a>
            </div>
            <h2 class="text-center">{{ $tugas->judul }}</h2>
            <div class="col-md-12 d-flex justify-content-center gap-3 flex-wrap my-4">
                <div class="card col-md-8 rounded" style="border-color: #10439F;">
                    @if ($tugas->image && Storage::disk('public')->exists($tugas->image))
                    <img src="{{ Storage::url($tugas->image) }}" class="card-img-top" alt="materi-image" style="width: 50%; margin: 0 auto;">
                    @endif
                    @if ($tugas->file_path && strpos($tugas->file_path, '.mp4') !== false)
                    <video width="100%" class="mt-4" controls>
                        <source src="{{ Storage::url($tugas->file_path) }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                    @elseif ($tugas->file_path && strpos($tugas->file_path, '.pdf') !== false)
                    <embed src="{{ Storage::url($tugas->file_path) }}" type="application/pdf" width="100%" height="500px" class="mt-4 p-3" />
                    @endif
                    <div class="card-body my-3">
                        <h5 class="card-title">Perintah</h5>
                        <p class="card-text">{{ $tugas->deskripsi }}</p>
                        <h5 class="card-title">File</h5>
                        @if ($tugas->file_path)
                        <a href="{{ route('download_tugas', $tugas->id) }}" class="btn btn-primary">Download File</a>
                        @else
                        <p class="text-muted">No file available</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-body my-4 p-3 border-0 rounded shadow">
            <div class="d-flex gap-2 align-items-center">
                <h4 style="color: #10439F;" class="fw-bold">Submit Tugas</h4>
                <h6 class="fw-bold rounded p-1 text-white bg-success">{{ $tugassubmission->nilai ?? 'Belum dinilai' }}</h6>
            </div>
            <div class="col-md-12 d-flex justify-content-center gap-3 flex-wrap my-4">
                <div class="card col-md-8 rounded p-3" style="border-color: #10439F;">
                    <h5 class="fw-bold text-secondary mb-4">Pekerjaan Anda</h5>
                    @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    <div class="ps-4">
                        @if ($tugassubmission)
                        <form action="{{ route('update_tugas_mahasiswa', ['matakuliah_id' => $matakuliah->id, 'tugas_id' => $tugas->id, 'submission_id' => $tugassubmission->id]) }}" method="POST" enctype="multipart/form-data">
                            @method('PUT')
                            @csrf
                            <div class="form-group mb-3">
                                <label for="jawaban" class="fw-bold mb-1">Jawaban</label>
                                <div style="width: 70%;">
                                    <textarea class="form-control @error('jawaban') is-invalid @enderror" name="jawaban" id="jawaban" rows="3" placeholder="Masukan jawaban anda">{{ $tugassubmission->jawaban }}</textarea>
                                </div>
                                @error('jawaban')
                                <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="file_path" class="fw-bold mb-1">File</label>
                                <div style="width: 70%;">
                                    <input id="file_path" type="file" class="form-control-file @error('file_path') is-invalid @enderror" name="file_path">
                                </div>
                                @error('file_path')
                                <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                                @if ($tugassubmission->file_path)
                                <p class="mt-2"><a href="{{ Storage::url($tugassubmission->file_path) }}" class="text-decoration-none" download="{{ basename(Storage::url($tugassubmission->file_path)) }}">Lihat File</a></p>
                                @endif
                            </div>

                            <button type="submit" class="btn" style="background-color: #10439F; color: white; width: 100px;">Update</button>
                        </form>
                        @else
                        <p class="mb-2">Belum ada jawaban</p>
                        @endif
                    </div>
                </div>
            </div>
            <form action="{{ route('submit_tugas_mahasiswa', ['matakuliah_id' => $matakuliah->id, 'tugas_id' => $tugas->id]) }}" method="POST" enctype="multipart/form-data" class="col-md-8 my-5">
                @csrf
                <div class="form-group mb-3">
                    <label for="jawaban" class="fw-bold mb-1">Jawaban</label>
                    <div style="width: 70%;">
                        <textarea class="form-control @error('jawaban') is-invalid @enderror" name="jawaban" id="jawaban" rows="3" placeholder="Masukan jawaban anda"></textarea>
                    </div>
                </div>
                @error('jawaban')
                <div class="text-danger mt-2">{{ $message }}</div>
                @enderror

                <div class="form-group mb-3">
                    <label for="file_path" class="fw-bold mb-1">File</label>
                    <div style="width: 70%;">
                        <input id="file_path" type="file" class="form-control-file @error('file_path') is-invalid @enderror" name="file_path">
                    </div>
                </div>
                @error('file_path')
                <div class="text-danger mt-2">{{ $message }}</div>
                @enderror

                <button type="submit" class="btn" style="background-color: #10439F; color: white; width: 100px;">Tambah</button>
            </form>
        </div>
    </div>
</div>
@endsection