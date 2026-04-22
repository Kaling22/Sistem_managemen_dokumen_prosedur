<?php

namespace App\Http\Controllers\daftarIndukDokumen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class daftar_induk_dokumen extends Controller
{
    public function exportIndukSop()
    {
        $user = Auth::user();
        $userDept = trim($user->departemen);
        $tabel = 'tb_sop_' . strtolower(str_replace(' ', '_', trim($user->departemen))) . 's';
        // 1. Ambil data dari tabel master
        $data = \DB::table($tabel)
                ->where('status_doc', 'Active')
                ->orderBy('no_dokumen', 'asc')
                ->get();

        // 2. Tentukan nama file
        $fileName = "Daftar_Induk_Dokumen_SOP_" . $userDept . "_" . date('d-m-Y') . ".xls";

        // 3. Header HTTP untuk memaksa download Excel
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$fileName\"");

        header("Pragma: no-cache");
        header("Expires: 0");

        // 4. Mulai Output Tabel
        echo '
        <table border="1">
            <thead>
                <tr>
                    <th colspan="7" style="font-size: 16px; font-weight: bold; height: 35px; background-color: #4F81BD; color: white; vertical-align: middle;">
                        DAFTAR INDUK DOKUMEN SOP ' . strtoupper($userDept) . '
                    </th>
                </tr>
                <tr style="background-color: #D9D9D9; font-weight: bold; height: 25px;">
                    <th width="50">No</th>
                    <th width="200">No. Dokumen</th>
                    <th width="400">Judul Dokumen</th>
                    <th width="120">Tgl. Terbit</th>
                    <th width="60">Edisi</th>
                    <th width="60">Revisi</th>
                    <th width="120">Tgl. Revisi Terakhir</th>
                </tr>
            </thead>
            <tbody>';

        $no = 1;
        foreach ($data as $row) {
            echo '
                <tr>
                    <td align="center" style="vertical-align: middle;">' . $no++ . '</td>
                    <td style="vertical-align: middle;">' . $row->no_dokumen . '</td>
                    <td style="vertical-align: middle;">' . $row->judul . '</td>
                    <td align="center" style="vertical-align: middle;">' . ($row->efektif_date ? date('d/m/Y', strtotime($row->efektif_date)) : '-') . '</td>
                    <td align="center" style="vertical-align: middle;">' . ($row->edisi ?? '0') . '</td>
                    <td align="center" style="vertical-align: middle;">' . ($row->revisi ?? '0') . '</td>
                    <td align="center" style="vertical-align: middle;">' . ($row->pjo_date ? date('d/m/Y', strtotime($row->pjo_date)) : '-') . '</td>
                </tr>';
        }

        echo '
            </tbody>
        </table>';

