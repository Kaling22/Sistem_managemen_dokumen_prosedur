<?php

namespace App\Http\Controllers\PLANT;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plant\tb_px_plant;
use Illuminate\Support\Facades\Storage;
class pxPlantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $px = tb_px_plant::all();
        return view ('admin.Menus.DataPlant.PROSEDUR EXTERNAL.data-px',compact('px'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('admin.Menus.DataPlant.PROSEDUR EXTERNAL.create-px');
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
            'file_px' => 'required|mimes:pdf|max:20480',
        ]);

        $file = $request->file('file_px');
        $file->storeAs('public/px', $file->hashName());
        tb_px_plant::create([
            'no_dokumen' => $request->no_dokumen,
            'judul_px' => $request->judul_px,
            'file_px' => $file->hashName(),
            'edisi' => $request->edisi,
            'revisi' => $request->revisi,
            'tanggal_efektif' => $request->tanggal_efektif,
        ]);


        return redirect()->route('dataPxPlant.index');
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
        $px = tb_px_plant::find($id);
        return view('admin.Menus.DataPlant.PROSEDUR EXTERNAL.edit-px',compact('px'));
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
        $px = tb_px_plant::find($id);
        $this->validate($request, [
            'file_px' => 'required|mimes:pdf',
        ]);
        Storage::delete('public/px/'.$px->file_px);
        $file = $request->file('file_px');
        $file->storeAs('public/px', $file->hashName());

        $px->update([
            'no_dokumen' => $request->no_dokumen,
            'judul_px' => $request->judul_px,
            'file_px' => $file->hashName(),
            'edisi' => $request->edisi,
            'revisi' => $request->revisi,
            'tanggal_efektif' => $request->tanggal_efektif,
        ]);
        $px->save();
        return redirect()->route('dataPxPlant.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = tb_px_plant::find($id);
        Storage::delete('public/px/'.$delete->file_px);
        $delete->delete();
        return redirect()->route('dataPxPlant.index');
    }
}
