<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\MahasiswaMatakuliah;
use App\Models\Matakuliah;
use App\Models\Materi;
use App\Models\Tugas;
use App\Models\TugasSubmission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MahasiswaController extends Controller {
    public function index() {
        $user = Auth::user();
        $matakuliahs = MahasiswaMatakuliah::where('user_id', $user->id)
            ->with('matakuliah.kelas')
            ->get();

        $totalDosen = $matakuliahs->flatMap(function ($item) {
            return $item->matakuliah->dosens ?? [];
        })->unique('id')->count();

        $kelas = $matakuliahs->first()->kelas ?? null;
        $totalKelas = $kelas ? 1 : 0;

        $totalMatakuliah = $matakuliahs->count();
        return view('mahasiswa.index', compact('user', 'totalDosen',  'totalKelas', 'totalMatakuliah'));
    }

    public function profile() {
        $user = Auth::user();
        $kelas = Kelas::all();
        $matakuliahs = Matakuliah::all();
        return view('mahasiswa.profile', compact('user', 'kelas', 'matakuliahs'));
    }

    public function profileUpdate(Request $request) {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'image' => 'sometimes|image',
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
            'no_telepon' => 'required',
            'nim' => 'required',
            'kelas_id' => 'nullable|exists:kelas,id',
            'matakuliah_ids' => 'nullable|array',
            'matakuliah_ids.*' => 'exists:matakuliah,id',
        ]);

        if ($validator->fails()) {
            return redirect()->route('profile')
                ->withErrors($validator)
                ->withInput();
        }

        if ($request->hasFile('image')) {
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }
            $imagePath = $request->file('image')->store('profile', 'public');
        } else {
            $imagePath = $user->image;
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->filled('password') ? Hash::make($request->password) : $user->password,
            'no_telepon' => $request->no_telepon,
            'image' => $imagePath,
            'nim' => $request->nim,
            'kelas_id' => $request->kelas_id,
        ]);

        if ($request->has('matakuliah_ids')) {
            $matakuliahData = [];
            foreach ($request->matakuliah_ids as $matakuliah_id) {
                $matakuliahData[$matakuliah_id] = ['kelas_id' => $request->kelas_id];
            }
            $user->matakuliahs()->sync($matakuliahData);
        } else {
            $user->matakuliahs()->sync([]);
        }

        return redirect()->route('mahasiswa')->with('success', 'Profile updated successfully');
    }

    public function matakuliah() {
        $user = Auth::user();
        $matakuliahs = $user->matakuliahs()->with('kelas')->get();
        return view('mahasiswa.matakuliah.index', compact('user', 'matakuliahs'));
    }

    public function matakuliahDetail($matakuliah_id) {
        $user = Auth::user();
        $matakuliah = Matakuliah::with('dosens.user', 'materis', 'tugas')->findOrFail($matakuliah_id);
        $materis = Materi::where('matakuliah_id', $matakuliah_id)->get();
        return view('mahasiswa.matakuliah.detail', compact('user', 'matakuliah', 'materis'));
    }

    public function detailMateri($matakuliah_id, $id) {
        $user = Auth::user();
        $materi = Materi::where('matakuliah_id', $matakuliah_id)->findOrFail($id);
        $matakuliah = Matakuliah::findOrFail($matakuliah_id);
        return view('mahasiswa.matakuliah.materi.index', compact('user', 'materi', 'matakuliah'));
    }

    public function downloadMateri($materi_id) {
        $materi = Materi::findOrFail($materi_id);

        if (!$materi->file_path) {
            return redirect()->back()->with('error', 'File tidak tersedia');
        }

        $filePath = storage_path('app/public/' . $materi->file_path);
        $fileName = basename($filePath);

        return response()->download($filePath, $fileName);
    }

    public function detailTugas($matakuliah_id, $tugas_id) {
        $user = Auth::user();
        $matakuliah = Matakuliah::findOrFail($matakuliah_id);
        $tugas = Tugas::findOrFail($tugas_id);
        $tugassubmission = TugasSubmission::where('user_id', Auth::id())
            ->where('tugas_id', $tugas_id)
            ->first();
        return view('mahasiswa.matakuliah.tugas.index', compact('user', 'matakuliah', 'tugas', 'tugassubmission'));
    }

    public function editTugas($matakuliah_id, $tugas_id, $submission_id) {
        $user = Auth::user();
        $matakuliah = Matakuliah::findOrFail($matakuliah_id);
        $tugas = Tugas::findOrFail($tugas_id);
        $tugassubmission = TugasSubmission::findOrFail($submission_id);

        return view('mahasiswa.matakuliah.tugas.index', compact('user', 'matakuliah', 'tugas', 'tugassubmission'));
    }

    public function updateTugas(Request $request, $matakuliah_id, $tugas_id, $submission_id) {
        $tugas = Tugas::findOrFail($tugas_id);

        if ($tugas->deadline < now()) {
            return redirect()->back()->with('error', 'Maaf, Anda tidak dapat melakukan update setelah batas waktu deadline.');
        }

        $request->validate([
            'jawaban' => 'required|string',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx,txt,jpg,jpeg,png,mp4',
        ]);

        $submission = TugasSubmission::findOrFail($submission_id);
        $submission->jawaban = $request->jawaban;

        if ($request->hasFile('file_path')) {
            if ($submission->file_path) {
                Storage::disk('public')->delete($submission->file_path);
            }

            $file = $request->file('file_path');
            $path = $file->store('tugas_files', 'public');
            $submission->file_path = $path;
        }

        $submission->save();

        return redirect()->route('detail_tugas_mahasiswa', ['matakuliah_id' => $matakuliah_id, 'tugas_id' => $tugas_id])
            ->with('success', 'Tugas berhasil diperbarui.');
    }

    public function submitTugas(Request $request, $matakuliah_id, $tugas_id) {
        $tugas = Tugas::findOrFail($tugas_id);

        if ($tugas->deadline < now()) {
            return redirect()->back()->with('error', 'Maaf, Anda tidak dapat menambahkan tugas setelah batas waktu deadline.');
        }
        
        $request->validate([
            'jawaban' => 'required|string',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx,txt,jpg,jpeg,png,mp4',
        ]);

        $submission = TugasSubmission::where('user_id', Auth::id())
            ->where('tugas_id', $tugas_id)
            ->first();

        if ($submission) {
            // Update submission
            $submission->jawaban = $request->jawaban;

            if ($request->hasFile('file_path')) {
                $file = $request->file('file_path');
                $path = $file->store('tugas_files', 'public');
                $submission->file_path = $path;
            }

            $submission->save();

            return redirect()->route('detail_tugas_mahasiswa', ['matakuliah_id' => $matakuliah_id, 'tugas_id' => $tugas_id])
                ->with('success', 'Jawaban tugas berhasil diperbarui.');
        } else {
            $newSubmission = new TugasSubmission();
            $newSubmission->jawaban = $request->jawaban;
            $newSubmission->tugas_id = $tugas_id;
            $newSubmission->user_id = Auth::id();

            if ($request->hasFile('file_path')) {
                $file = $request->file('file_path');
                $path = $file->store('tugas_files', 'public');
                $newSubmission->file_path = $path;
            }

            $newSubmission->save();

            return redirect()->route('detail_tugas_mahasiswa', ['matakuliah_id' => $matakuliah_id, 'tugas_id' => $tugas_id])
                ->with('success', 'Tugas berhasil dikirim.');
        }
    }
}
