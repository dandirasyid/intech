<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Matakuliah;
use App\Models\Materi;
use App\Models\Tugas;
use App\Models\TugasSubmission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DosenController extends Controller {
    public function index() {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        $mahasiswaCount = User::role('mahasiswa')
            ->whereHas('kelas', function ($query) use ($dosen) {
                $query->whereHas('dosens', function ($q) use ($dosen) {
                    $q->where('dosen_id', $dosen->id);
                });
            })
            ->count();

        $kelasCount = Kelas::whereHas('dosens', function ($query) use ($dosen) {
            $query->where('dosen_id', $dosen->id);
        })
            ->count();

        $matakuliahCount = Matakuliah::whereHas('dosenMatakuliahs', function ($query) use ($dosen) {
            $query->where('dosen_id', $dosen->id);
        })
            ->count();
        return view('dosen.index', compact('user', 'mahasiswaCount', 'kelasCount', 'matakuliahCount'));
    }

    public function matakuliah() {
        $user = Auth::user();
        $dosen = $user->dosen;
        $matakuliahs = $dosen->matakuliahs;
        return view('dosen.matakuliah.index', compact('user', 'matakuliahs'));
    }

    public function matakuliahDetail($matakuliah_id) {
        $user = Auth::user();
        $matakuliah = Matakuliah::with('dosens')->findOrFail($matakuliah_id);
        $materis = Materi::where('matakuliah_id', $matakuliah_id)->get();
        return view('dosen.matakuliah.detail', compact('user', 'matakuliah', 'materis'));
    }

    public function detailMateri($matakuliah_id, $id) {
        $user = Auth::user();
        $materi = Materi::where('matakuliah_id', $matakuliah_id)->findOrFail($id);
        $matakuliah = Matakuliah::findOrFail($matakuliah_id);
        return view('dosen.matakuliah.materi.detail', compact('user', 'materi', 'matakuliah'));
    }

    public function createMateri($matakuliah_id) {
        $user = Auth::user();
        $matakuliah = Matakuliah::findOrFail($matakuliah_id);
        return view('dosen.matakuliah.materi.create', compact('user', 'matakuliah'));
    }

    public function storeMateri(Request $request, $matakuliah_id) {
        $user = Auth::user();
        $matakuliah = Matakuliah::findOrFail($matakuliah_id);
        $validator = Validator::make($request->all(), [
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'link_video' => 'nullable|url',
            'file_path' => 'sometimes|file|mimes:pdf,doc,docx,ppt,pptx',
        ]);

        if ($validator->fails()) {
            return redirect()->route('create_materi', ['matakuliah_id' => $matakuliah_id])
                ->withErrors($validator)
                ->withInput();
        }

        if ($request->hasFile('image')) {
            if ($matakuliah->image) {
                Storage::disk('public')->delete($matakuliah->image);
            }

            $imagePath = $request->file('image')->store('foto-materi', 'public');
        } else {
            $imagePath = $matakuliah->image;
        }

        if ($request->hasFile('file_path')) {
            if ($matakuliah->file_path) {
                Storage::disk('public')->delete($matakuliah->file_path);
            }

            $filePath = $request->file('file_path')->store('materi', 'public');
        } else {
            $filePath = $matakuliah->file_path;
        }

        if ($request->link_video) {
            $link_video = str_replace('watch?v=', 'embed/', $request->link_video);
        } else {
            $link_video = null;
        }

        $materi = Materi::create([
            'image' => $imagePath,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'link_video' => $link_video,
            'file_path' => $filePath,
            'matakuliah_id' => $matakuliah_id,
            'dosen_id' => $user->dosen->id,
        ]);

        return redirect()->route('dosen_matakuliah.detail', ['matakuliah_id' => $matakuliah_id])
            ->with('success', 'Materi berhasil ditambahkan.');
    }

    public function editMateri($matakuliah_id, $materi_id) {
        $user = Auth::user();
        $matakuliah = Matakuliah::findOrFail($matakuliah_id);
        $materi = Materi::findOrFail($materi_id);
        return view('dosen.matakuliah.materi.edit', compact('user', 'matakuliah', 'materi'));
    }

    public function updateMateri(Request $request, $matakuliah_id, $materi_id) {
        $user = Auth::user();
        $materi = Materi::findOrFail($materi_id);
        $validator = Validator::make($request->all(), [
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'judul' => 'required|string|max:255',
            'link_video' => 'nullable|url',
            'deskripsi' => 'required|string',
            'file_path' => 'sometimes|file|mimes:pdf,doc,docx,ppt,pptx',
        ]);

        if ($validator->fails()) {
            return redirect()->route('edit_materi', ['matakuliah_id' => $matakuliah_id, 'materi_id' => $materi_id])
                ->withErrors($validator)
                ->withInput();
        }

        if ($request->hasFile('image')) {
            if ($materi->image) {
                Storage::disk('public')->delete($materi->image);
            }

            $imagePath = $request->file('image')->store('foto-materi', 'public');
        } else {
            $imagePath = $materi->image;
        }

        if ($request->hasFile('file_path')) {
            if ($materi->file_path) {
                Storage::disk('public')->delete($materi->file_path);
            }

            $filePath = $request->file('file_path')->store('materi', 'public');
        } else {
            $filePath = $materi->file_path;
        }

        if ($request->link_video) {
            $link_video = str_replace('watch?v=', 'embed/', $request->link_video);
        } else {
            $link_video = null;
        }

        $materi->update([
            'image' => $imagePath,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'link_video' => $link_video,
            'file_path' => $filePath,
            'matakuliah_id' => $matakuliah_id,
            'dosen_id' => $user->dosen->id,
        ]);

        return redirect()->route('dosen_matakuliah.detail', ['matakuliah_id' => $matakuliah_id])
            ->with('success', 'Materi berhasil diupdate.');
    }

    public function deleteMateri($matakuliah_id) {
        $materi = Materi::findOrFail($matakuliah_id);
        if ($materi->file_path) {
            Storage::disk('public')->delete($materi->file_path);
        }

        if ($materi->image) {
            Storage::disk('public')->delete($materi->image);
        }
        $materi->delete();
        return redirect()->route('dosen_matakuliah.detail', ['matakuliah_id' => $materi->matakuliah_id])
            ->with('success', 'Materi berhasil dihapus.');
    }

    public function downloadFile($materi_id) {
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
        $tugas = Tugas::with('tugasSubmissions.user')->findOrFail($tugas_id);
        return view('dosen.matakuliah.tugas.detail', compact('user', 'matakuliah', 'tugas'));
    }

    public function nilaiSubmission(Request $request, $nilaiTugas_id) {
        $submission = TugasSubmission::findOrFail($nilaiTugas_id);
        $submission->update(['nilai' => $request->nilai]);
        return redirect()->back()->with('success', 'Nilai berhasil diberikan.');
    }

    public function createTugas($matakuliah_id) {
        $user = Auth::user();
        $matakuliah = Matakuliah::with('tugas')->findOrFail($matakuliah_id);
        return view('dosen.matakuliah.tugas.create', compact('user', 'matakuliah'));
    }

    public function storeTugas(Request $request, $matakuliah_id) {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'judul' => 'required|string|max:255',
            'deadline' => 'required|date',
            'deskripsi' => 'required|string',
            'file_path' => 'sometimes|file|mimes:pdf,doc,docx,ppt,pptx',
        ]);

        if ($validator->fails()) {
            return redirect()->route('create_tugas', ['matakuliah_id' => $matakuliah_id])
                ->withErrors($validator)
                ->withInput();
        }

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('foto-tugas', 'public');
        } else {
            $imagePath = null;
        }

        if ($request->hasFile('file_path')) {
            $filePath = $request->file('file_path')->store('tugas', 'public');
        } else {
            $filePath = null;
        }

        $tugas = Tugas::create([
            'image' => $imagePath,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'deadline' => $request->deadline,
            'file_path' => $filePath,
            'matakuliah_id' => $matakuliah_id,
            'dosen_id' => $dosen->id,
        ]);

        return redirect()->route('dosen_matakuliah.detail', ['matakuliah_id' => $matakuliah_id])
            ->with('success', 'Tugas berhasil ditambahkan.');
    }

    public function editTugas($matakuliah_id, $tugas_id) {
        $user = Auth::user();
        $matakuliah = Matakuliah::findOrFail($matakuliah_id);
        $tugas = Tugas::findOrFail($tugas_id);
        return view('dosen.matakuliah.tugas.edit', compact('user', 'matakuliah', 'tugas'));
    }

    public function updateTugas(Request $request, $matakuliah_id, $tugas_id) {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->firstOrFail();
        $tugas = Tugas::findOrFail($tugas_id);

        $validator = Validator::make($request->all(), [
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'judul' => 'required|string|max:255',
            'deadline' => 'required|date',
            'deskripsi' => 'nullable|string',
            'file_path' => 'sometimes|file|mimes:pdf,doc,docx,ppt,pptx',
        ]);

        if ($validator->fails()) {
            return redirect()->route('edit_tugas', ['matakuliah_id' => $matakuliah_id, 'tugas_id' => $tugas_id])
                ->withErrors($validator)
                ->withInput();
        }

        if ($request->hasFile('image')) {
            if ($tugas->image) {
                Storage::disk('public')->delete($tugas->image);
            }
            $imagePath = $request->file('image')->store('foto-tugas', 'public');
        } else {
            $imagePath = $tugas->image;
        }

        if ($request->hasFile('file_path')) {
            if ($tugas->file_path) {
                Storage::disk('public')->delete($tugas->file_path);
            }
            $filePath = $request->file('file_path')->store('tugas', 'public');
        } else {
            $filePath = $tugas->file_path;
        }

        $tugas->update([
            'image' => $imagePath,
            'judul' => $request->judul,
            'deadline' => $request->deadline,
            'deskripsi' => $request->deskripsi,
            'file_path' => $filePath,
            'matakuliah_id' => $matakuliah_id,
            'dosen_id' => $dosen->id,
        ]);

        return redirect()->route('dosen_matakuliah.detail', ['matakuliah_id' => $matakuliah_id])
            ->with('success', 'Tugas berhasil diperbarui.');
    }

    public function deleteTugas($matakuliah_id, $tugas_id) {
        $user = Auth::user();
        $tugas = Tugas::findOrFail($tugas_id);

        if ($tugas->dosen_id !== $user->dosen->id) {
            return redirect()->route('dosen_matakuliah.detail', ['matakuliah_id' => $matakuliah_id])
                ->with('error', 'Anda tidak memiliki izin untuk menghapus tugas ini.');
        }

        if ($tugas->image) {
            Storage::disk('public')->delete($tugas->image);
        }

        if ($tugas->file_path) {
            Storage::disk('public')->delete($tugas->file_path);
        }
        $tugas->delete();
        return redirect()->route('dosen_matakuliah.detail', ['matakuliah_id' => $matakuliah_id])
            ->with('success', 'Tugas berhasil dihapus.');
    }

    public function downloadTugas($tugas_id) {
        $tugas = Tugas::findOrFail($tugas_id);

        if (!$tugas->file_path) {
            return redirect()->back()->with('error', 'File tidak tersedia');
        }

        $filePath = storage_path('app/public/' . $tugas->file_path);
        $fileName = basename($filePath);

        return response()->download($filePath, $fileName);
    }
}
