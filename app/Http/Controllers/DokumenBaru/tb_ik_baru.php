<?php

namespace App\Http\Controllers\DokumenBaru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DokumenBaru\ik_baru;
use App\Models\User; // Pastikan Model User di-import
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class tb_ik_baru extends Controller
{
    public function getApprovers()
    {
        try {
            // 1. Ambil Verifikator (DH/SH: Role 3 & 4)
            $dhsh = User::whereIn('role', [3, 4])
                        ->orderBy('nama', 'asc')
                        ->get(['nama', 'nrp']);

            // 2. Ambil SEMUA USER untuk Pembuat Tambahan (Role 0 sampai 6)
            // Kita gunakan orderBy agar mudah dicari di dropdown
            $allUsers = User::whereIn('role', [0, 1, 2, 3, 4, 5, 6])
                            ->orderBy('nama', 'asc')
                            ->get(['nama', 'nrp', 'role']);

            return response()->json([
                'success' => true,
                'dhsh' => $dhsh,
                'all_users' => $allUsers
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        $user = Auth::user();
        $userName = trim($user->nama);
        $userDept = trim($user->departemen);

        if ($user->role == 0) {
            $ikBaru = ik_baru::all();
        }
        elseif ($user->role == 1) {
            $ikBaru = ik_baru::where('departemen', $userDept)->get();
        }
        elseif (in_array($user->role, [3, 4])) {
            $ikBaru = ik_baru::where('departemen', $userDept)
                                ->where('DHdanSH', $userName)
                                ->get();
        } 
        else {
            $ikBaru = collect();
        }

        return view('admin.Menus.DokumenBaru.IK.ikBaru', compact('ikBaru'));
    }

    public function create()
    {
        $user = Auth::user();
        $deptCode = $user->departemen; 
        $prefix = "PPA-ADRO-IK-{$deptCode}-";
        
        $tabel = 'tb_ik_' . strtolower(str_replace(' ', '_', trim($user->departemen))) . 's';
        
        try {
            $allDocs = DB::table($tabel)->where('no_dokumen', 'like', $prefix . '%')->get();
        } catch (\Exception $e) {
            $allDocs = collect();
        }

        $maxNumber = 0;
        foreach ($allDocs as $doc) {
            $parts = explode('-', $doc->no_dokumen);
            $lastPart = end($parts);
            if (is_numeric($lastPart)) {
                $num = (int) $lastPart;
                if ($num > $maxNumber) { $maxNumber = $num; }
            }
        }

        $nextNumber = $maxNumber + 1;
        $autoNumber = $prefix . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);

        return view('admin.Menus.DokumenBaru.IK.create-ikBaru', compact('autoNumber'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'no_dokumen' => 'required',
            'judul' => 'required',
            'jenis_doc' => 'required',
            'DHdanSH' => 'required',
        ]);

        ini_set('memory_limit', '512M'); 

        try {
            // 2. Olah data people tambahan
            $people = $request->input('people', []);
            $peopleData = array_values(array_filter($people, function($value) {
                return !empty($value);
            }));

            // 3. Simpan ke Database
            $newData = ik_baru::create([
                'no_dokumen' => $request->no_dokumen,
                'judul'      => $request->judul,
                'jenis_doc'  => $request->jenis_doc,
                'aktifitas_tanggung_jawab' => $request->aktifitas_tanggung_jawab,
                'people'     => json_encode($peopleData),
                'departemen' => Auth::user()->departemen,
                'pembuat'    => Auth::user()->nama,
                'DHdanSH'    => $request->DHdanSH,
                'status_doc' => 'Draft',
                'pembuat_date' => now(),
            ]);

            // 4. Jalankan PDF Generator
            $this->generateIK_PDF($newData);

            // 5. SELESAI - Langsung Return
            return redirect()->route('dataIkBaru.index')
                        ->with('success', 'Dokumen berhasil dikirim.');

        } catch (\Exception $e) {
            // Jika gagal, tampilkan pesan error yang spesifik
            return back()->with('error', 'Gagal: ' . $e->getMessage())->withInput();
        }
    }

    public function approveDHSH(Request $request)
    {
        $doc = ik_baru::findOrFail($request->id);
        $action = $request->action;
        if ($action == 'approve') {
            try {
                DB::beginTransaction();
                
                $doc->update([
                    'DHdanSHApprove' => 'approved',
                    'DHdanSHFeedback' => $request->feedback,
                    'status_doc' => 'Active',
                    'dhsh_date' => now(),
                    'efektif_date' => now()
                ]);

                $newFileName = $this->generateIK_PDF($doc);
                $namaTable = 'tb_ik_' . strtolower(str_replace(' ', '_', trim($doc->departemen))) . 's';
                
                DB::table($namaTable)->insert([
                    'no_dokumen'               => $doc->no_dokumen,
                    'judul'                    => $doc->judul,
                    'jenis_doc'                => $doc->jenis_doc,
                    'departemen'               => $doc->departemen,
                    'file'                     => $newFileName, 
                    'pembuat'                  => $doc->pembuat,
                    'people'                   => $doc->people,
                    'pembuat_date'             => $doc->pembuat_date,
                    'DHdanSH'                  => $doc->DHdanSH,
                    'dhsh_date'                => $doc->dhsh_date,
                    'status_doc'               => 'Active',
                    'aktifitas_tanggung_jawab' => $doc->aktifitas_tanggung_jawab,
                    'edisi'                    => '0',
                    'revisi'                   => '0',
                    'efektif_date'             => $doc->efektif_date ?? now(),
                    'catatan'                   => null,
                    'created_at'               => now(),
                    'updated_at'               => now(),
                ]);
                
                if (Storage::exists('public/ik_pending/' . $newFileName)) {
                    Storage::move('public/ik_pending/' . $newFileName, 'public/ik/' . $newFileName);
                }

                
                $doc->delete();
                DB::commit();
                return redirect()->route('dataIkBaru.index')->with('success', 'Dokumen telah Aktif.');
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'Gagal migrasi: ' . $e->getMessage());
            }
        } else {
            $doc->update([
                'DHdanSHApprove' => 'rejected',
                'DHdanSHFeedback' => $request->feedback,
                'status_doc' => 'Rejected'
            ]);
            return back()->with('success', 'Dokumen direject.');
        }  
    }

    private function generateIK_PDF($doc)
    {
        try {
            ini_set('memory_limit', '512M');
            $pdfData = $doc->toArray();

            // Olah People Array
            $rawPeople = $doc->people;
            $peopleArray = [];
            if (!empty($rawPeople)) {
                $decoded = json_decode($rawPeople, true);
                $peopleArray = is_array($decoded) ? $decoded : explode(',', $rawPeople);
            }
            $pdfData['people_array'] = $peopleArray;

            // Olah Aktivitas Array
            $rawAktivitas = $doc->aktifitas_tanggung_jawab;
            $aktivitasArray = [];
            if (!empty($rawAktivitas)) {
                $steps = explode('[END]', $rawAktivitas);
                foreach ($steps as $step) {
                    if (trim($step) != '') {
                        $parts = explode('[PIC]', $step);
                        $aktivitasArray[] = [
                            'isi' => trim($parts[0]),
                            'pic' => isset($parts[1]) ? trim($parts[1]) : '-'
                        ];
                    }
                }
            }
            $pdfData['aktivitas_array'] = $aktivitasArray;

            // LOAD VIEW - Pastikan path ini benar!
            // Jika file anda di resources/views/admin/Menus/Template/template_ik.blade.php
            $pdf = Pdf::loadView('admin.Menus.Template.template_ik', $pdfData)
                        ->setPaper('A4', 'portrait');

            $fileName = 'IK-' . time() . '-' . str_replace(['/', ' '], '-', $doc->no_dokumen) . '.pdf';
            
            // Simpan ke storage/app/public/ik_pending
            Storage::put('public/ik_pending/' . $fileName, $pdf->output());

            // Update record
            $doc->update(['file' => $fileName]);

            return $fileName;
        } catch (\Exception $e) {
            // Log error jika PDF gagal agar tidak membuat aplikasi crash
            \Log::error('PDF Generate Error: ' . $e->getMessage());
            return null;
        }
    }

    public function destroy($id)
    {
        $doc = ik_baru::findOrFail($id);
        if ($doc->file) {
            Storage::delete('public/ik_pending/' . $doc->file);
        }
        $doc->delete();
        return redirect()->route('dataIkBaru.index')->with('success', 'Dokumen berhasil dihapus.');
    }
}
