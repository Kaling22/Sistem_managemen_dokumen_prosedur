<?php

namespace App\Http\Controllers\DokumenRevisi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DokumenRevisi\ik_revisi;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class tb_ik_revisi extends Controller
{

    public function checkPendingRevision($no_dokumen)
    {
        // Cari apakah ada no_dokumen yang sama di tabel revisi
        // Kita cek status yang bukan 'Active' (karena Active berarti sudah selesai/pindah ke master)
        $pending = ik_revisi::where('no_dokumen', $no_dokumen)->first();

        if ($pending) {
            return response()->json([
                'status' => 'exists',
                'message' => 'Revisi baru tidak bisa diajukan karena dokumen ini sedang dalam proses approval.',
                'detail' => [
                    'status_saat_ini' => $pending->status_doc,
                    'oleh' => $pending->pembuat
                ]
            ]);
        }

        return response()->json(['status' => 'available']);
    }
    
    public function index()
    {
        $ikRevisi = ik_revisi::all();
        return view('admin.Menus.DokumenRevisi.IK.dataikRevisi', compact('ikRevisi'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        ini_set('memory_limit', '512M'); 

        // --- FIX DOUBLE ENCODING PEOPLE ---
        $peopleFinalValue = null;
        if ($request->filled('people')) {
            $inputPeople = $request->people;
            
            // Jika sudah format JSON string (dari JS JSON.stringify), simpan apa adanya
            if (is_string($inputPeople) && str_starts_with($inputPeople, '[')) {
                $peopleFinalValue = $inputPeople;
            } else {
                // Jika inputnya koma atau array, baru di-encode
                $peopleArray = is_array($inputPeople) ? $inputPeople : array_map('trim', explode(',', $inputPeople));
                $peopleFinalValue = json_encode($peopleArray);
            }
        }
        // ----------------------------------

        // 2. Simpan Data ke Database
        $revisi = ik_revisi::create([
            'no_dokumen'    => $request->no_dokumen,
            'judul'         => $request->judul,
            'jenis_doc'     => $request->jenis_doc,
            'departemen'    => $request->departemen,
            'pembuat'       => Auth::user()->nama,
            'pembuat_date'  => now(),
            'status_doc'    => 'Revision Pending',
            'aktifitas_tanggung_jawab' => $request->aktifitas_tanggung_jawab,
            'DHdanSH'       => $request->DHdanSH,
            'revisi'        => (intval($request->revisi) >= 5) ? 0 : intval($request->revisi) + 1,
            'edisi'         => (intval($request->revisi) >= 5) ? intval($request->edisi ?? 0) + 1 : ($request->edisi ?? 0),
            'efektif_date'  => $request->efektif_date ?? null,
            'people'        => $peopleFinalValue, 
            'catatan'       => $request->catatan ?? null,
        ]);

        // 3. Generate PDF
        try {
            $fileName = $this->generateRevisiPDF($revisi);
            $revisi->update(['file' => $fileName]);

            return redirect()->route('dataIkRevisi.index')->with('success', 'Pengajuan revisi dan Draft PDF berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()->route('dataIkRevisi.index')->with('error', 'Data tersimpan, tapi PDF gagal dibuat: ' . $e->getMessage());
        }
    }

    public function approveDHSH(Request $request)
    {
        // 1. Ambil data dari tabel REVISI, bukan ik_baru
        $doc = ik_revisi::findOrFail($request->id); 
        $action = $request->action;

        if ($action == 'approve') {
            try {
                DB::beginTransaction();
                
                // 2. Update status di tabel revisi (opsional, sebelum dihapus/pindah)
                $doc->update([
                    'DHdanSHApprove' => 'approved',
                    'DHdanSHFeedback' => $request->feedback,
                    'status_doc' => 'Active',
                    'dhsh_date' => now(),
                    'efektif_date' => now()
                ]);

                // 3. Gunakan method generate PDF yang sesuai (Pastikan method ini ada atau gunakan generateRevisiPDF)
                // Jika untuk publish, biasanya menggunakan template ik Final
                $newFileName = $this->generateRevisiPDF($doc, json_decode($doc->lampiran, true)); 

                // 4. Tentukan nama tabel departemen secara dinamis
                $namaTable = 'tb_ik_' . strtolower(str_replace(' ', '_', trim($doc->departemen))) . 's';

                // 5. Insert ke tabel arsip departemen
                
                DB::table($namaTable)
                    ->where('no_dokumen', $doc->no_dokumen)
                    ->update(['status_doc' => 'Inactive']);
                DB::table($namaTable)->insert([
                    'no_dokumen'               => $doc->no_dokumen,
                    'judul'                    => $doc->judul,
                    'jenis_doc'                => $doc->jenis_doc,
                    'departemen'               => $doc->departemen,
                    'file'                     => $newFileName, 
                    'pembuat'                  => $doc->pembuat,
                    'pembuat_date'             => $doc->pembuat_date,
                    'DHdanSH'                  => $doc->DHdanSH, // Nama atasan
                    'DHdanSHApprove'           => 'approved',
                    'dhsh_date'                => now(),
                    'status_doc'               => 'Active',
                    'aktifitas_tanggung_jawab' => $doc->aktifitas_tanggung_jawab,
                    'edisi'                    => $doc->edisi,
                    'revisi'                   => $doc->revisi, 
                    'efektif_date'             => $doc->efektif_date ?? now(),
                    'catatan'                  => $doc->catatan,
                    'people'                   => $doc->people,
                    'created_at'               => now(),
                    'updated_at'               => now(),
                ]);

                // 6. Pindahkan file dari folder revisi_pending ke folder ik final
                if (Storage::disk('public')->exists('ik_revisi_pending/' . $newFileName)) {
                    Storage::disk('public')->move('ik_revisi_pending/' . $newFileName, 'ik/' . $newFileName);
                }

                // 7. Hapus draft di tabel revisi karena sudah masuk tabel utama
                $doc->delete();

                DB::commit();
                return redirect()->route('dataIkRevisi.index')->with('success', 'Dokumen Revisi telah Aktif dan dipublikasikan.');

            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'Gagal aktivasi revisi: ' . $e->getMessage());
            }
        } else {
            // Logika Reject
            $doc->update([
                'DHdanSHApprove' => 'rejected',
                'DHdanSHFeedback' => $request->feedback,
                'status_doc' => 'Rejected'
            ]);
            return back()->with('success', 'Dokumen revisi telah ditolak.');
        }  
    }
    private function generateRevisiPDF($revisi)
    {
        // --- AMBIL SEMUA CATATAN REVISI SEBELUMNYA ---
        $tabel = 'tb_ik_' . strtolower(str_replace(' ', '_', trim($revisi->departemen))) . 's';

        $historyLogs = \DB::table($tabel)
            ->where('no_dokumen', $revisi->no_dokumen)
            ->whereNotNull('catatan')
            ->orderBy('edisi', 'asc')
            ->orderBy('revisi', 'asc')
            ->select('edisi', 'revisi', 'catatan', 'pembuat_date')
            ->get();

        $allNotes = [];
        foreach ($historyLogs as $log) {
            $allNotes[] = [
                'versi' => "Edisi " . $log->edisi . " Rev " . $log->revisi,
                'catatan' => $log->catatan,
                'tanggal' => date('d/m/Y', strtotime($log->pembuat_date))
            ];
        }

        // Tambahkan catatan revisi yang sedang diajukan sekarang sebagai baris terakhir
        $allNotes[] = [
            'versi' => "Edisi " . $revisi->edisi . " Rev " . $revisi->revisi,
            'catatan' => $revisi->catatan,
            'tanggal' => date('d/m/Y')
        ];
        // ---------------------------------------------

        $pdfData = $revisi->toArray();
        $pdfData['history_notes'] = $allNotes; // Kirim riwayat ke PDF

        // --- LOGIKA FLEXIBLE DECODE UNTUK PEOPLE ---
        $rawPeople = $revisi->people;
        $peopleArray = [];

        if (!empty($rawPeople)) {
            $decoded = json_decode($rawPeople, true);
            if (is_string($decoded)) {
                $peopleArray = json_decode($decoded, true) ?? [];
            } elseif (is_array($decoded)) {
                $peopleArray = $decoded;
            } else {
                $peopleArray = explode(',', $rawPeople);
            }
        }

        $pdfData['people_array'] = array_map(function($item) {
            return trim($item, " \t\n\r\0\x0B\"");
        }, (array)$peopleArray);
        // -------------------------------------------

        $pdf = Pdf::loadView('admin.Menus.Template.template_ik', $pdfData)
                ->setPaper('A4', 'portrait');

        $fileName = 'REV-' . time() . '-' . str_replace('/', '-', $revisi->no_dokumen) . '.pdf';
        Storage::put('public/ik_revisi_pending/' . $fileName, $pdf->output());

        return $fileName;
    }

    public function destroy($id)
    {
        $ikRevisi = ik_revisi::findOrFail($id);
        if ($ikRevisi->file) {
            Storage::delete('public/ik_revisi_pending/' . $ikRevisi->file);
        }
        $ikRevisi->delete();
        return redirect()->route('dataIkRevisi.index')->with('success', 'Data revisi berhasil dihapus.');
    }
}
