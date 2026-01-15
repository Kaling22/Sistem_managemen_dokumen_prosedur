<?php

namespace App\Http\Controllers\INFORMASI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\INFORMASI\tb_sertifikatsio;
use Illuminate\Support\Facades\Storage;
class sertifikatSIOController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sertifikatsio = tb_sertifikatsio::all();
        return view ('admin.Menus.DataInformasi.SertifikatSIO.data-sertifikatsio', compact('sertifikatsio'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('admin.Menus.DataInformasi.SertifikatSIO.create-sertifikatsio');
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
        $file->storeAs('public/sertifikatsio', $file->hashName());
        tb_sertifikatsio::create([
            'no_dokumen' => $request->no_dokumen,
            'judul' => $request->judul,
            'file' => $file->hashName(),

        ]);
        return redirect()->route('dataSertifikatSIO.index');
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
        $sertifikatsio = tb_sertifikatsio::find($id);
        return view('admin.Menus.DataInformasi.SertifikatSIO.edit-sertifikatsio',compact('sertifikatsio'));
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
        $sertifikatsio = tb_sertifikatsio::find($id);
        $this->validate($request, [
            'file' => 'required|mimes:pdf|max:20480',
        ]);
        Storage::delete('public/sertifikatsio/'.$sertifikatsio->file);
        $file = $request->file('file');
        $file->storeAs('public/sertifikatsio', $file->hashName());

        $sertifikatsio->update([
            'no_dokumen' => $request->no_dokumen,
            'judul' => $request->judul,
            'file' => $file->hashName(),
        ]);
        $sertifikatsio->save();
        return redirect()->route('dataSertifikatSIO.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sertifikatsio = tb_sertifikatsio::find($id);
        Storage::delete('public/sertifikatsio/'.$sertifikatsio->file);
        $sertifikatsio->delete();
        return redirect()->route('dataSertifikatSIO.index');
    }
}
