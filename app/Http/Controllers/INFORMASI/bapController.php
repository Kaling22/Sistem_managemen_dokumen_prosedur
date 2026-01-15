<?php

namespace App\Http\Controllers\INFORMASI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\INFORMASI\tb_bap;
use Illuminate\Support\Facades\Storage;
class bapController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $baps = tb_bap::all();
        return view ('admin.Menus.DataInformasi.BAP.data-bap', compact('baps'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('admin.Menus.DataInformasi.BAP.create-bap');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validasi format file
        $this->validate($request, [
            'file' => 'required|mimes:pdf|max:20480',
        ]);

        $file = $request->file('file');
        $file->storeAs('public/bap', $file->hashName());
        tb_bap::create([
            'no_dokumen' => $request->no_dokumen,
            'judul' => $request->judul,
            'file' => $file->hashName(),

        ]);
        return redirect()->route('dataBap.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $baps = tb_bap::find($id);
        return view('admin.Menus.DataInformasi.BAP.edit-bap',compact('baps'));
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
        $baps = tb_bap::find($id);
        $this->validate($request, [
            'file' => 'required|mimes:pdf|max:20480',
        ]);
        Storage::delete('public/bap/'.$baps->file);
        $file = $request->file('file');
        $file->storeAs('public/bap', $file->hashName());

        $baps->update([
            'no_dokumen' => $request->no_dokumen,
            'judul' => $request->judul,
            'file' => $file->hashName(),
        ]);
        $baps->save();
        return redirect()->route('dataBap.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $baps = tb_bap::find($id);
        Storage::delete('public/bap/'.$baps->file);
        $baps->delete();
        return redirect()->route('dataBap.index');
    }
}
