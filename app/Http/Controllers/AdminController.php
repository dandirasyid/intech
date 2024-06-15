<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\DosenMatakuliah;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\MahasiswaMatakuliah;
use App\Models\Matakuliah;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller {
    public function index() {
        $user = Auth::user();
        $mahasiswaCount = User::role('mahasiswa')->count();
        $dosenCount = User::role('dosen')->count();
        $kelasCount = Kelas::count();
        $matakuliahCount = Matakuliah::count();
        return view('admin.index', compact('user', 'mahasiswaCount', 'dosenCount', 'kelasCount', 'matakuliahCount'));
    }

    public function mahasiswa() {
        $user = Auth::user();
        return view('admin.mahasiswa.index', compact('user'));
    }

    public function mahasiswaDatatable(Request $request) {
        $mahasiswas = User::role('mahasiswa')->get();
        return DataTables::of($mahasiswas)
            ->addColumn('kelas_name', function ($model) {
                return $model->kelas ? $model->kelas->name : '-';
            })
            ->editColumn('action', function ($model) {
                return '<a href="' . route('admin_mahasiswa.edit', $model->id) . '" class="btn btn-sm btn-warning">Update</a>
                    <form action="' . route('admin_mahasiswa.delete', $model->id) . '" method="POST" class="d-inline">
                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                        <button class="btn btn-sm btn-danger text-white" type="submit">Delete</button>
                    </form>';
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
    }

    public function mahasiswaCreate() {
        $user = Auth::user();
        $kelas = Kelas::all();
        $matakuliahs = Matakuliah::all();
        return view('admin.mahasiswa.create', compact('user', 'kelas', 'matakuliahs'));
    }

    public function mahasiswaStore(Request $request) {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'image' => 'sometimes',
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'nim' => 'required',
            'no_telepon' => 'required',
            'kelas_id' => 'nullable|exists:kelas,id',
            'matakuliah_ids.*' => 'exists:matakuliah,id',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin_mahasiswa.create')
                ->withErrors($validator)
                ->withInput();
        }

        if ($request->hasFile('image')) {
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }

            $imagePath = $request->file('image')->store('postingan', 'public');
        } else {
            $imagePath = $user->image;
        }

        $user = User::create([
            'image' => $imagePath,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_telepon' => $request->no_telepon,
            'nim' => $request->nim,
            'kelas_id' => $request->kelas_id,
        ]);

        $user->assignRole('mahasiswa');

        if ($request->has('matakuliah_ids')) {
            foreach ($request->matakuliah_ids as $matakuliah_id) {
                $matakuliah = Matakuliah::find($matakuliah_id);
                if ($matakuliah) {
                    MahasiswaMatakuliah::create([
                        'matakuliah_id' => $matakuliah_id,
                        'user_id' => $user->id,
                        'kelas_id' => $request->kelas_id,
                    ]);
                }
            }
        }

        if ($user) {
            return redirect()->route('admin_mahasiswa')
                ->with('success', 'Data berhasil ditambahkan');
        } else {
            return redirect()->route('admin_mahasiswa.create')
                ->with('error', 'Gagal untuk menambahkan data');
        }
    }

    public function mahasiswaEdit($mahasiswa_id) {
        $user = Auth::user();
        $mahasiswa = User::where('id', $mahasiswa_id)
            ->whereHas('roles', function ($query) {
                $query->where('name', 'mahasiswa');
            })
            ->with('matakuliahs')
            ->firstOrFail();
        $kelas = Kelas::all();
        $matakuliahs = Matakuliah::all();
        return view('admin.mahasiswa.edit', compact('user', 'mahasiswa', 'kelas', 'matakuliahs'));
    }

    public function mahasiswaUpdate(Request $request, $mahasiswa_id) {
        $mahasiswa = User::where('id', $mahasiswa_id)
            ->whereHas('roles', function ($query) {
                $query->where('name', 'mahasiswa');
            })
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'image' => 'sometimes|image',
            'name' => 'required|string|',
            'email' => 'required|email|unique:users,email,' . $mahasiswa->id,
            'password' => 'nullable|min:8|confirmed',
            'nim' => 'required',
            'no_telepon' => 'required',
            'kelas_id' => 'nullable|exists:kelas,id',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin_mahasiswa.edit', $mahasiswa_id)
                ->withErrors($validator)
                ->withInput();
        }

        if ($request->hasFile('image')) {
            if ($mahasiswa->image) {
                Storage::disk('public')->delete($mahasiswa->image);
            }

            $imagePath = $request->file('image')->store('postingan', 'public');
        } else {
            $imagePath = $mahasiswa->image;
        }

        $mahasiswa->name = $request->name;
        $mahasiswa->email = $request->email;
        if ($request->filled('password')) {
            $mahasiswa->password = Hash::make($request->password);
        }
        $mahasiswa->no_telepon = $request->no_telepon;
        $mahasiswa->nim = $request->nim;
        $mahasiswa->image = $imagePath;
        $mahasiswa->kelas_id = $request->kelas_id;
        $mahasiswa->save();

        if ($request->has('matakuliah_ids')) {
            $matakuliahData = [];
            foreach ($request->matakuliah_ids as $matakuliah_id) {
                $matakuliahData[$matakuliah_id] = ['kelas_id' => $request->kelas_id];
            }
            $mahasiswa->matakuliahs()->sync($matakuliahData);
        } else {
            $mahasiswa->matakuliahs()->sync([]);
        }

        return redirect()->route('admin_mahasiswa')
            ->with('success', 'Data berhasil diupdate');
    }

    public function mahasiswaDelete($mahasiswa_id) {
        $mahasiswa = User::where('id', $mahasiswa_id)->whereHas('roles', function ($query) {
            $query->where('name', 'mahasiswa');
        })->firstOrFail();
        if ($mahasiswa->image && Storage::disk('public')->exists($mahasiswa->image)) {
            Storage::disk('public')->delete($mahasiswa->image);
        }
        $mahasiswa->kelas_id = null;
        $mahasiswa->save();
        $mahasiswa->delete();
        return redirect()->route('admin_mahasiswa');
    }

    public function dosen() {
        $user = Auth::user();
        return view('admin.dosen.index', compact('user'));
    }

    public function dosenDatatable(Request $request) {
        $dosens = User::role('dosen')->with('dosen')->get();
        return DataTables::of($dosens)
            ->editColumn('kelas', function ($model) {
                $kelas = $model->dosen->kelas->pluck('name')->implode(', ');
                return $kelas;
            })
            ->editColumn('action', function ($model) {
                return '<a href="' . route('admin_dosen.edit', $model->id) . '" class="btn btn-sm btn-warning">Update</a>
                        <form action="' . route('admin_dosen.delete', $model->id) . '" method="POST" class="d-inline">
                        <input type="hidden" name="_token" value="' . csrf_token() . '">
                            <button class="btn btn-sm btn-danger text-white" type="submit">Delete</button>
                        </form>';
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
    }

    public function dosenCreate() {
        $user = Auth::user();
        $kelas = Kelas::all();
        $matakuliahs = Matakuliah::all();
        return view('admin.dosen.create', compact('user', 'kelas', 'matakuliahs'));
    }

    public function dosenStore(Request $request) {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'image' => 'sometimes',
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'no_telepon' => 'required',
            'kelas_id' => 'required|array',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin_dosen.create')
                ->withErrors($validator)
                ->withInput();
        }

        if ($request->hasFile('image')) {
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }

            $imagePath = $request->file('image')->store('postingan', 'public');
        } else {
            $imagePath = $user->image;
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_telepon' => $request->no_telepon,
            'image' => $imagePath,
            'nim' => null,
            'kelas_id' => null,
        ]);

        $user->assignRole('dosen');

        $dosen = Dosen::create([
            'user_id' => $user->id,
        ]);

        if ($request->has('matakuliah_ids')) {
            $matakuliahIds = $request->matakuliah_ids;
            foreach ($matakuliahIds as $matakuliahId) {
                DosenMatakuliah::create([
                    'dosen_id' => $dosen->id,
                    'matakuliah_id' => $matakuliahId,
                ]);
            }
        }

        if ($request->has('kelas_id')) {
            $kelasIds = $request->kelas_id;
            foreach ($kelasIds as $kelasId) {
                $dosen->kelas()->attach($kelasId);
            }
        }

        if ($dosen) {
            return redirect()->route('admin_dosen')->with('success', 'Data berhasil ditambahkan.');;
        } else {
            return redirect()->route('admin_dosen.create');
        }
    }

    public function dosenEdit($id) {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $id)->first();
        $kelas = Kelas::all();
        $matakuliahs = Matakuliah::all();
        return view('admin.dosen.edit', compact('user', 'dosen', 'kelas', 'matakuliahs'));
    }

    public function dosenUpdate(Request $request, $id) {
        $dosen = Dosen::findOrFail($id);
        $user = $dosen->user;

        $validator = Validator::make($request->all(), [
            'image' => 'sometimes|file|image|max:2048',
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'no_telepon' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin_dosen.edit', $dosen->id)
                ->withErrors($validator)
                ->withInput();
        }

        if ($request->hasFile('image')) {
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }

            $imagePath = $request->file('image')->store('postingan', 'public');
        } else {
            $imagePath = $user->image;
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->filled('password') ? Hash::make($request->password) : $user->password,
            'no_telepon' => $request->no_telepon,
            'image' => $imagePath,
        ]);

        if ($request->has('kelas_id')) {
            $dosen->kelas()->sync($request->kelas_id);
        }

        if ($request->has('matakuliah_ids')) {
            $matakuliahIds = $request->matakuliah_ids;
            $dosen->matakuliahs()->sync($matakuliahIds);
        } else {
            $dosen->matakuliahs()->detach();
        }

        return redirect()->route('admin_dosen')->with('success', 'Data berhasil di update.');;
    }

    public function dosenDelete($id) {
        $dosen = Dosen::where('user_id', $id)->firstOrFail();
        if ($dosen->user->image && Storage::disk('public')->exists($dosen->user->image)) {
            Storage::disk('public')->delete($dosen->user->image);
        }
        $dosen->dosenMatakuliahs()->delete();
        $dosen->user()->delete();
        $dosen->delete();
        return redirect()->route('admin_dosen');
    }

    public function kelas() {
        $user = Auth::user();
        return view('admin.kelas.index', compact('user'));
    }

    public function kelasDatatable(Request $request) {
        $kelasList = Kelas::all();
        return DataTables::of($kelasList)
            ->editColumn('action', function ($model) {
                return '<a href="' . route('admin_kelas.edit', $model->id) . '" class="btn btn-sm btn-warning">Update</a>
                    <form action="' . route('admin_kelas.delete', $model->id) . '" method="POST" class="d-inline">
                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                        <button class="btn btn-sm btn-danger text-white" type="submit">Delete</button>
                    </form>';
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
    }

    public function kelasCreate() {
        $user = Auth::user();
        $kelas = Kelas::all();
        return view('admin.kelas.create', compact('user', 'kelas'));
    }

    public function kelasStore(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:1|unique:kelas',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin_kelas.create')
                ->withErrors($validator)
                ->withInput();
        }

        $kelas = Kelas::create([
            'name' => $request->name,
        ]);

        if ($kelas) {
            return redirect()->route('admin_kelas')->with('success', 'Data berhasil ditambahkan.');;
        }
    }

    public function kelasEdit($kelas_id) {
        $user = Auth::user();
        $kelas = Kelas::findOrFail($kelas_id);
        return view('admin.kelas.edit', compact('user', 'kelas'));
    }

    public function kelasUpdate(Request $request, $kelas_id) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:1|unique:kelas,name,' . $kelas_id,
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin_kelas.edit', $kelas_id)
                ->withErrors($validator)
                ->withInput();
        }

        $kelas = Kelas::findOrFail($kelas_id);
        $kelas->name = $request->name;
        $kelas->save();

        return redirect()->route('admin_kelas')->with('success', 'Data berhasil di update.');;
    }

    public function kelasDelete($kelas_id) {
        $kelas = Kelas::findOrFail($kelas_id);
        $kelas->delete();
        return redirect()->route('admin_kelas', 'kelas')->with('success', 'Data berhasil dihapus.');
    }

    public function matakuliah() {
        $user = Auth::user();
        return view('admin.matakuliah.index', compact('user'));
    }

    public function matakuliahDatatable(Request $request) {
        $matakuliahs = Matakuliah::all();
        return DataTables::of($matakuliahs)
            ->editColumn('action', function ($model) {
                return '<a href="' . route('admin_matakuliah.edit', $model->id) . '" class="btn btn-sm btn-warning">Update</a>
                <form action="' . route('admin_matakuliah.delete', $model->id) . '" method="POST" class="d-inline">
                <input type="hidden" name="_token" value="' . csrf_token() . '">
                    <button class="btn btn-sm btn-danger text-white" type="submit">Delete</button>
                </form>';
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
    }

    public function matakuliahCreate() {
        $user = Auth::user();
        return view('admin.matakuliah.create', compact('user'));
    }

    public function matakuliahStore(Request $request) {
        $validator = Validator::make($request->all(), [
            'image' => 'sometimes|image',
            'name' => 'required|string|',
            'deskripsi' => 'nullable',
            'semester' => 'required|max:1',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin_matakuliah.create')
                ->withErrors($validator)
                ->withInput();
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('postingan', 'public');
        }

        $matakuliah = Matakuliah::create([
            'image' => $imagePath,
            'name' => $request->name,
            'deskripsi' => $request->deskripsi,
            'semester' => $request->semester,
        ]);

        if ($matakuliah) {
            return redirect()->route('admin_matakuliah')
                ->with('success', 'Data berhasil ditambahkan');
        } else {
            return redirect()->route('admin_matakuliah.create')
                ->with('error', 'Gagal untuk menambahkan data');
        }
    }

    public function matakuliahEdit($matakuliah_id) {
        $user = Auth::user();
        $matakuliah = Matakuliah::findOrFail($matakuliah_id);
        return view('admin.matakuliah.edit', compact('user', 'matakuliah'));
    }

    public function matakuliahUpdate(Request $request, $matakuliah_id) {
        $matakuliah = Matakuliah::findOrFail($matakuliah_id);

        $validator = Validator::make($request->all(), [
            'image' => 'sometimes|image',
            'name' => 'required|string',
            'deskripsi' => 'nullable|string',
            'semester' => 'required|max:1|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin_matakuliah.edit', $matakuliah_id)
                ->withErrors($validator)
                ->withInput();
        }

        if ($request->hasFile('image')) {
            if ($matakuliah->image) {
                Storage::disk('public')->delete($matakuliah->image);
            }

            $imagePath = $request->file('image')->store('postingan', 'public');
        } else {
            $imagePath = $matakuliah->image;
        }

        $matakuliah->update([
            'image' => $imagePath,
            'name' => $request->name,
            'deskripsi' => $request->deskripsi,
            'semester' => $request->semester,
        ]);

        return redirect()->route('admin_matakuliah')
            ->with('success', 'Data berhasil diupdate');
    }

    public function matakuliahDelete($matakuliah_id) {
        $matakuliah = Matakuliah::findOrFail($matakuliah_id);
        $matakuliah->delete();
        return redirect()->route('admin_matakuliah', 'matakuliah')->with('success', 'Data berhasil dihapus.');
    }
}
