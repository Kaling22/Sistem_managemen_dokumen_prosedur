<?php

namespace App\Http\Controllers\SHE;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SHE\tb_sp_she;
use Illuminate\Support\Facades\Storage;
class spSheController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sp = tb_sp_she::all();
        return view ('admin.Menus.DataSHE.SP.data-sp',compact('sp'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('admin.Menus.DataSHE.SP.create-sp');
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
            'file_sp' => 'required|mimes:pdf|max:20480',
        ]);

        $file = $request->file('file_sp');
        $file->storeAs('public/sp', $file->hashName());
        tb_sp_she::create([
            'no_dokumen' => $request->no_dokumen,
            'judul_sp' => $request->judul_sp,
            'file_sp' => $file->hashName(),
            'edisi' => $request->edisi,
            'revisi' => $request->revisi,
            'tanggal_efektif' => $request->tanggal_efektif,
        ]);


        return redirect()->route('dataSpShe.index');
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
        $sp = tb_sp_she::find($id);
        return view('admin.Menus.DataSHE.SP.edit-sp',compact('sp'));
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
        $sp = tb_sp_she::find($id);
        $this->validate($request, [
            'file_sp' => 'required|mimes:pdf',
        ]);
        Storage::delete('public/sp/'.$sp->file_sp);
        $file = $request->file('file_sp');
        $file->storeAs('public/sp', $file->hashName());

        $sp->update([
            'no_dokumen' => $request->no_dokumen,
            'judul_sp' => $request->judul_sp,
            'file_sp' => $file->hashName(),
            'edisi' => $request->edisi,
            'revisi' => $request->revisi,
            'tanggal_efektif' => $request->tanggal_efektif,
        ]);
        $sp->save();
        return redirect()->route('dataSpShe.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = tb_sp_she::find($id);
        Storage::delete('public/sp/'.$delete->file_sp);
        $delete->delete();
        return redirect()->route('dataSpShe.index');
    }
}
