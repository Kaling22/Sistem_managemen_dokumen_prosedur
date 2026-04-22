<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardAdminController;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| AUTH & LOGIN
|--------------------------------------------------------------------------
*/
Route::get('/', [AuthController::class, 'home'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

/*
|--------------------------------------------------------------------------
| DASHBOARD (SEMUA USER LOGIN)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardAdminController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| ADMIN ONLY (ROLE = 0)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin:0,1'])->group(function () {
    Route::resource('auth', AuthController::class);
});

Route::middleware(['auth'])->group(function () {
    Route::post('/dokumen/{id}/approve-dhsh', [App\Http\Controllers\newDocumentController::class, 'approveDHSH'])
    ->name('dokumen.approve.dhsh');
    
    Route::post('/dokumen/{id}/approve-pjo', [App\Http\Controllers\newDocumentController::class, 'approvePJO'])
    ->name('dokumen.approve.pjo');
    
    Route::get('/dokumen/{id}/approve', [App\Http\Controllers\newDocumentController::class, 'approveView'])
    ->name('dokumen.approve.view');

    Route::post('/dokumen/{id}/approve-save', [App\Http\Controllers\newDocumentController::class, 'approveSave'])
    ->name('dokumen.approve.save');

    Route::post('/dokumen/reject/{id}', [App\Http\Controllers\newDocumentController::class, 'reject'])->name('dokumen.reject');
    
    Route::resource('dataReportDoc', App\Http\Controllers\reportController::class);
    
    //edit
    Route::get('/get-nomor-dokumen/{jenis}', [App\Http\Controllers\editDocumentController::class, 'getNomorDokumen']);
    Route::get('/get-daftar-atasan', [App\Http\Controllers\editDocumentController::class, 'getAtasan']);
    Route::get('/get-daftar-pjo', [App\Http\Controllers\editDocumentController::class, 'getAtasanPJO']);
    
    
    //dokumen baru
    //SOP
    Route::post('/sop-baru/approve-people', [App\Http\Controllers\DokumenBaru\tb_sop_baru::class, 'approvePeople'])->name('sop.people.approve');
    Route::resource('dataSopBaru', App\Http\Controllers\DokumenBaru\tb_sop_baru::class);
    Route::post('/dokumen/approve-dhsh', [App\Http\Controllers\DokumenBaru\tb_sop_baru::class, 'approveDHSH'])->name('dokumen.approve.dhsh');
    Route::post('/dokumen/approve-pjo', [App\Http\Controllers\DokumenBaru\tb_sop_baru::class, 'approvePJO'])->name('dokumen.approve.pjo');
    Route::get('/api/get-approvers', function () {
        return response()->json([
            // Role 3 (Section Head) & 4 (Dept Head) untuk dropdown verifikasi
            'dhsh' => User::whereIn('role', [3, 4])->orderBy('nama', 'asc')->get(['nama', 'role', 'nrp']),
            
            // Role 2 (PJO) untuk dropdown persetujuan
            'pjo'  => User::where('role', 2)->orderBy('nama', 'asc')->get(['nama', 'nrp']),
            
            // TAMBAHKAN INI: Ambil SEMUA untuk Pembuat Tambahan (Role 0 sampai 6)
            'all_users' => User::whereIn('role', [0, 1, 5, 6])
                            ->orderBy('nama', 'asc')
                            ->get(['nama', 'nrp', 'role'])
        ]);
    });
    //SP
    Route::resource('dataSpBaru', App\Http\Controllers\DokumenBaru\tb_sp_baru::class);
    Route::post('/dokumen/approve-dhsh-sp', [App\Http\Controllers\DokumenBaru\tb_sp_baru::class, 'approveDHSH'])->name('dokumen.approve.dhshsp');
    
    //IK
    Route::resource('dataIkBaru', App\Http\Controllers\DokumenBaru\tb_ik_baru::class);
    Route::post('/dokumen/approve-dhsh-ik', [App\Http\Controllers\DokumenBaru\tb_ik_baru::class, 'approveDHSH'])->name('dokumen.approve.dhshik');

    //JSA
    Route::resource('dataJsaBaru', App\Http\Controllers\DokumenBaru\tb_jsa_baru::class);
    Route::get('/dataJsaBaru/{id}/review', [App\Http\Controllers\DokumenBaru\tb_jsa_baru::class, 'review'])
        ->name('dataJsaBaru.review');
    Route::put('/dataJsaBaru/{id}/review/update', [App\Http\Controllers\DokumenBaru\tb_jsa_baru::class, 'updateReview'])
        ->name('dataJsaBaru.updateReview');

    
    //dokumen revisi SOP
    // --- DOKUMEN REVISI SOP ---
    Route::resource('dataSopRevisi', App\Http\Controllers\DokumenRevisi\tb_sop_revisi::class);
    Route::post('/revisi/sop/approve-dhsh', [App\Http\Controllers\DokumenRevisi\tb_sop_revisi::class, 'approveDHSH'])
        ->name('revisi.sop.approve.dhsh');
        
    Route::post('/revisi/sop/approve-pjo', [App\Http\Controllers\DokumenRevisi\tb_sop_revisi::class, 'approvePJO'])
        ->name('revisi.sop.approve.pjo');
    Route::get('/check-revisi/sop/{no_dokumen}', [App\Http\Controllers\DokumenRevisi\tb_sop_revisi::class, 'checkPendingRevision'])
        ->name('check.revisi.sop');

    // --- DOKUMEN REVISI SP ---
    Route::resource('dataSpRevisi', App\Http\Controllers\DokumenRevisi\tb_sp_revisi::class);
    Route::post('/revisi/sp/approve-dhsh', [App\Http\Controllers\DokumenRevisi\tb_sp_revisi::class, 'approveDHSH'])
        ->name('revisi.sp.approve.dhsh');
    Route::get('/check-revisi/sp/{no_dokumen}', [App\Http\Controllers\DokumenRevisi\tb_sp_revisi::class, 'checkPendingRevision'])
        ->name('check.revisi.sp');

    // --- DOKUMEN REVISI IK ---
    Route::resource('dataIkRevisi', App\Http\Controllers\DokumenRevisi\tb_ik_revisi::class);
    Route::post('/revisi/ik/approve-dhsh', [App\Http\Controllers\DokumenRevisi\tb_ik_revisi::class, 'approveDHSH'])
        ->name('revisi.ik.approve.dhsh');
    Route::get('/check-revisi/ik/{no_dokumen}', [App\Http\Controllers\DokumenRevisi\tb_ik_revisi::class, 'checkPendingRevision'])
        ->name('check.revisi.ik');

    //Daftar Induk Dokumen
    Route::get('/export-induk-sop', [App\Http\Controllers\DaftarIndukDokumen\daftar_induk_dokumen::class, 'exportIndukSop'])->name('export.induk.sop');
    Route::get('/export-induk-sp', [App\Http\Controllers\DaftarIndukDokumen\daftar_induk_dokumen::class, 'exportIndukSp'])->name('export.induk.sp');
    Route::get('/export-induk-ik', [App\Http\Controllers\DaftarIndukDokumen\daftar_induk_dokumen::class, 'exportIndukIk'])->name('export.induk.ik');
});

// Route::middleware(['auth', 'department:PRODUKSI'])->group(function () {
    Route::get('/dataSopProduksi/inactive', [App\Http\Controllers\Produksi\sopProduksiController::class, 'indexInactive'])->name('dataSopProduksi.inactive');
    Route::resource('dataSopProduksi', App\Http\Controllers\Produksi\sopProduksiController::class);
    //sop Inactive
    Route::get('/dataSpProduksi/inactive', [App\Http\Controllers\Produksi\spProduksiController::class, 'indexInactive'])->name('dataSpProduksi.inactive');
    Route::resource('dataSpProduksi', App\Http\Controllers\Produksi\spProduksiController::class);
    //ik Inactive
    Route::get('/dataIkProduksi/inactive', [App\Http\Controllers\Produksi\ikProduksiController::class, 'indexInactive'])->name('dataIkProduksi.inactive');
    Route::resource('dataIkProduksi', App\Http\Controllers\Produksi\ikProduksiController::class);
    Route::resource('dataJsaProduksi', App\Http\Controllers\Produksi\jsaProduksiController::class);
    Route::resource('dataPxProduksi', App\Http\Controllers\Produksi\pxProduksiController::class);
    Route::resource('dataFkProduksi', App\Http\Controllers\Produksi\fkProduksiController::class);
    Route::resource('dataLinkProduksi', App\Http\Controllers\Produksi\linkproduksiController::class);
    
// });

// Route::middleware(['auth', 'department:SHE'])->group(function () {
    Route::get('/dataSopShe/inactive', [App\Http\Controllers\She\sopSheController::class, 'indexInactive'])->name('dataSopShe.inactive');
    Route::resource('dataSopShe', App\Http\Controllers\She\sopSheController::class);
    //sop Inactive
    Route::get('/dataSpShe/inactive', [App\Http\Controllers\She\spSheController::class, 'indexInactive'])->name('dataSpShe.inactive');
    Route::resource('dataSpShe', App\Http\Controllers\She\spSheController::class);
    //ik Inactive
    Route::get('/dataIkShe/inactive', [App\Http\Controllers\She\ikSheController::class, 'indexInactive'])->name('dataIkShe.inactive');
    Route::resource('dataIkShe', App\Http\Controllers\She\ikSheController::class);
    Route::resource('dataJsaShe', App\Http\Controllers\She\jsaSheController::class);
    Route::resource('dataPxShe', App\Http\Controllers\She\pxSheController::class);
    Route::resource('dataFkShe', App\Http\Controllers\She\fkSheController::class);
// });

// Route::middleware(['auth', 'department:ICTMD'])->group(function () {
    Route::get('/dataSopIctmd/inactive', [App\Http\Controllers\ICTMD\sopIctmdController::class, 'indexInactive'])->name('dataSopIctmd.inactive');
    Route::resource('dataSopIctmd', App\Http\Controllers\ICTMD\sopIctmdController::class);
    //sop Inactive
    Route::get('/dataSpIctmd/inactive', [App\Http\Controllers\ICTMD\spIctmdController::class, 'indexInactive'])->name('dataSpIctmd.inactive');
    Route::resource('dataSpIctmd', App\Http\Controllers\ICTMD\spIctmdController::class);
    //ik Inactive
    Route::get('/dataIkIctmd/inactive', [App\Http\Controllers\ICTMD\ikIctmdController::class, 'indexInactive'])->name('dataIkIctmd.inactive');
    Route::resource('dataIkIctmd', App\Http\Controllers\ICTMD\ikIctmdController::class);
    Route::resource('dataJsaIctmd', App\Http\Controllers\ICTMD\jsaIctmdController::class);
    Route::resource('dataPxIctmd', App\Http\Controllers\ICTMD\pxIctmdController::class);
    Route::resource('dataFkIctmd', App\Http\Controllers\ICTMD\fkIctmdController::class);
// });

// Route::middleware(['auth', 'department:FALOG'])->group(function () {
    Route::get('/dataSopFalog/inactive', [App\Http\Controllers\Falog\sopFalogController::class, 'indexInactive'])->name('dataSopFalog.inactive');
    Route::resource('dataSopFalog', App\Http\Controllers\Falog\sopFalogController::class);
    //sop Inactive
    Route::get('/dataSpFalog/inactive', [App\Http\Controllers\Falog\spFalogController::class, 'indexInactive'])->name('dataSpFalog.inactive');
    Route::resource('dataSpFalog', App\Http\Controllers\Falog\spFalogController::class);
    //ik Inactive
    Route::get('/dataIkFalog/inactive', [App\Http\Controllers\Falog\ikFalogController::class, 'indexInactive'])->name('dataIkFalog.inactive');
    Route::resource('dataIkFalog', App\Http\Controllers\Falog\ikFalogController::class);
    Route::resource('dataJsaFalog', App\Http\Controllers\Falog\jsaFalogController::class);
    Route::resource('dataPxFalog', App\Http\Controllers\Falog\pxFalogController::class);
    Route::resource('dataFkFalog', App\Http\Controllers\Falog\fkFalogController::class);
// });

// Route::middleware(['auth', 'department:HCGA'])->group(function () {
        Route::get('/dataSopHcga/inactive', [App\Http\Controllers\Hcga\sopHcgaController::class, 'indexInactive'])->name('dataSopHcga.inactive');
    Route::resource('dataSopHcga', App\Http\Controllers\Hcga\sopHcgaController::class);
    //sop Inactive
    Route::get('/dataSpHcga/inactive', [App\Http\Controllers\Hcga\spHcgaController::class, 'indexInactive'])->name('dataSpHcga.inactive');
    Route::resource('dataSpHcga', App\Http\Controllers\Hcga\spHcgaController::class);
    //ik Inactive
    Route::get('/dataIkHcga/inactive', [App\Http\Controllers\Hcga\ikHcgaController::class, 'indexInactive'])->name('dataIkHcga.inactive');
    Route::resource('dataIkHcga', App\Http\Controllers\Hcga\ikHcgaController::class);
    Route::resource('dataJsaHcga', App\Http\Controllers\Hcga\jsaHcgaController::class);
    Route::resource('dataPxHcga', App\Http\Controllers\Hcga\pxHcgaController::class);
    Route::resource('dataFkHcga', App\Http\Controllers\Hcga\fkHcgaController::class);
// });

// Route::middleware(['auth', 'department:PLANT'])->group(function () {
        Route::get('/dataSopPlant/inactive', [App\Http\Controllers\Plant\sopPlantController::class, 'indexInactive'])->name('dataSopPlant.inactive');
    Route::resource('dataSopPlant', App\Http\Controllers\Plant\sopPlantController::class);
    //sop Inactive
    Route::get('/dataSpPlant/inactive', [App\Http\Controllers\Plant\spPlantController::class, 'indexInactive'])->name('dataSpPlant.inactive');
    Route::resource('dataSpPlant', App\Http\Controllers\Plant\spPlantController::class);
    //ik Inactive
    Route::get('/dataIkPlant/inactive', [App\Http\Controllers\Plant\ikPlantController::class, 'indexInactive'])->name('dataIkPlant.inactive');
    Route::resource('dataIkPlant', App\Http\Controllers\Plant\ikPlantController::class);
    Route::resource('dataJsaPlant', App\Http\Controllers\Plant\jsaPlantController::class);
    Route::resource('dataPxPlant', App\Http\Controllers\Plant\pxPlantController::class);
    Route::resource('dataFkPlant', App\Http\Controllers\Plant\fkPlantController::class);
// });

// Route::middleware(['auth', 'department:ENGINEERING'])->group(function () {
    Route::get('/dataSopEngineering/inactive', [App\Http\Controllers\Engineering\sopEngineeringController::class, 'indexInactive'])->name('dataSopEngineering.inactive');
    Route::resource('dataSopEngineering', App\Http\Controllers\Engineering\sopEngineeringController::class);
    //sop Inactive
    Route::get('/dataSpEngineering/inactive', [App\Http\Controllers\Engineering\spEngineeringController::class, 'indexInactive'])->name('dataSpEngineering.inactive');
    Route::resource('dataSpEngineering', App\Http\Controllers\Engineering\spEngineeringController::class);
    //ik Inactive
    Route::get('/dataIkEngineering/inactive', [App\Http\Controllers\Engineering\ikEngineeringController::class, 'indexInactive'])->name('dataIkEngineering.inactive');
    Route::resource('dataIkEngineering', App\Http\Controllers\Engineering\ikEngineeringController::class);
    Route::resource('dataJsaEngineering', App\Http\Controllers\Engineering\jsaEngineeringController::class);
    Route::resource('dataPxEngineering', App\Http\Controllers\Engineering\pxEngineeringController::class);
    Route::resource('dataFkEngineering', App\Http\Controllers\Engineering\fkEngineeringController::class);
// });

Route::middleware(['auth'])->group(function () {
    Route::resource('dataKebijakan', App\Http\Controllers\INFORMASI\kebijakanController::class);
    Route::resource('dataPoster', App\Http\Controllers\INFORMASI\PosterController::class);
    Route::resource('dataMemo', App\Http\Controllers\INFORMASI\MemoController::class);
    Route::resource('dataMemoInternal', App\Http\Controllers\INFORMASI\memoInternalController::class);
    Route::resource('dataMsds', App\Http\Controllers\INFORMASI\msdsController::class);
    Route::resource('dataInstruksiKtt', App\Http\Controllers\INFORMASI\instruksiKTTController::class);
    Route::resource('dataBap', App\Http\Controllers\INFORMASI\bapController::class);
    Route::resource('dataSertifikatSIO', App\Http\Controllers\INFORMASI\sertifikatSIOController::class);
    Route::resource('dataMocMprp', App\Http\Controllers\INFORMASI\mocmprpController::class);
    Route::resource('dataIbpr', App\Http\Controllers\INFORMASI\ibprController::class);

});