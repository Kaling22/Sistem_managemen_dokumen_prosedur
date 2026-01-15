<?php

namespace App\Http\Controllers\INFORMASI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\INFORMASI\tb_mocmprp;
use Illuminate\Support\Facades\Storage;
class mocmprpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mocmprp = tb_mocmprp::all();
        return view ('admin.Menus.DataInformasi.MocMprp.data-mocmprp', compact('mocmprp'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('admin.Menus.DataInformasi.MocMprp.create-mocmprp');
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
            'file' => 'required|mimes:pdf|max:20480',
        ]);

        $file = $request->file('file');
        $file->storeAs('public/mocmprp', $file->hashName());
        tb_mocmprp::create([
            'no_dokumen' => $request->no_dokumen,
            'judul' => $request->judul,
            'file' => $file->hashName(),

        ]);
        return redirect()->route('dataMocMprp.index');
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
        $mocmprp = tb_mocmprp::find($id);
        return view('admin.Menus.DataInformasi.MocMprp.edit-mocmprp',compact('mocmprp'));
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
        $mocmprp = tb_mocmprp::find($id);
        $this->validate($request, [
            'file' => 'required|mimes:pdf|max:20480',
        ]);
        Storage::delete('public/mocmprp/'.$mocmprp->file);
        $file = $request->file('file');
        $file->storeAs('public/mocmprp', $file->hashName());

        $mocmprp->update([
            'no_dokumen' => $request->no_dokumen,
            'judul' => $request->judul,
            'file' => $file->hashName(),
        ]);
        $mocmprp->save();
        return redirect()->route('dataMocMprp.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $mocmprp = tb_mocmprp::find($id);
        Storage::delete('public/mocmprp/'.$mocmprp->file);
        $mocmprp->delete();
        return redirect()->route('dataMocMprp.index');
    }
}
