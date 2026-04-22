<?php

namespace App\Http\Controllers\ICTMD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ICTMD\tb_sop_ictmd;
use Illuminate\Support\Facades\Storage;
use App\Models\DokumenRevisi\sop_revisi;

class sopIctmdController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sop = tb_sop_ictmd::where('status_doc', 'active')->get();
        return view ('admin.Menus.DataICTMD.SOP.data-sop',compact('sop'));
    }

    public function indexInactive()
    {
        $sopTidakAktif = tb_sop_ictmd::where('status_doc', 'Inactive')->get();
        return view ('admin.Menus.DataICTMD.SOP.data-sop-tidak-aktif',compact('sopTidakAktif'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
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
        $sopRevisi = tb_sop_ictmd::findOrFail($id);
        return view('admin.Menus.DokumenRevisi.SOP.revisi-sop', compact('sopRevisi'));
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
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = tb_sop_ictmd::find($id);
        Storage::delete('public/sop/'.$delete->file_sop);
        $delete->delete();
        return redirect()->route('dataSopIctmd.index');
    }
}
