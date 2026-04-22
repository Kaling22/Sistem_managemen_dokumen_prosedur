<?php

namespace App\Http\Controllers\DokumenBaru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DokumenBaru\sop_baru;
use App\Models\User; // Pastikan Model User di-import
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class tb_sop_baru extends Controller
{
    /**
     * Fungsi API untuk mengambil data Approver dan Semua User
     * Masukkan route ini di api.php atau web.php: 
     * Route::get('/api/get-approvers', [tb_sop_baru::class, 'getApprovers']);
     */
    public function getApprovers()
    {
        try {
            // 1. Ambil Verifikator (DH/SH: Role 3 & 4)
            $dhsh = User::whereIn('role', [3, 4])
                        ->orderBy('nama', 'asc')
                        ->get(['nama', 'nrp']);

            // 2. Ambil Approval (PJO: Role 2)
            $pjo = User::where('role', 2)
                    ->orderBy('nama', 'asc')
                    ->get(['nama', 'nrp']);

            // 3. Ambil SEMUA USER untuk Pembuat Tambahan (Role 0 sampai 6)
            // Kita gunakan orderBy agar mudah dicari di dropdown
            $allUsers = User::whereIn('role', [0, 1, 2, 3, 4, 5, 6])
                            ->orderBy('nama', 'asc')
                            ->get(['nama', 'nrp', 'role']);

            return response()->json([
                'success' => true,
                'dhsh' => $dhsh,
                'pjo' => $pjo,
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
            $sopBaru = sop_baru::all();
        } 
        elseif ($user->role == 2) {
            $sopBaru = sop_baru::where('PJO', $userName)->get();
        }
        elseif ($user->role == 1) {
            $sopBaru = sop_baru::where('departemen', $userDept)->get();
        }
        elseif ($user->role == 5) {
            $sopBaru = sop_baru::where('departemen', $userDept)->get();
        }
        elseif ($user->role == 6) {
            $sopBaru = sop_baru::where('departemen', $userDept)->get();
        }
        
        elseif (in_array($user->role, [3, 4])) {
            $sopBaru = sop_baru::where('departemen', $userDept)
                                ->where('DHdanSH', $userName)
                                ->get();
        } 
        else {
            $sopBaru = collect();
        }

        return view('admin.Menus.DokumenBaru.SOP.sopBaru', compact('sopBaru'));
    }

    public function create()
    {
        $user = Auth::user();
        $deptCode = $user->departemen; 
        $prefix = "PPA-ADRO-SOP-{$deptCode}-";
        
        $tabel = 'tb_sop_' . strtolower(str_replace(' ', '_', trim($user->departemen))) . 's';
        
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

        return view('admin.Menus.DokumenBaru.SOP.create-sopBaru', compact('autoNumber'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_dokumen' => 'required',
            'judul' => 'required',
            'jenis_doc' => 'required',
            'DHdanSH' => 'required',
            'PJO' => 'required',
        ]);

        ini_set('memory_limit', '512M'); 

        try {
            $people = $request->input('people', []);
            $peopleData = array_values(array_filter($people, function($value) {
                return !empty($value);
            }));

            $peopleApproveStatus = [];
            foreach ($peopleData as $nama) {
                $peopleApproveStatus[$nama] = 'pending';
            }

            $lampiranData = [];
            if ($request->has('lampiran_judul')) {
                foreach ($request->lampiran_judul as $key => $judul) {
                    $filePath = null;
                    if ($request->hasFile("lampiran_file.$key")) {
                        $file = $request->file("lampiran_file")[$key];
                        $filePath = $file->store('lampiran_sop', 'public');
                    }
                    $lampiranData[] = [
                        'judul' => $judul,
                        'text'  => $request->lampiran_text[$key] ?? '',
                        'file'  => $filePath
                    ];
                }
            }

            $newData = sop_baru::create([
                'no_dokumen' => $request->no_dokumen,
                'judul'      => $request->judul,
                'jenis_doc'  => $request->jenis_doc,
                'tujuan'     => $request->tujuan,
                'ruang_lingkup' => $request->ruang_lingkup,
                'referensi'  => $request->referensi, 
                'definisi'   => $request->definisi,  
                'aktifitas_tanggung_jawab' => $request->aktifitas_tanggung_jawab,
                'lampiran'   => json_encode($lampiranData),
                'people'     => json_encode($peopleData),
                // Simpan status awal ke kolom people_approve
                'people_approve' => json_encode($peopleApproveStatus), 
                'departemen' => Auth::user()->departemen,
                'pembuat'    => Auth::user()->nama,
                'DHdanSH'    => $request->DHdanSH,
                'PJO'        => $request->PJO,
                'status_doc' => 'Draft',
                'pembuat_date' => now(),
                'catatan' => null
            ]);

            $this->generateSOP_PDF($newData);

            return redirect()->route('dataSopBaru.index')
                             ->with('success', 'Dokumen berhasil dikirim untuk verifikasi.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan dokumen: ' . $e->getMessage());
        }
    }

    public function approvePeople(Request $request)
    {
        $doc = sop_baru::findOrFail($request->id);
        $userLogin = Auth::user()->nama;

        // Decode status yang ada sekarang
        $currentStatus = json_decode($doc->people_approve, true) ?? [];

        // Pastikan user ini memang ada di daftar people
        if (!array_key_exists($userLogin, $currentStatus)) {
            return back()->with('error', 'Anda tidak memiliki otoritas approval untuk dokumen ini.');
        }

        // Update status spesifik user tersebut
        $currentStatus[$userLogin] = $request->action; // 'approved' atau 'rejected'

        $doc->update([
            'people_approve' => json_encode($currentStatus)
        ]);

        // Opsional: Regenerate PDF jika ingin status approval masuk ke PDF
        $this->generateSOP_PDF($doc);

        return back()->with('success', 'Status persetujuan staf diperbarui.');
    }

    public function approvePJO(Request $request)
    {
        $doc = sop_baru::findOrFail($request->id);
        $action = $request->action;

        if ($action == 'approve') {
            try {
                DB::beginTransaction();                
                $doc->update([
                    'PJOApprove' => 'approved',
                    'PJOFeedback' => $request->feedback,
                    'status_doc' => 'Active',
                    'pjo_date' => now(),
                    'efektif_date' => now()
                ]);

                $newFileName = $this->generateSOP_PDF($doc);
                $namaTable = 'tb_sop_' . strtolower(str_replace(' ', '_', trim($doc->departemen))) . 's';

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
                    'PJO'                      => $doc->PJO,
                    'pjo_date'                 => $doc->pjo_date,
                    'status_doc'               => 'Active',
                    'tujuan'                   => $doc->tujuan,
                    'ruang_lingkup'            => $doc->ruang_lingkup,
                    'referensi'                => $doc->referensi, 
                    'definisi'                 => $doc->definisi,  
                    'aktifitas_tanggung_jawab' => $doc->aktifitas_tanggung_jawab,
                    'lampiran'                 => $doc->lampiran, 
                    'edisi'                    => '1',
                    'revisi'                   => '0',
                    'efektif_date'             => $doc->efektif_date ?? now(),
                    'catatan' => null,
                    'created_at'               => now(),
                    'updated_at'               => now(),
                ]);

                if (Storage::exists('public/sop_pending/' . $newFileName)) {
                    Storage::move('public/sop_pending/' . $newFileName, 'public/sop/' . $newFileName);
                }

                $doc->delete();
                DB::commit();
                return redirect()->route('dataSopBaru.index')->with('success', 'Dokumen telah Aktif.');

            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'Gagal migrasi: ' . $e->getMessage());
            }
        } else {
            $doc->update([
                'PJOApprove' => 'rejected',
                'PJOFeedback' => $request->feedback,
                'status_doc' => 'Rejected'
            ]);
            return back()->with('success', 'Dokumen direject.');
        }
    }

    public function approveDHSH(Request $request)
    {
        $doc = sop_baru::findOrFail($request->id);
        if ($request->action == 'approve') {
            $doc->update([
                'DHdanSHApprove' => 'approved',
                'DHdanSHFeedback' => $request->feedback,
                'status_doc' => 'Waiting PJO',
                'dhsh_date' => now()
            ]);
            
            if ($doc->file && Storage::exists('public/sop_pending/' . $doc->file)) {
                Storage::delete('public/sop_pending/' . $doc->file);
            }
            $this->generateSOP_PDF($doc);
        } else {
            $doc->update([
                'DHdanSHApprove' => 'rejected',
                'DHdanSHFeedback' => $request->feedback,
                'status_doc' => 'Rejected'
            ]);
        }
        return back()->with('success', 'Status verifikasi diperbarui.');
    }

    private function generateSOP_PDF($doc)
    {
        ini_set('memory_limit', '512M');
        
        // --- Bagian Lampiran (Tetap Aman/Tidak Berubah) ---
        $lampiranData = json_decode($doc->lampiran, true) ?? [];
        $processedLampiran = [];
        foreach ($lampiranData as $item) {
            $base64 = null;
            if (isset($item['file'])) {
                $path = storage_path('app/public/' . $item['file']);
                if (file_exists($path)) {
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $imgData = file_get_contents($path);
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($imgData);
                }
            }
            $processedLampiran[] = [
                'judul' => $item['judul'],
                'text' => $item['text'],
                'base64' => $base64
            ];
        }

        $pdfData = $doc->toArray();
        $pdfData['lampiran_array'] = $processedLampiran;

        // --- Bagian Perbaikan People (Logika Baru yang Lebih Kuat) ---
        $rawPeople = $doc->people;
        $peopleArray = [];

        if (!empty($rawPeople)) {
            // Decode tahap pertama
            $decoded = json_decode($rawPeople, true);

            // Jika hasilnya masih berupa string JSON (Double Encoded dengan backslash)
            if (is_string($decoded)) {
                $peopleArray = json_decode($decoded, true) ?? [];
            } 
            // Jika hasilnya langsung berupa Array (Format JSON bersih setelah PJO Approve)
            elseif (is_array($decoded)) {
                $peopleArray = $decoded;
            } 
            // Jika data sudah dalam bentuk Array (Casting Laravel)
            elseif (is_array($rawPeople)) {
                $peopleArray = $rawPeople;
            }
            // Jika string biasa dipisah koma
            else {
                $peopleArray = explode(',', $rawPeople);
            }
        }

        // Pastikan hasil akhirnya array dan bersih dari karakter kutip/backslash sisa
        $pdfData['people_array'] = array_map(function($item) {
            return trim($item, " \t\n\r\0\x0B\"");
        }, (array) $peopleArray);

        // --- Bagian Output PDF (Tetap Aman/Tidak Berubah) ---
        $pdf = Pdf::loadView('admin.Menus.Template.template_sop', $pdfData)
                    ->setPaper('A4', 'portrait');

        $fileName = 'SOP-' . time() . '-' . str_replace('/', '-', $doc->no_dokumen) . '.pdf';
        Storage::put('public/sop_pending/' . $fileName, $pdf->output());

        $doc->update(['file' => $fileName]);
        return $fileName;
    }

    public function destroy($id)
    {
        //delete data
        $sopBaru = sop_baru::findOrFail($id);
        if ($sopBaru->file && Storage::exists('public/sop_pending/' . $sopBaru->file)) {
            Storage::delete('public/sop_pending/' . $sopBaru->file);
        }
        $sopBaru->delete();
        return redirect()->route('dataSopBaru.index')->with('success', 'Dokumen berhasil dihapus.');
    }
}