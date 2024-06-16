<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <title>Sidebar</title>
</head>

<body>
    <div id="sidebar" class="sidebar">
        <div class="togglebtn" onclick="toggleSidebar()">
            <i class="bi bi-list"></i>
        </div>
        <div class="sidebar-content mt-3">
            @auth
            @if(auth()->user()->hasRole('admin'))
            <a href="{{ route('admin') }}"><i class="bi bi-house"></i> <span class="link-text">Home</span></a>
            <a href="{{ route('admin_mahasiswa') }}"><i class="bi bi-mortarboard"></i> <span class="link-text">Data Mahasiswa</span></a>
            <a href="{{ route('admin_dosen') }}"><i class="bi bi-people"></i> <span class="link-text">Data Dosen</span></a>
            <a href="{{ route('admin_kelas') }}"><i class="bi bi-building"></i> <span class="link-text">Data Kelas</span></a>
            <a href="{{ route('admin_matakuliah') }}"><i class="bi bi-book"></i> <span class="link-text">Data Matakuliah</span></a>
            <a href="{{ route ('logout') }}"><i class="bi bi-box-arrow-left"></i> <span class="link-text">Logout</span></a>
            @elseif(auth()->user()->hasRole('dosen'))
            <a href="{{ route('dosen') }}"><i class="bi bi-house"></i> <span class="link-text">Home</span></a>
            <a href="{{ route('dosen_matakuliah') }}"><i class="bi bi-book"></i> <span class="link-text">Data Matakuliah</span></a>
            <a href="{{ route ('logout') }}"><i class="bi bi-box-arrow-left"></i> <span class="link-text">Logout</span></a>
            @elseif(auth()->user()->hasRole('mahasiswa'))
            <a href="{{ route('mahasiswa') }}"><i class="bi bi-house"></i> <span class="link-text">Home</span></a>
            <a href="{{ route('mahasiswa_matakuliah') }}"><i class="bi bi-book"></i> <span class="link-text">Data Matakuliah</span></a>
            <a href="{{ route ('logout') }}"><i class="bi bi-box-arrow-left"></i> <span class="link-text">Logout</span></a>
            @endif
            @endauth
        </div>
    </div>

</body>

</html>