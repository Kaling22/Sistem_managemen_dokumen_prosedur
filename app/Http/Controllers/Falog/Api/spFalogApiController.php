<?php

namespace App\Http\Controllers\Falog\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Falog\tb_sp_falog;
class spFalogApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data_sp_falog = tb_sp_falog::all();
        return response()->json([
            'status' => 'success ',
            'message' => 'showing all sp falog',
            'data' => $data_sp_falog
        ], 200);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            $sp_Falog_file = tb_sp_falog::findOrFail($id);
            return response()->json([
                'success' => true,
                'message' => 'showing data SP FALOG',
                'data' => $sp_Falog_file,
            ], 200);
        }
        catch(\Exception $fail){
            return response()->json([
                'success' => false,
                'message' => 'data not found',
                'data'=> $fail->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
