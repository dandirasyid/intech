@extends('layouts.master')
@section('title', 'Detail Matakuliah')

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
                <a href="{{ route('dosen_matakuliah') }}" class="btn btn-md text-white" style="background-color: #10439F;">Back</a>
            </div>
            <div class="d-flex align-items-center justify-content-between mb-5" style="margin: 0 100px;">
                <div>
                    <h4 class="fw-bold">{{ $matakuliah->name }}</h4>
                    <p class="mb-3">{{ $matakuliah->deskripsi }}</p>
                    <p class="mb-1">Dosen : </p>
                    <div class="d-flex gap-2 flex-wrap">
                        @foreach ($matakuliah->dosens as $dosen)
                        <p class="text-white rounded mb-1" style="background-color: #10439F; padding: 2px;">{{ $dosen->user->name }}</p>
                        @endforeach
                    </div>
                </div>
                <div>
                    <img src="{{ asset('images/dosen-dashboard.png') }}" alt="image-admin" width="400px">
                </div>
            </div>

            <div class="col-md-12 mt-4 mb-5">
                @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <div class="d-flex justify-content-start gap-3 mb-3">
                    <h5 class="fw-bold" style="color: #10439F;">Materi</h5>
                    <a href="{{ route('create_materi', ['matakuliah_id' => $matakuliah->id]) }}" class="btn btn-sm btn-outline-success">Tambah</a>
                </div>
                <div>
                    <table class="table table-striped mt-3" id="datatable">
                        <thead class="text-center">
                            <tr>
                                <th>No</th>
                                <th>Judul</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @foreach ($materis as $materi)
                            <tr>
                                <td class="align-middle">{{ $loop->iteration }}</td>
                                <td class="align-middle">{{ $materi->judul }}</td>
                                <td class="align-middle">{{ $materi->created_at->diffForHumans() }}</td>
                                <td class="align-middle">
                                    <a href="{{ route('detail_materi', ['matakuliah_id' => $matakuliah->id, 'id' => $materi->id]) }}" class="btn btn-sm btn-primary">Detail</a>
                                    <a href="{{ route('edit_materi', ['matakuliah_id' => $matakuliah->id, 'materi_id' => $materi->id]) }}" class="btn btn-sm btn-warning">Update</a>
                                    <form action="{{ route('delete_materi', ['matakuliah_id' => $materi->id]) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger text-white" type="submit">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-12 mt-4 mb-5">
                <div class="d-flex justify-content-start gap-3 mb-3">
                    <h5 class="fw-bold" style="color: #10439F;">Tugas</h5>
                    <a href="{{ route('create_tugas', ['matakuliah_id' => $matakuliah->id]) }}" class="btn btn-sm btn-outline-success">Tambah</a>
                </div>
                <div>
                    <table class="table table-striped mt-3" id="datatable">
                        <thead class="text-center">
                            <tr>
                                <th>No</th>
                                <th>Judul</th>
                                <th>Deadline</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @foreach ($matakuliah->tugas as $index => $tugas)
                            <tr>
                                <td class="align-middle">{{ $index + 1 }}</td>
                                <td class="align-middle">{{ $tugas->judul }}</td>
                                <td class="align-middle">{{ $tugas->deadline }}</td>
                                <td class="align-middle">
                                    <a href="{{ route('detail_tugas', ['matakuliah_id' => $matakuliah->id, 'tugas_id' => $tugas->id]) }}" class="btn btn-sm btn-primary">Detail</a>
                                    <a href="{{ route('edit_tugas', ['matakuliah_id' => $matakuliah->id, 'tugas_id' => $tugas->id]) }}" class="btn btn-sm btn-warning">Update</a>
                                    <form action="{{ route('delete_tugas', ['matakuliah_id' => $matakuliah->id, 'tugas_id' => $tugas->id]) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm text-white" type="submit">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-12 mt-4 mb-5">
                <div class="d-flex justify-content-start gap-3 mb-3">
                    <h5 class="fw-bold" style="color: #10439F;">Tugas dan Ujian Pilihan Ganda</h5>
                    <button class="btn btn-sm btn-outline-success" type="submit" style="height: 30px;">Tambah</button>
                </div>
                <div>
                    <table class="table table-striped mt-3" id="datatable">
                        <thead class="text-center">
                            <tr>
                                <th>No</th>
                                <th>Nama Ujian</th>
                                <th>Waktu</th>
                                <th>Deadline</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <tr>
                                <td class="align-middle"></td>
                                <td class="align-middle"></td>
                                <td class="align-middle"></td>
                                <td class="align-middle"></td>
                                <td class="align-middle">
                                    <a href="" class="btn btn-sm btn-primary">Detail</a>
                                    <a href="" class="btn btn-sm btn-warning">Update</a>
                                    <form action="" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger text-white" type="submit">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection