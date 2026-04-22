<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash; // Gunakan Hash untuk keamanan lebih baik

class AuthController extends Controller
{
    public function index()
    {
        $anggota = User::all();
        return view('admin.Menus.DataAnggota.data-anggota', compact('anggota'));
    }

    public function create()
    {
        return view('admin.Menus.DataAnggota.create-data-anggota');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string',
            'nrp' => 'required|string|unique:users,nrp',
            'departemen' => 'required|string',
            'kontak' => 'nullable|string',
            'role' => 'required|in:0,1,2,3,4,5,6',
            'password' => 'required|min:6', // Tambahkan minimal karakter
            'email' => 'nullable|email',
        ]);

        User::create([
            'nama' => $validated['nama'],
            'nrp' => $validated['nrp'],
            'departemen' => $validated['departemen'],
            'kontak' => $validated['kontak'],
            'role' => $validated['role'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']), // Hash::make lebih standar
        ]);

        return redirect()->route('auth.index')->with('success', 'Anggota berhasil ditambahkan');
    }

    public function edit($id)
    {
        $anggota = User::findOrFail($id); // Gunakan findOrFail agar error jika ID tidak ada
        return view('admin.Menus.DataAnggota.edit-data-anggota', compact('anggota'));
    }

    public function update(Request $request, $id)
    {
        $anggota = User::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string',
            'nrp' => 'required|string|unique:users,nrp,' . $id,
            'departemen' => 'required|string',
            'kontak' => 'nullable|string',
            'email' => 'nullable|email',
            'role' => 'required|in:0,1,2,3,4,5,6',
            'password' => 'nullable|min:6',
        ]);

        $data = $request->except(['password']); // Ambil semua kecuali password
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $anggota->update($data);

        return redirect()->route('auth.index')->with('success', 'Data anggota berhasil diperbarui');
    }

    public function home()
    {
        // Jika user sudah login, jangan biarkan masuk ke halaman login lagi
        if (Auth::check()) {
            return redirect()->intended('/dashboard');
        }
        return view('auth.login');
    }

    /**
     * Handle an authentication attempt.
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'nrp' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            /**
             * redirect()->intended() akan membawa user ke URL yang mereka tuju 
             * sebelum dicegat oleh middleware auth. Jika tidak ada (langsung login), 
             * akan dialihkan ke '/dashboard'.
             */
            return redirect()->intended('/dashboard');
        }

        return back()->with('loginError', 'NRP atau Password salah!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }

    public function destroy($id)
    {
        $anggota = User::findOrFail($id);
        $anggota->delete();

        return redirect()->route('auth.index')->with('success', 'Anggota berhasil dihapus');
    }
}