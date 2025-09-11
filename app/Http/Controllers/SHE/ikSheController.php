<?php

namespace App\Http\Controllers\SHE;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SHE\tb_ik_she;
use Illuminate\Support\Facades\Storage;
class ikSheController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ik = tb_ik_she::all();
        return view ('admin.Menus.DataSHE.IK.data-ik',compact('ik'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('admin.Menus.DataSHE.IK.create-ik');
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
            'file_ik' => 'required|mimes:pdf|max:20480',
        ]);

        $file = $request->file('file_ik');
        $file->storeAs('public/ik', $file->hashName());
        tb_ik_she::create([
            'no_dokumen' => $request->no_dokumen,
            'judul_ik' => $request->judul_ik,
            'file_ik' => $file->hashName(),
            'edisi' => $request->edisi,
            'revisi' => $request->revisi,
            'tanggal_efektif' => $request->tanggal_efektif,
        ]);


        return redirect()->route('dataIkShe.index');
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
        $ik = tb_ik_she::find($id);
        return view('admin.Menus.DataSHE.IK.edit-ik',compact('ik'));
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
        $ik = tb_ik_she::find($id);
        $this->validate($request, [
            'file_ik' => 'required|mimes:pdf',
        ]);
        Storage::delete('public/ik/'.$ik->file_ik);
        $file = $request->file('file_ik');
        $file->storeAs('public/ik', $file->hashName());

        $ik->update([
            'no_dokumen' => $request->no_dokumen,
            'judul_ik' => $request->judul_ik,
            'file_ik' => $file->hashName(),
            'edisi' => $request->edisi,
            'revisi' => $request->revisi,
            'tanggal_efektif' => $request->tanggal_efektif,
        ]);
        $ik->save();
        return redirect()->route('dataIkShe.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = tb_ik_she::find($id);
        Storage::delete('public/ik/'.$delete->file_ik);
        $delete->delete();
        return redirect()->route('dataIkShe.index');
    }
}
