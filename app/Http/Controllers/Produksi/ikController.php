<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produksi\tb_ik_produksi;
use Illuminate\Support\Facades\Storage;
class ikController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ik = tb_ik_produksi::all();
        return view ('admin.Menus.DataProduksi.IK.data-ik',compact('ik'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('admin.Menus.DataProduksi.IK.create-ik');
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
        tb_ik_produksi::create([
            'no_dokumen' => $request->no_dokumen,
            'judul_ik' => $request->judul_ik,
            'file_ik' => $file->hashName(),
            'edisi' => $request->edisi,
            'revisi' => $request->revisi,
            'tanggal_efektif' => $request->tanggal_efektif,
        ]);


        return redirect()->route('dataIkProduksi.index');
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
        $ik = tb_ik_produksi::find($id);
        return view('admin.Menus.DataProduksi.IK.edit-ik',compact('ik'));
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
        $ik = tb_ik_produksi::find($id);
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
        return redirect()->route('dataIkProduksi.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = tb_sp_produksi::find($id);
        Storage::delete('public/ik/'.$delete->file_ik);
        $delete->delete();
        return redirect()->route('dataIkProduksi.index');
    }
}
