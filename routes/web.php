<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardAdminController;

/*
|--------------------------------------------------------------------------
| AUTH & LOGIN
|--------------------------------------------------------------------------
*/
Route::get('/', [AuthController::class, 'home'])->name('home');
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

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('auth', AuthController::class);
});

Route::middleware(['auth', 'department:PRODUKSI'])->group(function () {
    Route::resource('dataSopProduksi', App\Http\Controllers\Produksi\sopController::class);
    Route::resource('dataSpProduksi', App\Http\Controllers\Produksi\spController::class);
    Route::resource('dataIkProduksi', App\Http\Controllers\Produksi\ikController::class);
    Route::resource('dataJsaProduksi', App\Http\Controllers\Produksi\jsaController::class);
    Route::resource('dataPxProduksi', App\Http\Controllers\Produksi\pxProduksiController::class);
    Route::resource('dataFkProduksi', App\Http\Controllers\Produksi\fkProduksiController::class);
    Route::resource('dataLinkProduksi', App\Http\Controllers\Produksi\linkproduksiController::class);
    Route::resource('dataReportProduksi', App\Http\Controllers\Produksi\reportController::class);
});

Route::middleware(['auth', 'department:SHE'])->group(function () {
    Route::resource('dataSopShe', App\Http\Controllers\SHE\sopSheController::class);
    Route::resource('dataSpShe', App\Http\Controllers\SHE\spSheController::class);
    Route::resource('dataIkShe', App\Http\Controllers\SHE\ikSheController::class);
    Route::resource('dataJsaShe', App\Http\Controllers\SHE\jsaSheController::class);
    Route::resource('dataPxShe', App\Http\Controllers\SHE\pxSheController::class);
    Route::resource('dataFkShe', App\Http\Controllers\SHE\fkSheController::class);
});

Route::middleware(['auth', 'department:COE'])->group(function () {
    Route::resource('dataSopCoe', App\Http\Controllers\COE\sopCoeController::class);
    Route::resource('dataSpCoe', App\Http\Controllers\COE\spCoeController::class);
    Route::resource('dataIkCoe', App\Http\Controllers\COE\ikCoeController::class);
    Route::resource('dataJsaCoe', App\Http\Controllers\COE\jsaCoeController::class);
    Route::resource('dataPxCoe', App\Http\Controllers\COE\pxCoeController::class);
    Route::resource('dataFkCoe', App\Http\Controllers\COE\fkCoeController::class);
});

Route::middleware(['auth', 'department:FALOG'])->group(function () {
    Route::resource('dataSopFalog', App\Http\Controllers\Falog\sopFalogController::class);
    Route::resource('dataSpFalog', App\Http\Controllers\Falog\spFalogController::class);
    Route::resource('dataIkFalog', App\Http\Controllers\Falog\ikFalogController::class);
    Route::resource('dataJsaFalog', App\Http\Controllers\Falog\jsaFalogController::class);
    Route::resource('dataPxFalog', App\Http\Controllers\Falog\pxFalogController::class);
    Route::resource('dataFkFalog', App\Http\Controllers\Falog\fkFalogController::class);
});

Route::middleware(['auth', 'department:HCGA'])->group(function () {
    Route::resource('dataSopHcga', App\Http\Controllers\HCGA\sopHcgaController::class);
    Route::resource('dataSpHcga', App\Http\Controllers\HCGA\spHcgaController::class);
    Route::resource('dataIkHcga', App\Http\Controllers\HCGA\ikHcgaController::class);
    Route::resource('dataJsaHcga', App\Http\Controllers\HCGA\jsaHcgaController::class);
    Route::resource('dataPxHcga', App\Http\Controllers\HCGA\pxHcgaController::class);
    Route::resource('dataFkHcga', App\Http\Controllers\HCGA\fkHcgaController::class);
});

Route::middleware(['auth', 'department:PLANT'])->group(function () {
    Route::resource('dataSopPlant', App\Http\Controllers\Plant\sopPlantController::class);
    Route::resource('dataSpPlant', App\Http\Controllers\Plant\spPlantController::class);
    Route::resource('dataIkPlant', App\Http\Controllers\Plant\ikPlantController::class);
    Route::resource('dataJsaPlant', App\Http\Controllers\Plant\jsaPlantController::class);
    Route::resource('dataPxPlant', App\Http\Controllers\Plant\pxPlantController::class);
    Route::resource('dataFkPlant', App\Http\Controllers\Plant\fkPlantController::class);
});

Route::middleware(['auth', 'department:ENGINEERING'])->group(function () {
     Route::resource('dataSopEngineering', App\Http\Controllers\Engineering\sopEngineeringController::class);
    Route::resource('dataSpEngineering', App\Http\Controllers\Engineering\spEngineeringController::class);
    Route::resource('dataIkEngineering', App\Http\Controllers\Engineering\ikEngineeringController::class);
    Route::resource('dataJsaEngineering', App\Http\Controllers\Engineering\jsaEngineeringController::class);
    Route::resource('dataPxEngineering', App\Http\Controllers\Engineering\pxEngineeringController::class);
    Route::resource('dataFkEngineering', App\Http\Controllers\Engineering\fkEngineeringController::class);
});

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