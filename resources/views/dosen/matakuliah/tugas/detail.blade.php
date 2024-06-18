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
                <a href="{{ route('dosen_matakuliah.detail', ['matakuliah_id' => $matakuliah->id]) }}" class="btn btn-md text-white" style="background-color: #10439F;">Back</a>
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
                        <h5 class="card-title">Deskripsi</h5>
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
            <p style="color: #10439F; font-weight: 500;">Submission Tugas Mahasiswa</p>
            <table class="table table-striped mt-2" id="datatable">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Jawaban</th>
                        <th>File</th>
                        <th>Nilai</th>
                        <th>Input Nilai</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach ($tugas->tugasSubmissions as $index => $submission)
                    <tr>
                        <td class="align-middle">{{ $index + 1 }}</td>
                        <td class="align-middle">{{ $submission->user->name }}</td>
                        <td class="align-middle">{{ $submission->jawaban }}</td>
                        <td class="align-middle">
                            @if ($submission->file_path)
                            <a href="{{ Storage::url($submission->file_path) }}" download target="_blank" class="text-decoration-none">Lihat File</a>
                            @else
                            <p class="text-muted">No file available</p>
                            @endif
                        </td>
                        <td class="align-middle">{{ $submission->nilai ?? 'Belum dinilai' }}</td>
                        <td class="align-middle">
                            <form action="{{ route('nilai_submission', ['nilaiTugas_id' => $submission->id]) }}" method="POST">
                                @csrf
                                <input type="number" name="nilai" class="form-control" value="{{ $submission->nilai }}" required>
                                <button type="submit" class="btn btn-primary mt-2">Submit</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endsection