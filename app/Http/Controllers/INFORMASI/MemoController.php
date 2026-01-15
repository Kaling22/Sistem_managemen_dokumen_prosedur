<?php

namespace App\Http\Controllers\INFORMASI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\INFORMASI\tb_memo;
use Illuminate\Support\Facades\Storage;
class MemoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $memos = tb_memo::all();
        return view ('admin.Menus.DataInformasi.Memo.data-memo', compact('memos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('admin.Menus.DataInformasi.Memo.create-memo');
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
        $file->storeAs('public/memo', $file->hashName());
        tb_memo::create([
            'no_dokumen' => $request->no_dokumen,
            'judul' => $request->judul,
            'file' => $file->hashName(),

        ]);
        return redirect()->route('dataMemo.index');
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
        $memos = tb_memo::find($id);
        return view('admin.Menus.DataInformasi.Memo.edit-memo',compact('memos'));
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
        $memos = tb_memo::find($id);
        $this->validate($request, [
            'file' => 'required|mimes:pdf|max:20480',
        ]);
        Storage::delete('public/memo/'.$memos->file);
        $file = $request->file('file');
        $file->storeAs('public/memo', $file->hashName());

        $memos->update([
            'no_dokumen' => $request->no_dokumen,
            'judul' => $request->judul,
            'file' => $file->hashName(),
        ]);
        $memos->save();
        return redirect()->route('dataMemo.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $memos = tb_memo::find($id);
        Storage::delete('public/memo/'.$memos->file);
        $memos->delete();
        return redirect()->route('dataMemo.index');
    }
}
