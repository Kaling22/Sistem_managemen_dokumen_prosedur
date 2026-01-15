<?php

namespace App\Http\Controllers\SHE;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SHE\tb_fk_she;
use Illuminate\Support\Facades\Storage;
class fkSheController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fk = tb_fk_she::all();
        return view ('admin.Menus.DataSHE.FORMULIR KERJA.data-fk',compact('fk'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('admin.Menus.DataSHE.FORMULIR KERJA.create-fk');
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
            'file_fk' => 'required|mimes:pdf|max:20480',
        ]);

        $file = $request->file('file_fk');
        $file->storeAs('public/fk', $file->hashName());
        tb_fk_she::create([
            'no_dokumen' => $request->no_dokumen,
            'judul_fk' => $request->judul_fk,
            'file_fk' => $file->hashName(),
            'edisi' => $request->edisi,
            'revisi' => $request->revisi,
            'tanggal_efektif' => $request->tanggal_efektif,
        ]);


        return redirect()->route('dataFkShe.index');
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
        $fk = tb_fk_she::find($id);
        return view('admin.Menus.DataSHE.FORMULIR KERJA.edit-fk',compact('fk'));
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
        $fk = tb_fk_she::find($id);
        $this->validate($request, [
            'file_fk' => 'required|mimes:pdf',
        ]);
        Storage::delete('public/fk/'.$fk->file_fk);
        $file = $request->file('file_fk');
        $file->storeAs('public/fk', $file->hashName());

        $fk->update([
            'no_dokumen' => $request->no_dokumen,
            'judul_fk' => $request->judul_fk,
            'file_fk' => $file->hashName(),
            'edisi' => $request->edisi,
            'revisi' => $request->revisi,
            'tanggal_efektif' => $request->tanggal_efektif,
        ]);
        $fk->save();
        return redirect()->route('dataFkShe.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = tb_fk_she::find($id);
        Storage::delete('public/fk/'.$delete->file_fk);
        $delete->delete();
        return redirect()->route('dataFkShe.index');
    }
}
