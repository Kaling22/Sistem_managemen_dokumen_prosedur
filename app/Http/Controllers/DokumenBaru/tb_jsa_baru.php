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
     * Map role integer ke nama Jabatan agar tampil rapi di PDF.
     */
    private function jabatanName($nama)
    {
        $role = User::where('nama', $nama)->value('role');
        switch ($role) {
            case 0: return 'Admin';
            case 1: return 'DOCO';
            case 2: return 'PJO';
            case 3: return 'Department Head';
            case 4: return 'Section Head';
            case 5: return 'Group Leader';
            case 6: return 'Non Staf';
            default: return '-';
        }
    }

    /**
     * Fungsi Private untuk Generate PDF dan simpan ke Storage (folder pending).
     */
    private function generateJsaPdf($jsaId)
    {
        $jsa = jsa_baru::with(['steps.hazards.controls'])->findOrFail($jsaId);

        $data = [
            'jsa' => $jsa,
            'jabatanPembuat' => $this->jabatanName($jsa->dibuat_oleh),
            'jabatanReviewer' => $this->jabatanName($jsa->direview_oleh),
            'jabatanPenyetuju' => $this->jabatanName($jsa->disetujui_oleh),
        ];

        $pdf = Pdf::loadView('admin.Menus.Template.template_jsa', $data);
        $fileName = 'JSA_Pending_' . $jsaId . '.pdf';

        Storage::put('public/jsa_pending/' . $fileName, $pdf->output());

        return $fileName;
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
            $jsa->edisi = '1';
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
            $this->storeJsaDetails($jsa->id, $request->jsa);

            // BAGIAN PDF: Panggil fungsi generate
            $this->generateJsaPdf($jsa->id);
            //simpan nama file ke database
            $jsa->file = 'JSA_Pending_' . $jsa->id . '.pdf';
            $jsa->save();

            DB::commit();
            return redirect()->route('dataJsaBaru.index')->with('success', 'JSA Berhasil Dibuat.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal menyimpan JSA: ' . $e->getMessage())->withInput();
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
            $jsa->direview_oleh_feedback = null;
            $jsa->status_doc = 'Waiting Reviewer';
            $jsa->save();

            // 2. Hapus detail lama untuk menghindari data ganda/sampah
            $this->deleteJsaDetails($jsa);

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

    /**
     * Hapus seluruh detail (steps -> hazards -> controls) milik sebuah JSA.
     */
    private function deleteJsaDetails($jsa)
    {
        foreach ($jsa->steps as $step) {
            foreach ($step->hazards as $hazard) {
                $hazard->controls()->delete();
            }
            $step->hazards()->delete();
        }
        $jsa->steps()->delete();
    }

    public function review($id)
    {
        $jsa = jsa_baru::with(['steps.hazards.controls'])->findOrFail($id);
        $jabatanPembuat = $this->jabatanName($jsa->dibuat_oleh);
        $jabatanReviewer = $this->jabatanName($jsa->direview_oleh);
        $jabatanPenyetuju = $this->jabatanName($jsa->disetujui_oleh);

        return view('admin.Menus.DokumenBaru.JSA.review-jsaBaru', compact('jsa', 'jabatanPembuat', 'jabatanReviewer', 'jabatanPenyetuju'));
    }

    public function updateReview(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $jsa = jsa_baru::findOrFail($id);
            $reviews = $request->input('reviews', []);
            $allChecked = true;
            $feedbacks = [];

            foreach ($reviews as $controlId => $data) {
                $control = jsa_control_baru::findOrFail($controlId);
                $isApproved = isset($data['approved']) && $data['approved'] == '1';

                $control->approved = $isApproved;
                $control->feedback = $data['feedback'] ?? null;
                $control->save();

                if (!empty($data['feedback'])) {
                    $feedbacks[] = $control->control_no . ': ' . $data['feedback'];
                }

                if (!$isApproved) {
                    $allChecked = false;
                }
            }

            // Simpan status review ke kolom yang ada di database
            $jsa->direview_oleh_approve = $allChecked ? 'approved' : 'rejected';
            $jsa->direview_oleh_feedback = !empty($feedbacks) ? implode("\n", $feedbacks) : null;
            $jsa->direview_date = now();
            // Jika reviewer approve -> lanjut ke antrian DH/SH. Jika tidak -> Rejected.
            $jsa->status_doc = $allChecked ? 'Waiting Approval' : 'Rejected';
            $jsa->save();

            // Regenerate PDF agar tanda centang review ikut tercetak
            $this->generateJsaPdf($jsa->id);

            DB::commit();
            return redirect()->route('dataJsaBaru.index')->with('success', 'Review berhasil. Status: ' . strtoupper($jsa->direview_oleh_approve));

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal update review: ' . $e->getMessage());
        }
    }

    /**
     * Approval final oleh DH/SH (disetujui_oleh).
     * Jika approve: dokumen jadi Active, di-publish ke tabel JSA departemen.
     */
    public function approveDHSH(Request $request)
    {
        $jsa = jsa_baru::findOrFail($request->id);

        // Reviewer wajib approve terlebih dahulu
        if ($jsa->direview_oleh_approve !== 'approved') {
            return back()->with('error', 'Dokumen belum di-review / disetujui oleh Reviewer.');
        }

        $action = $request->action; // 'approved' atau 'rejected'

        if ($action === 'approved') {
            try {
                DB::beginTransaction();

                $jsa->update([
                    'disetujui_oleh_approve'  => 'approved',
                    'disetujui_oleh_feedback' => $request->feedback,
                    'disetujui_date'          => now(),
                    'efektif_date'            => now(),
                    'status_doc'              => 'Active',
                ]);

                // Regenerate PDF (sudah lengkap tanda tangan/pengesahan)
                $newFileName = $this->generateJsaPdf($jsa->id);
                $jsa->update(['file' => $newFileName]);

                // Pindahkan PDF dari folder pending ke folder publish
                if (Storage::exists('public/jsa_pending/' . $newFileName)) {
                    Storage::makeDirectory('public/jsa');
                    if (Storage::exists('public/jsa/' . $newFileName)) {
                        Storage::delete('public/jsa/' . $newFileName);
                    }
                    Storage::move('public/jsa_pending/' . $newFileName, 'public/jsa/' . $newFileName);
                }

                // Publish ke tabel JSA departemen (tb_jsa_<dept>s)
                $this->publishToDepartemen($jsa, $newFileName);

                // Tandai versi lama (no_dokumen sama) menjadi Inactive
                jsa_baru::where('no_dokumen', $jsa->no_dokumen)
                        ->where('id', '!=', $jsa->id)
                        ->update(['status_doc' => 'Inactive']);

                DB::commit();
                return redirect()->route('dataJsaBaru.index')->with('success', 'JSA telah disetujui dan diterbitkan.');

            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'Gagal menerbitkan JSA: ' . $e->getMessage());
            }
        } else {
            $jsa->update([
                'disetujui_oleh_approve'  => 'rejected',
                'disetujui_oleh_feedback' => $request->feedback,
                'status_doc'              => 'Rejected',
            ]);
            return back()->with('success', 'JSA direject.');
        }
    }

    /**
     * Simpan / perbarui dokumen JSA aktif di tabel departemen.
     */
    private function publishToDepartemen($jsa, $fileName)
    {
        $namaTable = 'tb_jsa_' . strtolower(str_replace(' ', '_', trim($jsa->departemen))) . 's';

        $payload = [
            'no_dokumen'      => $jsa->no_dokumen,
            'judul_jsa'       => $jsa->nama_pekerjaan,
            'file_jsa'        => $fileName,
            'edisi'           => $jsa->edisi ?? '1',
            'revisi'          => $jsa->revisi ?? '0',
            'tanggal_efektif' => $jsa->efektif_date
                ? \Carbon\Carbon::parse($jsa->efektif_date)->format('Y-m-d')
                : now()->format('Y-m-d'),
            'updated_at'      => now(),
        ];

        $existing = DB::table($namaTable)->where('no_dokumen', $jsa->no_dokumen)->first();

        if ($existing) {
            // Revisi: perbarui baris yang sudah ada
            DB::table($namaTable)->where('no_dokumen', $jsa->no_dokumen)->update($payload);
        } else {
            $payload['views'] = 0;
            $payload['created_at'] = now();
            DB::table($namaTable)->insert($payload);
        }
    }

    /**
     * Form pengajuan revisi: tampilkan data dokumen aktif (prefilled).
     */
    public function revisi($id)
    {
        $jsa = jsa_baru::with(['steps.hazards.controls'])->findOrFail($id);

        if ($jsa->status_doc !== 'Active') {
            return redirect()->route('dataJsaBaru.index')
                ->with('error', 'Hanya dokumen aktif yang dapat diajukan revisi.');
        }

        return view('admin.Menus.DokumenBaru.JSA.revisi-jsaBaru', compact('jsa'));
    }

    /**
     * Simpan revisi sebagai record jsa_barus baru (reuse jsa_barus).
     */
    public function storeRevisi(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required',
            'lokasi_kerja' => 'required',
            'direview_oleh' => 'required',
            'disetujui_oleh' => 'required',
            'jsa' => 'required|array',
        ]);

        try {
            DB::beginTransaction();

            $old = jsa_baru::findOrFail($id);

            // Hitung revisi/edisi baru (rollover edisi setiap 5 revisi seperti SOP)
            $currentRevisi = intval($old->revisi);
            $currentEdisi = intval($old->edisi ?? 1);
            if ($currentRevisi >= 5) {
                $newRevisi = 0;
                $newEdisi = $currentEdisi + 1;
            } else {
                $newRevisi = $currentRevisi + 1;
                $newEdisi = $currentEdisi;
            }

            $jsa = new jsa_baru();
            $jsa->no_dokumen   = $old->no_dokumen;
            $jsa->no_jsa       = $old->no_jsa;
            $jsa->revisi       = str_pad($newRevisi, 2, '0', STR_PAD_LEFT);
            $jsa->edisi        = (string) $newEdisi;
            $jsa->tgl_terbit   = now();
            $jsa->tgl_pembuatan = now();
            $jsa->nama_pekerjaan = $request->judul;
            $jsa->jenis_doc    = 'JSA';
            $jsa->lokasi_kerja = $request->lokasi_kerja;
            $jsa->departemen   = $old->departemen;
            $jsa->status_doc   = 'Waiting Reviewer';
            $jsa->apd_wajib    = $request->apd_wajib;
            $jsa->peralatan_pendukung = $request->peralatan_pendukung;
            $jsa->dibuat_oleh  = Auth::user()->nama;
            $jsa->direview_oleh = $request->direview_oleh;
            $jsa->disetujui_oleh = $request->disetujui_oleh;
            $jsa->save();

            $this->storeJsaDetails($jsa->id, $request->jsa);

            $this->generateJsaPdf($jsa->id);
            $jsa->file = 'JSA_Pending_' . $jsa->id . '.pdf';
            $jsa->save();

            DB::commit();
            return redirect()->route('dataJsaBaru.index')
                ->with('success', 'Revisi JSA (Rev ' . $jsa->revisi . ') berhasil diajukan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengajukan revisi: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $jsa = jsa_baru::with(['steps.hazards.controls'])->findOrFail($id);

        // Hapus file di folder pending maupun publish
        if ($jsa->file) {
            Storage::delete('public/jsa_pending/' . $jsa->file);
            Storage::delete('public/jsa/' . $jsa->file);
        }

        $this->deleteJsaDetails($jsa);
        $jsa->delete();
        return redirect()->back()->with('success', 'Data JSA berhasil dihapus.');
    }
}
