<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produksi\tb_linkproduksi;
use Illuminate\Support\Facades\Storage;
class linkproduksiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $link = tb_linkproduksi::all();
        return view ('admin.Menus.DataPRODUKSI.Link.data-link',compact('link'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('admin.Menus.DataPRODUKSI.Link.create-link');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        tb_linkproduksi::create([
            'judul' => $request->judul,
            'link' => $request->link,
            'views' => $request->views,
        ]);


        return redirect()->route('dataLinkProduksi.index');
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
        $link = tb_linkproduksi::find($id);
        return view('admin.Menus.DataPRODUKSI.Link.edit-link',compact('link'));
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
        $link = tb_linkproduksi::find($id);
        
        $link->update([
            'judul' => $request->judul,
            'link' => $request->link,
            'views' => $request->views,
        ]);
        $link->save();
        return redirect()->route('dataLinkProduksi.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = tb_linkproduksi::find($id);
        $delete->delete();
        return redirect()->route('dataLinkProduksi.index');
    }
}
