<?php

namespace App\Http\Controllers\INFORMASI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\INFORMASI\tb_kebijakan;
use Illuminate\Support\Facades\Storage;
class kebijakanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kebijakans = tb_kebijakan::all();
        return view ('admin.Menus.DataInformasi.Kebijakan.data-kebijakan', compact('kebijakans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('admin.Menus.DataInformasi.Kebijakan.create-kebijakan');
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
        $file->storeAs('public/kebijakan', $file->hashName());
        tb_kebijakan::create([
            'no_dokumen' => $request->no_dokumen,
            'judul' => $request->judul,
            'file' => $file->hashName(),
            'edisi' => $request->edisi,
            'revisi' => $request->revisi,
        ]);
        return redirect()->route('dataKebijakan.index');
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
        $kebijakan = tb_kebijakan::find($id);
        return view('admin.Menus.DataInformasi.Kebijakan.edit-kebijakan',compact('kebijakan'));
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
        $kebijakan = tb_kebijakan::find($id);
        $this->validate($request, [
            'file_kebijakan' => 'required|mimes:pdf|max:20480',
        ]);
        Storage::delete('public/kebijakan/'.$kebijakan->file_kebijakan);
        $file = $request->file('file_kebijakan');
        $file->storeAs('public/kebijakan', $file->hashName());

        $kebijakan->update([
            'no_dokumen' => $request->no_dokumen,
            'judul' => $request->judul_kebijakan,
            'file' => $file->hashName(),
            'edisi' => $request->edisi,
            'revisi' => $request->revisi,
        ]);
        $kebijakan->save();
        return redirect()->route('dataKebijakan.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = tb_kebijakan::find($id);
        Storage::delete('public/kebijakan/'.$delete->file);
        $delete->delete();
        return redirect()->route('dataKebijakan.index');
    }
}
