<?php

namespace App\Http\Controllers\ICTMD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ICTMD\tb_fk_ictmd;
use Illuminate\Support\Facades\Storage;
class fkIctmdController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fk = tb_fk_ictmd::all();
        return view ('admin.Menus.DataICTMD.FORMULIR KERJA.data-fk',compact('fk'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('admin.Menus.DataICTMD.FORMULIR KERJA.create-fk');
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
        tb_fk_ictmd::create([
            'no_dokumen' => $request->no_dokumen,
            'judul_fk' => $request->judul_fk,
            'file_fk' => $file->hashName(),
            'edisi' => $request->edisi,
            'revisi' => $request->revisi,
            'tanggal_efektif' => $request->tanggal_efektif,
        ]);


        return redirect()->route('dataFkIctmd.index');
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
        $fk = tb_fk_ictmd::find($id);
        return view('admin.Menus.DataICTMD.FORMULIR KERJA.edit-fk',compact('fk'));
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
        $fk = tb_fk_ictmd::find($id);
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
        return redirect()->route('dataFkIctmd.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = tb_fk_ictmd::find($id);
        Storage::delete('public/fk/'.$delete->file_fk);
        $delete->delete();
        return redirect()->route('dataFkIctmd.index');
    }
}
