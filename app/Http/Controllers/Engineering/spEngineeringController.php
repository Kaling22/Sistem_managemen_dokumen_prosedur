<?php

namespace App\Http\Controllers\Engineering;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Engineering\tb_sp_engineering;
use Illuminate\Support\Facades\Storage;
class spEngineeringController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sp = tb_sp_engineering::all();
        return view ('admin.Menus.DataEngineering.SP.data-sp',compact('sp'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('admin.Menus.DataEngineering.SP.create-sp');
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
        tb_sp_engineering::create([
            'no_dokumen' => $request->no_dokumen,
            'judul_sp' => $request->judul_sp,
            'file_sp' => $file->hashName(),
            'edisi' => $request->edisi,
            'revisi' => $request->revisi,
            'tanggal_efektif' => $request->tanggal_efektif,
        ]);


        return redirect()->route('dataSpEngineering.index');
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
        $sp = tb_sp_engineering::find($id);
        return view('admin.Menus.DataEngineering.SP.edit-sp',compact('sp'));
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
        $sp = tb_sp_engineering::find($id);
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
        return redirect()->route('dataSpEngineering.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = tb_sp_engineering::find($id);
        Storage::delete('public/sp/'.$delete->file_sp);
        $delete->delete();
        return redirect()->route('dataSpEngineering.index');
    }
}
