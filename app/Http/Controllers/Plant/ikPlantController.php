<?php

namespace App\Http\Controllers\Plant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plant\tb_ik_plant;
use Illuminate\Support\Facades\Storage;
class ikPlantController extends Controller
{
    public function indexInactive()
    {
        $ikTidakAktif = tb_ik_plant::where('status_doc', 'Inactive')->get();
        return view ('admin.Menus.DataPlant.IK.data-ik-tidak-aktif',compact('ikTidakAktif'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ik = tb_ik_plant::all();
        return view ('admin.Menus.DataPlant.IK.data-ik',compact('ik'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ikRevisi = tb_ik_plant::findOrFail($id);
        return view('admin.Menus.DataPlant.IK.revisi-ik', compact('ikRevisi'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = tb_ik_plant::find($id);
        Storage::delete('public/ik/'.$delete->file_ik);
        $delete->delete();
        return redirect()->route('dataIkPlant.index');
    }
}
