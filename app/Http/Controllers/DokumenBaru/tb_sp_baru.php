<?php

namespace App\Http\Controllers\DokumenBaru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DokumenBaru\sp_baru;
use App\Models\User; // Pastikan Model User di-import
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class tb_sp_baru extends Controller
{


    /**
     * Menampilkan halaman edit/revisi
     */
    public function edit($id)
    {
        $data = sp_baru::findOrFail($id);
        
        // Proteksi: Hanya pembuat atau admin yang bisa edit
        if (Auth::user()->nama != $data->pembuat && !in_array(Auth::user()->role, [0, 1])) {
            return redirect()->route('dataSpBaru.index')->with('error', 'Anda tidak memiliki hak akses untuk mengedit dokumen ini.');
        }

        return view('admin.Menus.DokumenBaru.SP.edit-spBaru', compact('data'));
    }

    /**
     * Memproses update revisi
     */
    public function update(Request $request, $id)
{
    $request->validate([
        'no_dokumen' => 'required',
        'judul' => 'required',
        'jenis_doc' => 'required',
        'DHdanSH' => 'required',
    ]);

    try {
        $sp = sp_baru::findOrFail($id);

        if ($sp->status_doc == 'Rejected') {
            $sp->status_doc = 'Pending';
            $sp->DHdanSHApprove = 'waiting';
        }

        // 1. Update Data Dasar
        $sp->no_dokumen = $request->no_dokumen;
        $sp->judul = $request->judul;
        $sp->jenis_doc = $request->jenis_doc;
        $sp->tujuan = $request->tujuan;
        $sp->ruang_lingkup = $request->ruang_lingkup;
        $sp->referensi = $request->referensi;
        $sp->definisi = $request->definisi;
        
        // PASTIKAN INI TERISI (Data dari Hidden Input hasil gabungan JS)
        $sp->aktifitas_tanggung_jawab = $request->aktifitas_tanggung_jawab;
        
        $sp->DHdanSH = $request->DHdanSH;
        
        // 2. Perbaikan Logika People (Simpan sebagai JSON)
        if ($request->has('people')) {
            $sp->people = json_encode(array_values(array_filter($request->people)));
        }

        // 3. Perbaikan Logika Lampiran
        $lampiranData = [];
        if ($request->has('lampiran_judul')) {
            // Ambil lampiran lama yang sudah ada di DB sebagai dasar
            $existingLampiran = json_decode($sp->lampiran, true) ?? [];

            foreach ($request->lampiran_judul as $key => $judul) {
                $filePath = null;

                // Cek apakah ada upload file baru untuk baris ini
                if ($request->hasFile("lampiran_file.$key")) {
                    $file = $request->file("lampiran_file")[$key];
                    $filePath = $file->store('lampiran_sp', 'public');
                } 
                // Jika tidak upload baru, cari file lama dari data sebelumnya (menggunakan index)
                elseif (isset($existingLampiran[$key]['file'])) {
                    $filePath = $existingLampiran[$key]['file'];
                }

                $lampiranData[] = [
                    'judul' => $judul,
                    'text'  => $request->lampiran_text[$key] ?? '',
                    'file'  => $filePath
                ];
            }
            $sp->lampiran = json_encode($lampiranData);
        }

        $sp->save();

        // 4. Regenerate PDF
        if ($sp->file && Storage::exists('public/sp_pending/' . $sp->file)) {
            Storage::delete('public/sp_pending/' . $sp->file);
        }
        $this->generateSP_PDF($sp);

        return redirect()->route('dataSpBaru.index')->with('success', 'Dokumen berhasil direvisi.');

    } catch (\Exception $e) {
        return back()->with('error', 'Gagal: ' . $e->getMessage());
    }
}
    
    /**
     * Fungsi API untuk mengambil data Approver dan Semua User
     * Masukkan route ini di api.php atau web.php: 
     * Route::get('/api/get-approvers', [tb_sp_baru::class, 'getApprovers']);
     */
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
            $spBaru = sp_baru::all();
        }
        elseif ($user->role == 1) {
            $spBaru = sp_baru::where('departemen', $userDept)->get();
        }
        elseif (in_array($user->role, [3, 4])) {
            $spBaru = sp_baru::where('departemen', $userDept)
                                ->where('DHdanSH', $userName)
                                ->get();
        } 
        else {
            $spBaru = collect();
        }

        return view('admin.Menus.DokumenBaru.SP.spBaru', compact('spBaru'));
    }

    public function create()
    {
        $user = Auth::user();
        $deptCode = $user->departemen; 
        $prefix = "PPA-ADRO-SP-{$deptCode}-";
        
        $tabel = 'tb_sp_' . strtolower(str_replace(' ', '_', trim($user->departemen))) . 's';
        
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
        $autoNumber = $prefix . str_pad($nextNumber, 2, '00', STR_PAD_LEFT);

        return view('admin.Menus.DokumenBaru.SP.create-spBaru', compact('autoNumber'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_dokumen' => 'required',
            'judul' => 'required',
            'jenis_doc' => 'required',
            'DHdanSH' => 'required',
        ]);

        ini_set('memory_limit', '512M'); 

        try {
            $people = $request->input('people', []);
            $peopleData = array_values(array_filter($people, function($value) {
                return !empty($value);
            }));

            $lampiranData = [];
            if ($request->has('lampiran_judul')) {
                foreach ($request->lampiran_judul as $key => $judul) {
                    $filePath = null;
                    if ($request->hasFile("lampiran_file.$key")) {
                        $file = $request->file("lampiran_file")[$key];
                        $filePath = $file->store('lampiran_sp', 'public');
                    }
                    $lampiranData[] = [
                        'judul' => $judul,
                        'text'  => $request->lampiran_text[$key] ?? '',
                        'file'  => $filePath
                    ];
                }
            }

            $newData = sp_baru::create([
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
                'departemen' => Auth::user()->departemen,
                'pembuat'    => Auth::user()->nama,
                'DHdanSH'    => $request->DHdanSH,
                'status_doc' => 'Draft',
                'pembuat_date' => now(),
                'catatan' => null
            ]);

            $this->generateSP_PDF($newData);

            return redirect()->route('dataSpBaru.index')
                             ->with('success', 'Dokumen berhasil dikirim untuk verifikasi.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan dokumen: ' . $e->getMessage());
        }
    }

    public function approveDHSH(Request $request)
    {
        $doc = sp_baru::findOrFail($request->id);
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

                $newFileName = $this->generateSP_PDF($doc);
                $namaTable = 'tb_sp_' . strtolower(str_replace(' ', '_', trim($doc->departemen))) . 's';

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
                    'tujuan'                   => $doc->tujuan,
                    'ruang_lingkup'            => $doc->ruang_lingkup,
                    'referensi'                => $doc->referensi, 
                    'definisi'                 => $doc->definisi,  
                    'aktifitas_tanggung_jawab' => $doc->aktifitas_tanggung_jawab,
                    'lampiran'                 => $doc->lampiran, 
                    'edisi'                    => '1',
                    'revisi'                   => '0',
                    'efektif_date'             => $doc->efektif_date ?? now(),
                    'catatan'                   => null,
                    'created_at'               => now(),
                    'updated_at'               => now(),
                ]);

                if (Storage::exists('public/sp_pending/' . $newFileName)) {
                    Storage::move('public/sp_pending/' . $newFileName, 'public/sp/' . $newFileName);
                }

                $doc->delete();
                DB::commit();
                return redirect()->route('dataSpBaru.index')->with('success', 'Dokumen telah Aktif.');

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

    private function generateSP_PDF($doc)
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
            // Jika hasilnya langsung berupa Array (Format JSON bersih setelah DHSH Approve)
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
        $pdf = Pdf::loadView('admin.Menus.Template.template_sp', $pdfData)
                    ->setPaper('A4', 'portrait');

        $fileName = 'SP-' . time() . '-' . str_replace('/', '-', $doc->no_dokumen) . '.pdf';
        Storage::put('public/sp_pending/' . $fileName, $pdf->output());

        $doc->update(['file' => $fileName]);
        return $fileName;
    }

    public function destroy($id)
    {
        $doc = sp_baru::findOrFail($id);
        if ($doc->file) {
            Storage::delete('public/sp_pending/' . $doc->file);
        }
        $doc->delete();
        return redirect()->route('dataSpBaru.index')->with('success', 'Dokumen berhasil dihapus.');
    }
}
