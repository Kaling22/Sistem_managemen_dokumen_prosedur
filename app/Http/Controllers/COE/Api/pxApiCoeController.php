<?php

namespace App\Http\Controllers\COE\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\COE\tb_px_coe;
class pxApiCoeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data_px_coe = tb_px_coe::all();
        return response()->json([
            'status' => 'success',
            'message' => 'showing all px coe',
            'data' => $data_px_coe
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
            $px_coe_file = tb_px_coe::findOrFail($id);
            return response()->json([
                'success' => true,
                'message' => 'showing data PX coe',
                'data' => $px_coe_file,
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
