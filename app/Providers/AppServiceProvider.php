<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\DokumenBaru\sop_baru;
use App\Models\DokumenBaru\sp_baru;
use App\Models\DokumenBaru\ik_baru;
use App\Models\DokumenBaru\JSA\jsa_baru;
use App\Models\DokumenRevisi\sop_revisi;
use App\Models\DokumenRevisi\sp_revisi;
use App\Models\DokumenRevisi\ik_revisi;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Suntikkan daftar dokumen yang menunggu persetujuan user yang login ke navbar.
        View::composer('partials.navbar', function ($view) {
            $items = [];

            if (Auth::check()) {
                try {
                    $items = $this->pendingApprovals(Auth::user()->nama);
                } catch (\Throwable $e) {
                    // Jangan biarkan error notifikasi merusak seluruh halaman.
                    $items = [];
                }
            }

            $view->with('notifItems', $items);
            $view->with('notifTotal', array_sum(array_column($items, 'count')));
        });
    }

    /**
     * Hitung dokumen yang menunggu aksi/persetujuan dari user (berdasarkan nama).
     * Mengembalikan array bucket: ['label','count','url','icon'].
     */
    private function pendingApprovals($nama)
    {
        $buckets = [];

        // Helper: kondisi "belum diputuskan" (null / waiting / selain approved-rejected).
        // Mengembalikan closure untuk dipakai di ->where(...) sebagai grup nested where.
        $undecided = function ($col) {
            return function ($w) use ($col) {
                $w->whereNull($col)->orWhereNotIn($col, ['approved', 'rejected']);
            };
        };

        // ---------- SOP Baru ----------
        $sopDH = sop_baru::where('DHdanSH', $nama)
            ->where('status_doc', '!=', 'Rejected')
            ->where($undecided('DHdanSHApprove'))
            ->count();

        $sopPJO = sop_baru::where('PJO', $nama)
            ->where('DHdanSHApprove', 'approved')
            ->where('status_doc', '!=', 'Rejected')
            ->where($undecided('PJOApprove'))
            ->count();

        // SOP people approval (decode JSON, cek status 'pending' untuk user ini)
        $sopPeople = 0;
        foreach (sop_baru::where('status_doc', '!=', 'Rejected')
                    ->where('people_approve', 'like', '%' . $nama . '%')
                    ->pluck('people_approve') as $raw) {
            $status = json_decode($raw, true) ?? [];
            if (($status[$nama] ?? null) === 'pending') {
                $sopPeople++;
            }
        }

        $sopTotal = $sopDH + $sopPJO + $sopPeople;
        if ($sopTotal > 0) {
            $buckets[] = [
                'label' => 'SOP menunggu persetujuan Anda',
                'count' => $sopTotal,
                'url'   => route('dataSopBaru.index'),
                'icon'  => 'bx-file',
            ];
        }

        // ---------- SP Baru ----------
        $spDH = sp_baru::where('DHdanSH', $nama)
            ->where('status_doc', '!=', 'Rejected')
            ->where($undecided('DHdanSHApprove'))
            ->count();
        if ($spDH > 0) {
            $buckets[] = [
                'label' => 'SP menunggu persetujuan Anda',
                'count' => $spDH,
                'url'   => route('dataSpBaru.index'),
                'icon'  => 'bx-file',
            ];
        }

        // ---------- IK Baru ----------
        $ikDH = ik_baru::where('DHdanSH', $nama)
            ->where('status_doc', '!=', 'Rejected')
            ->where($undecided('DHdanSHApprove'))
            ->count();
        if ($ikDH > 0) {
            $buckets[] = [
                'label' => 'IK menunggu persetujuan Anda',
                'count' => $ikDH,
                'url'   => route('dataIkBaru.index'),
                'icon'  => 'bx-file',
            ];
        }

        // ---------- JSA Baru ----------
        $jsaReview = jsa_baru::where('direview_oleh', $nama)
            ->whereNull('direview_oleh_approve')
            ->count();
        $jsaApprove = jsa_baru::where('disetujui_oleh', $nama)
            ->where('direview_oleh_approve', 'approved')
            ->whereNull('disetujui_oleh_approve')
            ->count();
        $jsaTotal = $jsaReview + $jsaApprove;
        if ($jsaTotal > 0) {
            $buckets[] = [
                'label' => ($jsaReview > 0 && $jsaApprove === 0)
                    ? 'JSA menunggu review Anda'
                    : 'JSA menunggu persetujuan Anda',
                'count' => $jsaTotal,
                'url'   => route('dataJsaBaru.index'),
                'icon'  => 'bx-shield-quarter',
            ];
        }

        // ---------- Revisi (SOP / SP / IK) ----------
        $sopRevDH = sop_revisi::where('DHdanSH', $nama)
            ->whereNotIn('status_doc', ['Rejected', 'Active'])
            ->where($undecided('DHdanSHApprove'))
            ->count();
        $sopRevPJO = sop_revisi::where('PJO', $nama)
            ->where('DHdanSHApprove', 'approved')
            ->whereNotIn('status_doc', ['Rejected', 'Active'])
            ->where($undecided('PJOApprove'))
            ->count();
        if (($sopRevDH + $sopRevPJO) > 0) {
            $buckets[] = [
                'label' => 'Revisi SOP menunggu persetujuan Anda',
                'count' => $sopRevDH + $sopRevPJO,
                'url'   => route('dataSopRevisi.index'),
                'icon'  => 'bx-edit',
            ];
        }

        $spRevDH = sp_revisi::where('DHdanSH', $nama)
            ->whereNotIn('status_doc', ['Rejected', 'Active'])
            ->where($undecided('DHdanSHApprove'))
            ->count();
        if ($spRevDH > 0) {
            $buckets[] = [
                'label' => 'Revisi SP menunggu persetujuan Anda',
                'count' => $spRevDH,
                'url'   => route('dataSpRevisi.index'),
                'icon'  => 'bx-edit',
            ];
        }

        $ikRevDH = ik_revisi::where('DHdanSH', $nama)
            ->whereNotIn('status_doc', ['Rejected', 'Active'])
            ->where($undecided('DHdanSHApprove'))
            ->count();
        if ($ikRevDH > 0) {
            $buckets[] = [
                'label' => 'Revisi IK menunggu persetujuan Anda',
                'count' => $ikRevDH,
                'url'   => route('dataIkRevisi.index'),
                'icon'  => 'bx-edit',
            ];
        }

        return $buckets;
    }
}
