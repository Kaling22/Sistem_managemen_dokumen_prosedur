<?php

namespace App\Http\Controllers\Falog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Falog\tb_ik_falog;
use Illuminate\Support\Facades\Storage;
class ikFalogController extends Controller
{
    public function indexInactive()
    {
        $ikTidakAktif = tb_ik_falog::where('status_doc', 'Inactive')->get();
        return view ('admin.Menus.DataFalog.IK.data-ik-tidak-aktif',compact('ikTidakAktif'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ik = tb_ik_falog::all();
        return view ('admin.Menus.DataFalog.IK.data-ik',compact('ik'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ikRevisi = tb_ik_falog::findOrFail($id);
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
        $delete = tb_ik_falog::find($id);
        Storage::delete('public/ik/'.$delete->file_ik);
        $delete->delete();
        return redirect()->route('dataIkFalog.index');
    }
}