        // Penting: Hentikan eksekusi script agar tidak ada script HTML lain yang ikut masuk ke Excel
        exit;
    }

    public function exportIndukSp()
    {
        $user = Auth::user();
        $userDept = trim($user->departemen);
        $tabel = 'tb_sp_' . strtolower(str_replace(' ', '_', trim($user->departemen))) . 's';
        // 1. Ambil data dari tabel master
        $data = \DB::table($tabel)
                ->where('status_doc', 'Active')
                ->orderBy('no_dokumen', 'asc')
                ->get();

        // 2. Tentukan nama file
        $fileName = "Daftar_Induk_Dokumen_SP_" . $userDept . "_" . date('d-m-Y') . ".xls";

        // 3. Header HTTP untuk memaksa download Excel
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$fileName\"");

        header("Pragma: no-cache");
        header("Expires: 0");

        // 4. Mulai Output Tabel
        echo '
        <table border="1">
            <thead>
                <tr>
                    <th colspan="7" style="font-size: 16px; font-weight: bold; height: 35px; background-color: #4F81BD; color: white; vertical-align: middle;">
                        DAFTAR INDUK DOKUMEN SP ' . strtoupper($userDept) . '
                    </th>
                </tr>
                <tr style="background-color: #D9D9D9; font-weight: bold; height: 25px;">
                    <th width="50">No</th>
                    <th width="200">No. Dokumen</th>
                    <th width="400">Judul Dokumen</th>
                    <th width="120">Tgl. Terbit</th>
                    <th width="60">Edisi</th>
                    <th width="60">Revisi</th>
                    <th width="120">Tgl. Revisi Terakhir</th>
                </tr>
            </thead>
            <tbody>';

        $no = 1;
        foreach ($data as $row) {
            echo '
                <tr>
                    <td align="center" style="vertical-align: middle;">' . $no++ . '</td>
                    <td style="vertical-align: middle;">' . $row->no_dokumen . '</td>
                    <td style="vertical-align: middle;">' . $row->judul . '</td>
                    <td align="center" style="vertical-align: middle;">' . ($row->efektif_date ? date('d/m/Y', strtotime($row->efektif_date)) : '-') . '</td>
                    <td align="center" style="vertical-align: middle;">' . ($row->edisi ?? '0') . '</td>
                    <td align="center" style="vertical-align: middle;">' . ($row->revisi ?? '0') . '</td>
                    <td align="center" style="vertical-align: middle;">' . ($row->dhsh_date ? date('d/m/Y', strtotime($row->dhsh_date)) : '-') . '</td>
                </tr>';
        }

        echo '
            </tbody>
        </table>';

        // Penting: Hentikan eksekusi script agar tidak ada script HTML lain yang ikut masuk ke Excel
        exit;
    }

    public function exportIndukIk()
    {
        $user = Auth::user();
        $userDept = trim($user->departemen);
        $tabel = 'tb_ik_' . strtolower(str_replace(' ', '_', trim($user->departemen))) . 's';
        // 1. Ambil data dari tabel master
        $data = \DB::table($tabel)
                ->where('status_doc', 'Active')
                ->orderBy('no_dokumen', 'asc')
                ->get();

        // 2. Tentukan nama file
        $fileName = "Daftar_Induk_Dokumen_IK_" . $userDept . "_" . date('d-m-Y') . ".xls";

        // 3. Header HTTP untuk memaksa download Excel
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$fileName\"");

        header("Pragma: no-cache");
        header("Expires: 0");

        // 4. Mulai Output Tabel
        echo '
        <table border="1">
            <thead>
                <tr>
                    <th colspan="7" style="font-size: 16px; font-weight: bold; height: 35px; background-color: #4F81BD; color: white; vertical-align: middle;">
                        DAFTAR INDUK DOKUMEN IK ' . strtoupper($userDept) . '
                    </th>
                </tr>
                <tr style="background-color: #D9D9D9; font-weight: bold; height: 25px;">
                    <th width="50">No</th>
                    <th width="200">No. Dokumen</th>
                    <th width="400">Judul Dokumen</th>
                    <th width="120">Tgl. Terbit</th>
                    <th width="60">Edisi</th>
                    <th width="60">Revisi</th>
                    <th width="120">Tgl. Revisi Terakhir</th>
                </tr>
            </thead>
            <tbody>';

        $no = 1;
        foreach ($data as $row) {
            echo '
                <tr>
                    <td align="center" style="vertical-align: middle;">' . $no++ . '</td>
                    <td style="vertical-align: middle;">' . $row->no_dokumen . '</td>
                    <td style="vertical-align: middle;">' . $row->judul . '</td>
                    <td align="center" style="vertical-align: middle;">' . ($row->efektif_date ? date('d/m/Y', strtotime($row->efektif_date)) : '-') . '</td>
                    <td align="center" style="vertical-align: middle;">' . ($row->edisi ?? '0') . '</td>
                    <td align="center" style="vertical-align: middle;">' . ($row->revisi ?? '0') . '</td>
                    <td align="center" style="vertical-align: middle;">' . ($row->dhsh_date ? date('d/m/Y', strtotime($row->dhsh_date)) : '-') . '</td>
                </tr>';
        }

        echo '
            </tbody>
        </table>';

        // Penting: Hentikan eksekusi script agar tidak ada script HTML lain yang ikut masuk ke Excel
        exit;
    }
}
