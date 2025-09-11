<?php

namespace App\Http\Controllers\Plant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plant\tb_sop_plant;
use Illuminate\Support\Facades\Storage;
class sopPlantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sop = tb_sop_plant::all();
        return view ('admin.Menus.DataPlant.SOP.data-sop',compact('sop'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('admin.Menus.DataPlant.SOP.create-sop');
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
            'file_sop' => 'required|mimes:pdf|max:20480',
        ]);

        $file = $request->file('file_sop');
        $file->storeAs('public/sop', $file->hashName());
        tb_sop_plant::create([
            'no_dokumen' => $request->no_dokumen,
            'judul_sop' => $request->judul_sop,
            'file_sop' => $file->hashName(),
            'edisi' => $request->edisi,
            'revisi' => $request->revisi,
            'tanggal_efektif' => $request->tanggal_efektif,
        ]);


        return redirect()->route('dataSopPlant.index');
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
        $sop = tb_sop_plant::find($id);
        return view('admin.Menus.DataPlant.SOP.edit-sop',compact('sop'));
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
        $sop = tb_sop_plant::find($id);
        $this->validate($request, [
            'file_sop' => 'required|mimes:pdf',
        ]);
        Storage::delete('public/sop/'.$sop->file_sop);
        $file = $request->file('file_sop');
        $file->storeAs('public/sop', $file->hashName());
        
        $sop->update([
            'no_dokumen' => $request->no_dokumen,
            'judul_sop' => $request->judul_sop,
            'file_sop' => $file->hashName(),
            'edisi' => $request->edisi,
            'revisi' => $request->revisi,
            'tanggal_efektif' => $request->tanggal_efektif,
        ]);
        $sop->save();
        return redirect()->route('dataSopPlant.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = tb_sop_plant::find($id);
        Storage::delete('public/sop/'.$delete->file_sop);
        $delete->delete();
        return redirect()->route('dataSopPlant.index');
    }
}
