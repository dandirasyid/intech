<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\MahasiswaMatakuliah;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MahasiswaController extends Controller {
    public function index() {
        $user = Auth::user();
        $matakuliahs = MahasiswaMatakuliah::where('user_id', $user->id)->with('matakuliah')->get();
        $totalDosen = $matakuliahs->flatMap(function ($item) {
            return $item->matakuliah->dosens ?? [];
        })->unique('id')->count();


        // Hitung total matakuliah yang dipilih
        $totalMatakuliah = $matakuliahs->count();
        return view('mahasiswa.index', compact('user', 'totalDosen', 'totalMatakuliah'));
    }
}
