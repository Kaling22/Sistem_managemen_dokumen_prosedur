<?php

namespace App\Http\Controllers\Engineering\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Engineering\tb_sp_engineering;
class spEngineeringApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data_sp_engineering = tb_sp_engineering::all();
        return response()->json([
            'status' => 'success ',
            'message' => 'showing all sp engineering',
            'data' => $data_sp_engineering
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
            $sp_Engineering_file = tb_sp_engineering::findOrFail($id);
            return response()->json([
                'success' => true,
                'message' => 'showing data sp ENGINEERING',
                'data' => $sp_Engineering_file,
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
