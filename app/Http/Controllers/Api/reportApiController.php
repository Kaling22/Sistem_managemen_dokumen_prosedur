<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\report_doc;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class reportApiController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'showing all report produksi',
            'data' => report_doc::all()
        ], 200);
    }

    public function store(Request $request)
{
    try {
        // VALIDASI
        $validated = $request->validate([
            'doc_number' => 'required|string',
            'doc_name'   => 'required|string',
            'name'       => 'required|string',
            'nrp'        => 'required|string',
            'departemen' => 'required|string', // konsisten
            'isi_report' => 'required|string',
        ]);

        // ===============================
        // PREFIX BERDASARKAN DEPARTEMEN
        // ===============================
        $prefixMap = [
            'Produksi'    => 'PRD',
            'ICTMD'         => 'ICTMD',
            'HCGA'        => 'HCG',
            'Engineering' => 'ENG',
            'SHE' => 'SHE',
            'FALOG'       => 'FALOG',
            'Plant'       => 'PLT',
        ];

        // FIX: ambil dari key yang benar
        $departemen = $validated['departemen'];
        $prefix = $prefixMap[$departemen] ?? 'GEN';

        // ===============================
        // TANGGAL
        // ===============================
        $today = Carbon::now()->format('Ymd');

        // ===============================
        // LAST REPORT (PER HARI + DEPARTEMEN)
        // ===============================
        $lastReport = report_prd::whereDate('created_at', Carbon::today())
            ->where('report_number', 'like', "$prefix-$today-%")
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = $lastReport
            ? str_pad(((int) substr($lastReport->report_number, -3)) + 1, 3, '0', STR_PAD_LEFT)
            : '001';

        $reportNumber = "$prefix-$today-$nextNumber";

        // ===============================
        // SIMPAN
        // ===============================
        $report = report_doc::create([
            'doc_number'    => $validated['doc_number'],
            'doc_name'      => $validated['doc_name'],
            'name'          => $validated['name'],
            'nrp'           => $validated['nrp'],
            'departemen'    => $departemen, // ✅ WAJIB
            'isi_report'    => $validated['isi_report'],
            'feedback'      => '-',
            'status'        => 'Open',
            'report_number' => $reportNumber,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Report berhasil dibuat',
            'data'    => $report
        ], 201);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'errors'  => $e->errors(),
        ], 422);

    } catch (\Throwable $e) {
        Log::error('Create Report Error', [
            'message' => $e->getMessage(),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan pada server',
        ], 500);
    }
}

    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //show report produksi by nrp
        $report = report_doc::where('nrp', $id)->get();
        return response()->json([
            'status' => 'success ',
            'message' => 'showing report produksi by nrp '.$id,
            'data' => $report
        ], 200);
    }
}
