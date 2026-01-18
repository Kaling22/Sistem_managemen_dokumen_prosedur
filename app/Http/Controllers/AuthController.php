<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class AuthController extends Controller
{

     public function index()
    {
        $anggota = User::all();
        return view ('admin.Menus.DataAnggota.data-anggota',compact('anggota'));
    }

    public function create()
    {
        return view('admin.Menus.DataAnggota.create-data-anggota');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string',
            'nrp' => 'required|string|unique:users,nrp',
            'departemen' => 'required|string',
            'kontak' => 'nullable|string',
            'role' => 'required|in:0,1,2,3,4,5,6',
            'password' => 'required|min:6',
        ]);
    
        User::create([
            'nama' => $validated['nama'],
            'nrp' => $validated['nrp'],
            'departemen' => $validated['departemen'],
            'kontak' => $validated['kontak'],
            'role' => $validated['role'],
            'password' => bcrypt($validated['password']),
        ]);
    
        return redirect()->route('auth.index')
            ->with('success', 'Akun berhasil dibuat');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $anggota = User::find($id);
        return view('admin.Menus.DataAnggota.edit-data-anggota',compact('anggota'));
    }

     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $anggota = User::findOrFail($id);
    
        $validated = $request->validate([
            'nama' => 'required|string',
            'nrp' => 'required|string|unique:users,nrp,' . $id,
            'departemen' => 'required|string',
            'kontak' => 'nullable|string',
            'role' => 'required|in:0,1,2,3,4,5,6',
            'password' => 'nullable|min:6',
        ]);
    
        $data = [
            'nama' => $validated['nama'],
            'nrp' => $validated['nrp'],
            'departemen' => $validated['departemen'],
            'kontak' => $validated['kontak'],
            'role' => $validated['role'],
        ];
    
        if (!empty($validated['password'])) {
            $data['password'] = bcrypt($validated['password']);
        }
    
        $anggota->update($data);
    
        return redirect()->route('auth.index')
            ->with('success', 'Akun berhasil diperbarui');
    }


    public function home()
    {
        return view ('auth.login');
    }

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'nrp' => ['required'],
            'password' => ['required'],
        ]); 
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        } 
        return back()->with('loginError', 'Login gagal');
    }
    
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
