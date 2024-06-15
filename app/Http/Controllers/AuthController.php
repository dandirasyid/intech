<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller {
    public function register() {
        $user = Auth::user();
        return view('auth.register', compact('user'));
    }

    public function registerUser(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'nim' => 'required',
            'no_telepon' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('register')
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nim' => $request->nim,
            'no_telepon' => $request->no_telepon,
            'image' => null,
            'kelas_id' => null,
        ]);

        $user->assignRole('mahasiswa');

        if ($user) {
            return redirect()->route('login')
                ->with('success', 'Registrasi berhasil! Silakan login.');
        } else {
            return redirect()->route('register')
                ->with('error', 'Failed to create user');
        }
    }

    public function login() {
        $user = Auth::user();
        return view('auth.login', compact('user'));
    }

    public function loginUser(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return redirect()->route('login')
                ->withErrors($validator)
                ->withInput();
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->roles->contains('name', 'admin')) {
                return redirect()->route('admin');
            } elseif ($user->roles->contains('name', 'dosen')) {
                return redirect()->route('dosen');
            } elseif ($user->roles->contains('name', 'mahasiswa')) {
                return redirect()->route('mahasiswa');
            } else {
                return redirect()->route('login')->with('error', 'Login failed, username or password is incorrect');
            }
        } else {
            return redirect()->route('login')
                ->with('error', 'Login failed, username or password is incorrect');
        }
    }

    public function registerAdmin() {
        $admin = Auth::user();
        return view('auth.registerAdmin', compact('admin'));
    }

    public function registAdmin(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'no_telepon' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('register_admin')
                ->withErrors($validator)
                ->withInput();
        }

        $admin = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_telepon' => $request->no_telepon,
            'nim' => null,
            'image' => null,
            'kelas_id' => null,
        ]);

        $admin->assignRole('admin');

        if ($admin) {
            return redirect()->route('login')
                ->with('success', 'Registrasi berhasil! Silakan login.');
        } else {
            return redirect()->route('register_admin')
                ->with('error', 'Failed to create admin');
        }
    }

    public function logout() {
        Auth::logout();
        return view('auth.login');
    }
}
