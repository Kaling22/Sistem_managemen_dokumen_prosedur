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
        $input = $request->all();
        User::create([   
            'role' => 0,
            'nama' => $request->nama,
            'kontak' => $request->kontak,
            'nrp' => $request->nrp,
            'password' => bcrypt($input['password']),
        ]);

        return redirect()->route('auth.index');
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
        $input = $request->all();
        $anggota = User::find($id);
        $anggota->update([
            'role' => 0,
            'nama' => $request->nama,
            'kontak' => $request->kontak,
            'nrp' => $request->nrp,
            'password' => bcrypt($input['password']),
        ]);
        return redirect()->route('auth.index');
    }

    public function destroy(string $id)
    {
        $anggota = User::find($id);
        $anggota -> delete();
        return redirect()->route('auth.index');
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
