<?php

namespace App\Http\Controllers\DokumenBaru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DokumenBaru\JSA\jsa_baru;
use App\Models\DokumenBaru\JSA\jsa_step_baru;
use App\Models\DokumenBaru\JSA\jsa_hazard_baru;
use App\Models\DokumenBaru\JSA\jsa_control_baru;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class tb_jsa_baru extends Controller
{
    public function index()
    {
        $jsaBaru = jsa_baru::orderBy('created_at', 'desc')->get();
        return view('admin.Menus.DokumenBaru.JSA.jsaBaru', compact('jsaBaru'));
    }

    public function create()
    {
        $year = date('Y');
        $count = jsa_baru::whereYear('created_at', $year)->count() + 1;
        $deptCode = Auth::user()->departemen ?? 'DEPT'; 
        $autoNumber = "PPA-ADRO-JSA-" . $deptCode . "-" . str_pad($count, 3, '0', STR_PAD_LEFT);

        return view('admin.Menus.DokumenBaru.JSA.create-jsaBaru', compact('autoNumber'));
    }

    /**
     * Fungsi Private untuk Generate PDF dan simpan ke Storage
     */
    private function generateJsaPdf($jsaId)
    {
        $jsa = jsa_baru::with(['steps.hazards.controls'])->findOrFail($jsaId);
        
        $data = [
            'jsa' => $jsa,
            'jabatanPembuat' => User::where('nama', $jsa->dibuat_oleh)->value('role') ?? '-',
            'jabatanReviewer' => User::where('nama', $jsa->direview_oleh)->value('role') ?? '-',
            'jabatanPenyetuju' => User::where('nama', $jsa->disetujui_oleh)->value('role') ?? '-',
        ];

        $pdf = Pdf::loadView('admin.Menus.Template.template_jsa', $data);
        $fileName = 'JSA_Pending_' . $jsaId . '.pdf';
        
        // Simpan fisik file
        Storage::put('public/jsa_pending/' . $fileName, $pdf->output());

        // Update nama file ke kolom 'file' (Jika kolom ini sudah kamu buat di migration)
        // $jsa->update(['file' => $fileName]);
    }

    public function store(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'no_dokumen' => 'required',
            'judul' => 'required',
            'lokasi_kerja' => 'required',
            'direview_oleh' => 'required',
            'disetujui_oleh' => 'required',
            'jsa' => 'required|array',
        ]);
        

        try {
            DB::beginTransaction();

            $jsa = new jsa_baru();
            // Sesuai Tabel: Kolom no_dokumen & no_jsa
            $jsa->no_dokumen = $request->no_dokumen;
            $jsa->no_jsa = $request->no_dokumen; 
            
            // Sesuai Tabel: Default Value & Date
            $jsa->revisi = '00';
            $jsa->tgl_terbit = now();
            $jsa->tgl_pembuatan = now();
            
            // Sesuai Tabel: Informasi Pekerjaan
            $jsa->nama_pekerjaan = $request->judul;
            $jsa->jenis_doc = 'JSA';
            $jsa->lokasi_kerja = $request->lokasi_kerja;
            $jsa->departemen = Auth::user()->departemen;
            $jsa->status_doc = 'Waiting Reviewer';
            $jsa->apd_wajib = $request->apd_wajib;
            $jsa->peralatan_pendukung = $request->peralatan_pendukung;

            // Sesuai Tabel: Approvals
            $jsa->dibuat_oleh = Auth::user()->nama;
            $jsa->direview_oleh = $request->direview_oleh;
            $jsa->disetujui_oleh = $request->disetujui_oleh;
            
            $jsa->save();
            
            // Simpan Looping JSA (Steps -> Hazards -> Controls)
            foreach ($request->jsa as $sKey => $stepData) {
                $step = new jsa_step_baru();
                $step->jsa_id = $jsa->id;
                $step->step_no = $sKey;
                $step->description = $stepData['langkah'];
                $step->save();

                if (isset($stepData['bahaya'])) {
                    foreach ($stepData['bahaya'] as $hKey => $hazardData) {
                        $hazard = new jsa_hazard_baru();
                        $hazard->jsa_step_id = $step->id;
                        $hazard->hazard_no = $sKey . "." . $hKey;
                        $hazard->hazard_description = $hazardData['teks'];
                        $hazard->save();

                        if (isset($hazardData['pengendalian'])) {
                            foreach ($hazardData['pengendalian'] as $cKey => $controlDesc) {
                                $control = new jsa_control_baru();
                                $control->jsa_hazard_id = $hazard->id;
                                $control->control_no = $sKey . "." . $hKey . "." . ($cKey + 1);
                                $control->control_description = $controlDesc;
                                $control->save();
                            }
                        }
                    }
                }
            }

            // BAGIAN PDF: Panggil fungsi generate
            $this->generateJsaPdf($jsa->id);
            //simpan nama file ke database
            $jsa->file = 'JSA_Pending_' . $jsa->id . '.pdf';
            $jsa->save();

            DB::commit();
            return redirect()->route('dataJsaBaru.index')->with('success', 'JSA Berhasil Dibuat.');

        } catch (\Exception $e) {
            DB::rollback();
    // Ini akan menghentikan program dan menunjukkan pesan error database yang asli
    dd($e->getMessage());
        }
    }

    public function edit($id)
    {
        $jsa = jsa_baru::with(['steps.hazards.controls'])->findOrFail($id);

        if ($jsa->direview_oleh_approve !== 'rejected') {
            return redirect()->route('dataJsaBaru.index')
                ->with('error', 'Hanya dokumen berstatus Rejected yang dapat diedit.');
        }

        return view('admin.Menus.DokumenBaru.JSA.edit-jsaBaru', compact('jsa'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'no_dokumen' => 'required',
            'judul' => 'required',
            'jsa' => 'required|array',
        ]);

        try {
            DB::beginTransaction();

            $jsa = jsa_baru::findOrFail($id);
            
            // 1. Update Data Utama
            $jsa->no_dokumen = $request->no_dokumen;
            $jsa->nama_pekerjaan = $request->judul;
            $jsa->lokasi_kerja = $request->lokasi_kerja;
            $jsa->apd_wajib = $request->apd_wajib;
            $jsa->peralatan_pendukung = $request->peralatan_pendukung;
            $jsa->direview_oleh_approve = null; // Reset status ke null agar direview ulang
            $jsa->save();

            // 2. Hapus detail lama untuk menghindari data ganda/sampah
            foreach ($jsa->steps as $step) {
                foreach ($step->hazards as $hazard) {
                    $hazard->controls()->delete();
                }
                $step->hazards()->delete();
            }
            $jsa->steps()->delete();

            // 3. Simpan data detail yang baru di-edit
            $this->storeJsaDetails($jsa->id, $request->jsa);

            // 4. Update PDF (File lama akan diganti dengan yang baru)
            $this->generateJsaPdf($jsa->id);
            $jsa->file = 'JSA_Pending_' . $jsa->id . '.pdf';
            $jsa->save();

            DB::commit();
            return redirect()->route('dataJsaBaru.index')->with('success', 'JSA berhasil diperbarui dan diajukan ulang.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal Update: ' . $e->getMessage());
        }
    }

    /**
     * Reusable function untuk menyimpan Steps, Hazards, dan Controls
     */
    private function storeJsaDetails($jsaId, $jsaData)
    {
        foreach ($jsaData as $sKey => $stepData) {
            $step = new jsa_step_baru();
            $step->jsa_id = $jsaId;
            $step->step_no = $sKey;
            $step->description = $stepData['langkah'];
            $step->save();

            if (isset($stepData['bahaya'])) {
                foreach ($stepData['bahaya'] as $hKey => $hazardData) {
                    $hazard = new jsa_hazard_baru();
                    $hazard->jsa_step_id = $step->id;
                    $hazard->hazard_no = $sKey . "." . $hKey;
                    $hazard->hazard_description = $hazardData['teks'];
                    $hazard->save();

                    if (isset($hazardData['pengendalian'])) {
                        foreach ($hazardData['pengendalian'] as $cKey => $controlDesc) {
                            $control = new jsa_control_baru();
                            $control->jsa_hazard_id = $hazard->id;
                            $control->control_no = $sKey . "." . $hKey . "." . ($cKey + 1);
                            $control->control_description = $controlDesc;
                            $control->save();
                        }
                    }
                }
            }
        }
    }

    public function review($id)
    {
        $jsa = jsa_baru::with(['steps.hazards.controls'])->findOrFail($id);
        $jabatanPembuat = User::where('nama', $jsa->dibuat_oleh)->value('role') ?? '-';
        $jabatanReviewer = User::where('nama', $jsa->direview_oleh)->value('role') ?? '-';
        $jabatanPenyetuju = User::where('nama', $jsa->disetujui_oleh)->value('role') ?? '-';

        return view('admin.Menus.DokumenBaru.JSA.review-jsaBaru', compact('jsa', 'jabatanPembuat', 'jabatanReviewer', 'jabatanPenyetuju'));
    }

    public function updateReview(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            
            $jsa = jsa_baru::findOrFail($id);
            $reviews = $request->input('reviews', []);
            $allChecked = true;
            
            foreach ($reviews as $controlId => $data) {
                $control = jsa_control_baru::findOrFail($controlId);
                $isApproved = isset($data['approved']) && $data['approved'] == '1';
                
                $control->approved = $isApproved;
                $control->feedback = $data['feedback'] ?? null;
                $control->save();

                if (!$isApproved) {
                    $allChecked = false;
                }
            }
            
            // Simpan status ke kolom yang ada di database
            $jsa->direview_oleh_approve = $allChecked ? 'approved' : 'rejected';
            $jsa->save();
            
            DB::commit();
            return redirect()->route('dataJsaBaru.index')->with('success', 'Review berhasil. Status: ' . strtoupper($jsa->direview_oleh_approve));
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal update review: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $jsa = jsa_baru::findOrFail($id);
        if($jsa->file) {
            Storage::delete('public/jsa_pending/' . $jsa->file);
        }
        $jsa->delete();
        return redirect()->back()->with('success', 'Data JSA berhasil dihapus.');
    }
}