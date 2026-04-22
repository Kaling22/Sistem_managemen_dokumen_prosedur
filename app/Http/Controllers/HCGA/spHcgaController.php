<?php

namespace App\Http\Controllers\HCGA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HCGA\tb_sp_hcga;
use Illuminate\Support\Facades\Storage;
class spHcgaController extends Controller
{
    public function indexInactive()
    {
        $spTidakAktif = tb_sp_hcga::where('status_doc', 'Inactive')->get();
        return view ('admin.Menus.DataHCGA.SP.data-sp-tidak-aktif',compact('spTidakAktif'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sp = tb_sp_hcga::where('status_doc', 'Active')->get();
        return view ('admin.Menus.DataHCGA.SP.data-sp',compact('sp'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
        $spRevisi = tb_sp_hcga::findOrFail($id);
        return view('admin.Menus.DokumenRevisi.SP.revisi-sp', compact('spRevisi'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = tb_sp_hcga::find($id);
        Storage::delete('public/sp/'.$delete->file_sp);
        $delete->delete();
        return redirect()->route('dataSpHcga.index');
    }
}
