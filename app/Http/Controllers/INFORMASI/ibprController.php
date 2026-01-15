<?php

namespace App\Http\Controllers\INFORMASI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\INFORMASI\tb_ibpr;
use Illuminate\Support\Facades\Storage;
class ibprController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ibprs = tb_ibpr::all();
        return view ('admin.Menus.DataInformasi.Ibpr.data-ibpr', compact('ibprs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('admin.Menus.DataInformasi.Ibpr.create-ibpr');
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
        $file->storeAs('public/ibpr', $file->hashName());
        tb_ibpr::create([
            'no_dokumen' => $request->no_dokumen,
            'judul' => $request->judul,
            'file' => $file->hashName(),
            'edisi' => $request->edisi,
            'revisi' => $request->revisi,
            'tanggal_efektif' => $request->tanggal_efektif,
        ]);
        return redirect()->route('dataIbpr.index');
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
        $ibpr = tb_ibpr::find($id);
        return view('admin.Menus.DataInformasi.Ibpr.edit-ibpr',compact('ibpr'));
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
        $ibpr = tb_ibpr::find($id);
        $this->validate($request, [
            'file' => 'required|mimes:pdf|max:20480',
        ]);
        Storage::delete('public/ibpr/'.$ibpr->file);
        $file = $request->file('file');
        $file->storeAs('public/ibpr', $file->hashName());

        $ibpr->update([
            'no_dokumen' => $request->no_dokumen,
            'judul' => $request->judul,
            'file' => $file->hashName(),
            'edisi' => $request->edisi,
            'revisi' => $request->revisi,
            'tanggal_efektif' => $request->tanggal_efektif,
        ]);
        $ibpr->save();
        return redirect()->route('dataIbpr.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = tb_ibpr::find($id);
        Storage::delete('public/ibpr/'.$delete->file);
        $delete->delete();
        return redirect()->route('dataIbpr.index');
    }
}
