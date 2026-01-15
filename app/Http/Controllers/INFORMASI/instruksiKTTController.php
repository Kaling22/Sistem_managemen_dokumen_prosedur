<?php

namespace App\Http\Controllers\INFORMASI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\INFORMASI\tb_instruksiktt;
use Illuminate\Support\Facades\Storage;
class instruksiKTTController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $instruksiKTTs = tb_instruksiktt::all();
        return view ('admin.Menus.DataInformasi.InstruksiKTT.data-instruksiKTT', compact('instruksiKTTs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('admin.Menus.DataInformasi.InstruksiKTT.create-instruksiKTT');
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
        $file->storeAs('public/instruksi_ktt', $file->hashName());
        tb_instruksiktt::create([
            'no_dokumen' => $request->no_dokumen,
            'judul' => $request->judul,
            'file' => $file->hashName(),
            'edisi' => $request->edisi,
            'revisi' => $request->revisi,
            'tanggal_efektif' => $request->tanggal_efektif,
        ]);
        return redirect()->route('dataInstruksiKtt.index');
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
        $instruksiKTT = tb_instruksiktt::find($id);
        return view('admin.Menus.DataInformasi.InstruksiKTT.edit-instruksiKTT',compact('instruksiKTT'));
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
        $instruksiKTT = tb_instruksiktt::find($id);
        $this->validate($request, [
            'file' => 'required|mimes:pdf',
        ]);
        Storage::delete('public/instruksi_ktt/'.$instruksiKTT->file);
        $file = $request->file('file');
        $file->storeAs('public/instruksi_ktt', $file->hashName());

        $instruksiKTT->update([
            'no_dokumen' => $request->no_dokumen,
            'judul' => $request->judul,
            'file' => $file->hashName(),
            'edisi' => $request->edisi,
            'revisi' => $request->revisi,
            'tanggal_efektif' => $request->tanggal_efektif,
        ]);
        $instruksiKTT->save();
        return redirect()->route('dataInstruksiKtt.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = tb_instruksiktt::find($id);
        Storage::delete('public/instruksi_ktt/'.$delete->file);
        $delete->delete();
        return redirect()->route('dataInstruksiKtt.index');
    }
}
