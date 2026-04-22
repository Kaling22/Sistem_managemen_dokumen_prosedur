<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\report_doc;
use Illuminate\Support\Facades\Auth;
class reportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        // Jika admin (role 0) lihat semua
        if ($user->role == 0) {
            $report = report_doc::orderBy('created_at', 'desc')->get();
        } else {
            // 👤 User biasa hanya lihat departemennya
            $report = report_doc::where('departemen', $user->departemen)
                ->orderBy('created_at', 'desc')
                ->get();
        }
    
        return view('admin.Menus.Report.data-report', compact('report'));
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
        $report = report_doc::find($id);
        return view('admin.Menus.Report.edit-report',compact('report'));
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
        $report = report_doc::find($id);
        $report->update([
            'doc_number' => $request->doc_number,
            'doc_name' => $request->doc_name,
            'name' => $request->name,
            'nrp' => $request->nrp,
            'isi_report' => $request->isi_report,
            'feedback' => $request->feedback,
            'status' => $request->status,
        ]);
        $report->save();
        return redirect()->route('dataReportDoc.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = report_doc::find($id);
        $delete->delete();
        return redirect()->route('dataReportDoc.index');
    }
}
