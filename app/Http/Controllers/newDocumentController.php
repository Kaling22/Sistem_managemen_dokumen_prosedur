<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tb_newDocument;
use App\Models\Produksi\tb_sop_produksi;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use setasign\Fpdi\Fpdi;

class newDocumentController extends Controller
{

    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan_reject' => 'required|string|max:500',
        ]);

        $dokumen = tb_newDocument::findOrFail($id);
        $user = Auth::user();

        // Tentukan kolom feedback berdasarkan role
        if (in_array($user->role, [3, 4])) {
            $dokumen->DHdanSHFeedback = $request->alasan_reject;
            $dokumen->DHdanSHApprove = 'rejected'; // Tandai juga status approvalnya
        } elseif ($user->role == 2) {
            $dokumen->PJOFeedback = $request->alasan_reject;
            $dokumen->PJOApprove = 'rejected';
        }

        $dokumen->status = 'Rejected';
        $dokumen->save();

        return redirect()->route('dataDokumenBaru.index')
            ->with('error', 'Dokumen berhasil ditolak.');
    }

    public function approveSave(Request $request, $id)
    {
        $request->validate([
            'ratio_x' => 'required|numeric',
            'ratio_y' => 'required|numeric',
        ]);

        $dokumen = tb_newDocument::findOrFail($id);
        $user = Auth::user();

        if (!$user->signature) {
            return back()->with('error', 'Signature belum tersedia');
        }

        $signaturePath = storage_path('app/public/signature/' . $user->signature);
        $pdfPath = storage_path('app/public/newDokumen/' . $dokumen->file);

        // Inisialisasi FPDI
        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile($pdfPath);

        for ($i = 1; $i <= $pageCount; $i++) {
            $pdf->AddPage();
            $tpl = $pdf->importPage($i);
            $size = $pdf->getTemplateSize($tpl);
            $pdf->useTemplate($tpl, 0, 0, $size['width'], $size['height']);

            if ($i == 1) {
                $realX = $request->ratio_x * $size['width'];
                $realY = $request->ratio_y * $size['height'];

                $sigWidth = 40; 
                $info = getimagesize($signaturePath);
                $sigHeight = ($info[1] / $info[0]) * $sigWidth;

                $finalX = $realX - ($sigWidth / 2);
                $finalY = $realY - ($sigHeight / 2);

                $pdf->Image($signaturePath, $finalX, $finalY, $sigWidth);
            }
        }

        // 1. Simpan PDF hasil tanda tangan (timpa file lama sementara atau simpan dengan nama baru)
        $newFileName = 'Approved_' . $dokumen->file;
        $tempPath = storage_path('app/public/newDokumen/' . $newFileName);
        $pdf->Output($tempPath, 'F');

        // Hapus file asli yang belum ditandatangani
        Storage::delete('public/newDokumen/' . $dokumen->file);

        // 2. Logika Role Approval
        if (in_array($user->role, [3, 4])) {
            $dokumen->DHdanSHApprove = 'approved';
        }

        // 3. LOGIKA FINAL APPROVAL (PJO - Role 2)
        if ($user->role == 2 && $dokumen->PJOApprove != 'approved') {
            $dokumen->PJOApprove = 'approved';
            $dokumen->status = 'Approved';

            // PINDAHKAN FILE FISIK dari folder 'newDokumen' ke 'sop'
            // Format: Storage::move('path/lama', 'path/baru')
            if (Storage::exists('public/newDokumen/' . $newFileName)) {
                Storage::move('public/newDokumen/' . $newFileName, 'public/sop/' . $newFileName);
            }

            // PINDAHKAN DATA KE TABEL SOP PRODUKSI
            tb_sop_produksi::create([
                'no_dokumen'      => $dokumen->no_dokumen,
                'judul_sop'       => $dokumen->judul,
                'file_sop'        => $newFileName,
                'edisi'           => 0,
                'revisi'          => 0,
                'tanggal_efektif' => now()->format('Y-m-d'),
            ]);

            // HAPUS DATA DARI TABEL LAMA (Karena sudah pindah ke SOP)
            $dokumen->delete();

            return redirect()->route('dataDokumenBaru.index')
                ->with('success', 'Dokumen disetujui PJO dan telah dipindahkan ke SOP Produksi');
        }

        // Jika bukan PJO (masih proses DH/SH), update data dokumen di tabel lama
        $dokumen->file = $newFileName;
        $dokumen->status = 'Pending PJO'; // Atau status lain sesuai alurmu
        $dokumen->save();

        return redirect()->route('dataDokumenBaru.index')
            ->with('success', 'Tanda tangan berhasil dibubuhkan');
    }

    public function approveView($id)
    {
        $dokumen = tb_newDocument::findOrFail($id);
        return view('admin.Menus.DokumenApprove.approve', compact('dokumen'));
    }

    public function index()
    {
        $newDokumen = tb_newDocument::all();
        return view('admin.Menus.DokumenApprove.data-newdokumen', compact('newDokumen'));
    }

    public function create()
    {
        return view('admin.Menus.DokumenApprove.create-newdokumen');
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_dokumen' => 'required|string',
            'judul' => 'required|string',
            'jenis_doc' => 'required|string',
            'file' => 'required|mimes:pdf|max:20480',
        ]);

        $file = $request->file('file');
        $file->storeAs('public/newDokumen', $file->hashName());

        tb_newDocument::create([
            'no_dokumen' => $request->no_dokumen,
            'judul' => $request->judul,
            'jenis_doc' => $request->jenis_doc,
            'file' => $file->hashName(),
            'departemen' => Auth::user()->departemen,
            'pembuat' => Auth::user()->nama,
            'status' => 'Open',
        ]);

        return redirect()->route('dataDokumenBaru.index')
            ->with('success', 'Dokumen berhasil diupload');
    }

    public function destroy($id)
    {
        $delete = tb_newDocument::findOrFail($id);

        Storage::delete('public/newDokumen/'.$delete->file);
        $delete->delete();

        return redirect()->route('dataDokumenBaru.index')
            ->with('success', 'Dokumen berhasil dihapus');
    }
}
