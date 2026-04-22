<?php

namespace App\Http\Controllers\Falog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Falog\tb_sp_falog;
use Illuminate\Support\Facades\Storage;
class spFalogController extends Controller
{
    public function indexInactive()
    {
        $spTidakAktif = tb_sp_falog::where('status_doc', 'Inactive')->get();
        return view ('admin.Menus.DataFalog.SP.data-sp-tidak-aktif',compact('spTidakAktif'));
    }

    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sp = tb_sp_falog::where('status_doc', 'Active')->get();
        return view ('admin.Menus.DataFalog.SP.data-sp',compact('sp'));
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
        $spRevisi = tb_sp_falog::findOrFail($id);
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
        $delete = tb_sp_falog::find($id);
        Storage::delete('public/sp/'.$delete->file_sp);
        $delete->delete();
        return redirect()->route('dataSpFalog.index');
    }
}
