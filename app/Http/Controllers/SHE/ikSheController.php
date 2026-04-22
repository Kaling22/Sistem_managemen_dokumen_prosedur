<?php

namespace App\Http\Controllers\SHE;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SHE\tb_ik_she;
use Illuminate\Support\Facades\Storage;
class ikSheController extends Controller
{
   public function indexInactive()
    {
        $ikTidakAktif = tb_ik_she::where('status_doc', 'Inactive')->get();
        return view ('admin.Menus.DataSHE.IK.data-ik-tidak-aktif',compact('ikTidakAktif'));
    }
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ikRevisi = tb_ik_she::findOrFail($id);
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
        $delete = tb_ik_she::find($id);
        Storage::delete('public/ik/'.$delete->file_ik);
        $delete->delete();
        return redirect()->route('dataIkShe.index');
    }
}
