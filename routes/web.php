<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [AuthController::class, 'login'])->name('login');

// Auth
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register-user', [AuthController::class, 'registerUser'])->name('register_user');
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login-user', [AuthController::class, 'loginUser'])->name('login_user');
Route::get('/register-admin', [AuthController::class, 'registerAdmin'])->name('register_admin');
Route::post('/register-admin/regist', [AuthController::class, 'registAdmin'])->name('register_admin.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin
Route::prefix('admin')->middleware('authenticate', 'role:admin')->group(function () {

    Route::get('/', [AdminController::class, 'index'])->name('admin');

    Route::prefix('mahasiswa')->group(function () {
        Route::get('/', [AdminController::class, 'mahasiswa'])->name('admin_mahasiswa');
        Route::post('/', [AdminController::class, 'mahasiswaDatatable'])->name('admin_mahasiswa.datatable');
        Route::get('/create', [AdminController::class, 'mahasiswaCreate'])->name('admin_mahasiswa.create');
        Route::post('/create/store', [AdminController::class, 'mahasiswaStore'])->name('admin_mahasiswa.store');
        Route::get('/edit/{mahasiswa_id}', [AdminController::class, 'mahasiswaEdit'])->name('admin_mahasiswa.edit');
        Route::put('/edit/update/{mahasiswa_id}', [AdminController::class, 'mahasiswaUpdate'])->name('admin_mahasiswa.update');
        Route::delete('/delete/{mahasiswa_id}', [AdminController::class, 'mahasiswaDelete'])->name('admin_mahasiswa.delete');
    });

    Route::prefix('dosen')->group(function () {
        Route::get('/', [AdminController::class, 'dosen'])->name('admin_dosen');
        Route::post('/', [AdminController::class, 'dosenDatatable'])->name('admin_dosen.datatable');
        Route::get('/create', [AdminController::class, 'dosenCreate'])->name('admin_dosen.create');
        Route::post('/create/store', [AdminController::class, 'dosenStore'])->name('admin_dosen.store');
        Route::get('/edit/{id}', [AdminController::class, 'dosenEdit'])->name('admin_dosen.edit');
        Route::put('/edit/update/{id}', [AdminController::class, 'dosenUpdate'])->name('admin_dosen.update');
        Route::delete('/delete/{id}', [AdminController::class, 'dosenDelete'])->name('admin_dosen.delete');
    });

    Route::prefix('kelas')->group(function () {
        Route::get('/', [AdminController::class, 'kelas'])->name('admin_kelas');
        Route::post('/', [AdminController::class, 'kelasDatatable'])->name('admin_kelas.datatable');
        Route::get('/create', [AdminController::class, 'kelasCreate'])->name('admin_kelas.create');
        Route::post('create/store', [AdminController::class, 'kelasStore'])->name('admin_kelas.store');
        Route::get('/edit/{kelas_id}', [AdminController::class, 'kelasEdit'])->name('admin_kelas.edit');
        Route::put('/edit/update/{kelas_id}', [AdminController::class, 'kelasUpdate'])->name('admin_kelas.update');
        Route::delete('/delete/{kelas_id}', [AdminController::class, 'kelasDelete'])->name('admin_kelas.delete');
    });

    Route::prefix('matakuliah')->group(function () {
        Route::get('/', [AdminController::class, 'matakuliah'])->name('admin_matakuliah');
        Route::post('/', [AdminController::class, 'matakuliahDatatable'])->name('admin_matakuliah.datatable');
        Route::get('/create', [AdminController::class, 'matakuliahCreate'])->name('admin_matakuliah.create');
        Route::post('/create/store', [AdminController::class, 'matakuliahStore'])->name('admin_matakuliah.store');
        Route::get('/edit/{matakuliah_id}', [AdminController::class, 'matakuliahEdit'])->name('admin_matakuliah.edit');
        Route::put('/edit/update/{matakuliah_id}', [AdminController::class, 'matakuliahUpdate'])->name('admin_matakuliah.update');
        Route::delete('/delete/{matakuliah_id}', [AdminController::class, 'matakuliahDelete'])->name('admin_matakuliah.delete');
    });
});


// Dosen
Route::prefix('dosen')->middleware('authenticate', 'role:dosen')->group(function () {
    Route::get('/', [DosenController::class, 'index'])->name('dosen');

    Route::prefix('matakuliah')->group(function () {
        Route::get('/', [DosenController::class, 'matakuliah'])->name('dosen_matakuliah');
        Route::get('/detail/{matakuliah_id}', [DosenController::class, 'matakuliahDetail'])->name('dosen_matakuliah.detail');
        Route::get('/detail/{matakuliah_id}/create-materi', [DosenController::class, 'createMateri'])->name('create_materi');
        Route::post('/detail/{matakuliah_id}/create-materi/store', [DosenController::class, 'storeMateri'])->name('store_materi');
        Route::get('/detail/{matakuliah_id}/edit-materi/{materi_id}', [DosenController::class, 'editMateri'])->name('edit_materi');
        Route::put('/detail/{matakuliah_id}/update-materi/{materi_id}', [DosenController::class, 'updateMateri'])->name('update_materi');
        Route::delete('/detail/{matakuliah_id}/delete-materi', [DosenController::class, 'deleteMateri'])->name('delete_materi');
        Route::get('/detail/{matakuliah_id}/materi/{id}', [DosenController::class, 'detailMateri'])->name('detail_materi');
        Route::get('/download/{materi_id}', [DosenController::class, 'downloadFile'])->name('download_file');

        Route::get('/detail/{matakuliah_id}/create-tugas', [DosenController::class, 'createTugas'])->name('create_tugas');
        Route::post('/submission-tugas/nilai/{nilaiTugas_id}', [DosenController::class, 'nilaiSubmission'])->name('nilai_submission');
        Route::post('/detail/{matakuliah_id}/create-tugas/store', [DosenController::class, 'storeTugas'])->name('store_tugas');
        Route::get('/detail/{matakuliah_id}/edit-tugas/{tugas_id}', [DosenController::class, 'editTugas'])->name('edit_tugas');
        Route::put('/detail/{matakuliah_id}/update-tugas/{tugas_id}', [DosenController::class, 'updateTugas'])->name('update_tugas');
        Route::delete('/detail/{matakuliah_id}/delete-tugas/{tugas_id}', [DosenController::class, 'deleteTugas'])->name('delete_tugas');
        Route::get('/detail/{matakuliah_id}/tugas/{tugas_id}', [DosenController::class, 'detailTugas'])->name('detail_tugas');
        Route::get('/download-tugas/{tugas_id}', [DosenController::class, 'downloadTugas'])->name('download_tugas');
    });
});

// Mahasiswa
Route::prefix('mahasiswa')->middleware('authenticate', 'role:mahasiswa')->group(function () {
    Route::get('/', [MahasiswaController::class, 'index'])->name('mahasiswa');
});
