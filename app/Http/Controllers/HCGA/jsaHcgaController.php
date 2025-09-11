<?php

namespace App\Http\Controllers\HCGA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HCGA\tb_jsa_hcga;
use Illuminate\Support\Facades\Storage;
class jsaHcgaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jsa = tb_jsa_hcga::all();
        return view ('admin.Menus.DataHCGA.JSA.data-jsa',compact('jsa'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('admin.Menus.DataHCGA.JSA.create-jsa');
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
            'file_jsa' => 'required|mimes:pdf|max:20480',
        ]);

        $file = $request->file('file_jsa');
        $file->storeAs('public/jsa', $file->hashName());
        tb_jsa_hcga::create([
            'no_dokumen' => $request->no_dokumen,
            'judul_jsa' => $request->judul_jsa,
            'file_jsa' => $file->hashName(),
            'edisi' => $request->edisi,
            'revisi' => $request->revisi,
            'tanggal_efektif' => $request->tanggal_efektif,
        ]);


        return redirect()->route('dataJsaHcga.index');
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
        $jsa = tb_jsa_hcga::find($id);
        return view('admin.Menus.DataHCGA.JSA.edit-jsa',compact('jsa'));
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
        $jsa = tb_jsa_hcga::find($id);
        $this->validate($request, [
            'file_jsa' => 'required|mimes:pdf',
        ]);
        Storage::delete('public/jsa/'.$jsa->file_jsa);
        $file = $request->file('file_jsa');
        $file->storeAs('public/jsa', $file->hashName());

        $jsa->update([
            'no_dokumen' => $request->no_dokumen,
            'judul_jsa' => $request->judul_jsa,
            'file_jsa' => $file->hashName(),
            'edisi' => $request->edisi,
            'revisi' => $request->revisi,
            'tanggal_efektif' => $request->tanggal_efektif,
        ]);
        $jsa->save();
        return redirect()->route('dataJsaHcga.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = tb_jsa_hcga::find($id);
        Storage::delete('public/jsa/'.$delete->file_jsa);
        $delete->delete();
        return redirect()->route('dataJsaHcga.index');
    }
}
