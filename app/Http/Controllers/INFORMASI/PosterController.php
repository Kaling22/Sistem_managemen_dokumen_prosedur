<?php

namespace App\Http\Controllers\INFORMASI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\INFORMASI\tb_poster;
use Illuminate\Support\Facades\Storage;
class PosterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posters = tb_poster::all();
        return view ('admin.Menus.DataInformasi.Poster.data-poster', compact('posters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('admin.Menus.DataInformasi.Poster.create-poster');
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
            'file' => 'required|mimes:png,jpg,jpeg|max:20480',
        ]);

        $file = $request->file('file');
        $file->storeAs('public/poster', $file->hashName());
        tb_poster::create([
            'no_dokumen' => $request->no_dokumen,
            'judul' => $request->judul,
            'file' => $file->hashName(),

        ]);
        return redirect()->route('dataPoster.index');
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
        $posters = tb_poster::find($id);
        return view('admin.Menus.DataInformasi.Poster.edit-poster',compact('posters'));
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
        $posters = tb_poster::find($id);
        $this->validate($request, [
            'file' => 'required|mimes:png,jpg,jpeg|max:20480',
        ]);
        Storage::delete('public/poster/'.$posters->file);
        $file = $request->file('file');
        $file->storeAs('public/poster', $file->hashName());

        $posters->update([
            'no_dokumen' => $request->no_dokumen,
            'judul' => $request->judul,
            'file' => $file->hashName(),
        ]);
        $posters->save();
        return redirect()->route('dataPoster.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $posters = tb_poster::find($id);
        Storage::delete('public/poster/'.$posters->file);
        $posters->delete();
        return redirect()->route('dataPoster.index');
    }
}
