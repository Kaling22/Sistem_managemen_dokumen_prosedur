<?php

namespace App\Http\Controllers\Engineering;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Engineering\tb_ik_engineering;
use Illuminate\Support\Facades\Storage;
class ikEngineeringController extends Controller
{
    public function indexInactive()
    {
        $ikTidakAktif = tb_ik_engineering::where('status_doc', 'Inactive')->get();
        return view ('admin.Menus.DataEngineering.IK.data-ik-tidak-aktif',compact('ikTidakAktif'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ik = tb_ik_engineering::all();
        return view ('admin.Menus.DataEngineering.IK.data-ik',compact('ik'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ikRevisi = tb_ik_engineering::findOrFail($id);
        return view('admin.Menus.DokumenRevisi.IK.revisi-ik', compact('ikRevisi'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = tb_ik_engineering::find($id);
        Storage::delete('public/ik/'.$delete->file_ik);
        $delete->delete();
        return redirect()->route('dataIkEngineering.index');
    }
}
