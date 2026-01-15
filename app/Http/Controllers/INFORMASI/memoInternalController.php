<?php

namespace App\Http\Controllers\INFORMASI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\INFORMASI\tb_memoInternal;
use Illuminate\Support\Facades\Storage;
class memoInternalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $memos = tb_memoInternal::all();
        return view ('admin.Menus.DataInformasi.Memo Internal.data-memoInternal', compact('memos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('admin.Menus.DataInformasi.Memo Internal.create-memoInternal');
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
        $file->storeAs('public/memoInternal', $file->hashName());
        tb_memoInternal::create([
            'no_dokumen' => $request->no_dokumen,
            'judul' => $request->judul,
            'file' => $file->hashName(),

        ]);
        return redirect()->route('dataMemoInternal.index');
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
        $memos = tb_memoInternal::find($id);
        return view('admin.Menus.DataInformasi.Memo Internal.edit-memoInternal',compact('memos'));
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
        $memos = tb_memoInternal::find($id);
        $this->validate($request, [
            'file' => 'required|mimes:pdf|max:20480',
        ]);
        Storage::delete('public/memoInternal/'.$memos->file);
        $file = $request->file('file');
        $file->storeAs('public/memoInternal', $file->hashName());

        $memos->update([
            'no_dokumen' => $request->no_dokumen,
            'judul' => $request->judul,
            'file' => $file->hashName(),
        ]);
        $memos->save();
        return redirect()->route('dataMemoInternal.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $memos = tb_memoInternal::find($id);
        Storage::delete('public/memoInternal/'.$memos->file);
        $memos->delete();
        return redirect()->route('dataMemoInternal.index');
    }
}
