<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\AuthController;
//PRODUKSI
use App\Http\Controllers\Produksi\sopController;
use App\Http\Controllers\Produksi\spController;
use App\Http\Controllers\Produksi\ikController;
use App\Http\Controllers\Produksi\jsaController;
use App\Http\Controllers\Produksi\pxProduksiController;
use App\Http\Controllers\Produksi\fkProduksiController;
use App\Http\Controllers\Produksi\linkproduksiController;
//SHE
use App\Http\Controllers\SHE\sopSheController;
use App\Http\Controllers\SHE\spSheController;
use App\Http\Controllers\SHE\ikSheController;
use App\Http\Controllers\SHE\jsaSheController;
use App\Http\Controllers\SHE\pxSheController;
use App\Http\Controllers\SHE\fkSheController;
//PLANT
use App\Http\Controllers\Plant\sopPlantController;
use App\Http\Controllers\Plant\spPlantController;
use App\Http\Controllers\Plant\ikPlantController;
use App\Http\Controllers\Plant\jsaPlantController;
use App\Http\Controllers\Plant\pxPlantController;
use App\Http\Controllers\Plant\fkPlantController;
//FALOG
use App\Http\Controllers\Falog\sopFalogController;
use App\Http\Controllers\Falog\spFalogController;
use App\Http\Controllers\Falog\ikFalogController;
use App\Http\Controllers\Falog\jsaFalogController;
use App\Http\Controllers\Falog\pxFalogController;
use App\Http\Controllers\Falog\fkFalogController;
//HCGA
use App\Http\Controllers\HCGA\sopHcgaController;
use App\Http\Controllers\HCGA\spHcgaController;
use App\Http\Controllers\HCGA\ikHcgaController;
use App\Http\Controllers\HCGA\jsaHcgaController;
use App\Http\Controllers\HCGA\pxHcgaController;
use App\Http\Controllers\HCGA\fkHcgaController;
//COE
use App\Http\Controllers\COE\sopCoeController;
use App\Http\Controllers\COE\spCoeController;
use App\Http\Controllers\COE\ikCoeController;
use App\Http\Controllers\COE\jsaCoeController;
use App\Http\Controllers\COE\pxCoeController;
use App\Http\Controllers\COE\fkCoeController;
//ENGINEERING
use App\Http\Controllers\Engineering\sopEngineeringController;
use App\Http\Controllers\Engineering\spEngineeringController;
use App\Http\Controllers\Engineering\ikEngineeringController;
use App\Http\Controllers\Engineering\jsaEngineeringController;
use App\Http\Controllers\Engineering\pxEngineeringController;
use App\Http\Controllers\Engineering\fkEngineeringController;

//Informasi
use App\Http\Controllers\INFORMASI\kebijakanController;
use App\Http\Controllers\INFORMASI\posterController;
use App\Http\Controllers\INFORMASI\memoController;
use App\Http\Controllers\INFORMASI\memoInternalController;
use App\Http\Controllers\INFORMASI\instruksiKttController;
use App\Http\Controllers\INFORMASI\msdsController;
use App\Http\Controllers\INFORMASI\bapController;
use App\Http\Controllers\INFORMASI\sertifikatSIOController;
use App\Http\Controllers\INFORMASI\mocmprpController;
use App\Http\Controllers\INFORMASI\ibprController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth', '0'])->group(function () {
    Route::resource('auth', AuthController::class);
    
    //PRODUKSI
    Route::resource('dataSopProduksi', sopController::class)->middleware('auth');
    Route::resource('dataSpProduksi', spController::class)->middleware('auth');
    Route::resource('dataIkProduksi', ikController::class)->middleware('auth');
    Route::resource('dataJsaProduksi', jsaController::class)->middleware('auth');
    Route::resource('dataPxProduksi', pxProduksiController::class)->middleware('auth');
    Route::resource('dataFkProduksi', fkProduksiController::class)->middleware('auth');
    Route::resource('dataLinkProduksi', linkProduksiController::class)->middleware('auth');
    

    //SHE
    Route::resource('dataSopShe', sopSheController::class)->middleware('auth');
    Route::resource('dataSpShe', spSheController::class)->middleware('auth');
    Route::resource('dataIkShe', ikSheController::class)->middleware('auth');
    Route::resource('dataJsaShe', jsaSheController::class)->middleware('auth');
    Route::resource('dataPxShe', pxSheController::class)->middleware('auth');
    Route::resource('dataFkShe', fkSheController::class)->middleware('auth');

    //PLANT
    Route::resource('dataSopPlant', sopPlantController::class)->middleware('auth');
    Route::resource('dataSpPlant', spPlantController::class)->middleware('auth');
    Route::resource('dataIkPlant', ikPlantController::class)->middleware('auth');
    Route::resource('dataJsaPlant', jsaPlantController::class)->middleware('auth');
    Route::resource('dataPxPlant', pxPlantController::class)->middleware('auth');
    Route::resource('dataFkPlant', fkPlantController::class)->middleware('auth');

    //FALOG
    Route::resource('dataSopFalog', sopFalogController::class)->middleware('auth');
    Route::resource('dataSpFalog', spFalogController::class)->middleware('auth');
    Route::resource('dataIkFalog', ikFalogController::class)->middleware('auth');
    Route::resource('dataJsaFalog', jsaFalogController::class)->middleware('auth');
    Route::resource('dataPxFalog', pxFalogController::class)->middleware('auth');
    Route::resource('dataFkFalog', fkFalogController::class)->middleware('auth');

    //HCGA
    Route::resource('dataSopHcga', sopHcgaController::class)->middleware('auth');
    Route::resource('dataSpHcga', spHcgaController::class)->middleware('auth');
    Route::resource('dataIkHcga', ikHcgaController::class)->middleware('auth');
    Route::resource('dataJsaHcga', jsaHcgaController::class)->middleware('auth');
    Route::resource('dataPxHcga', pxHcgaController::class)->middleware('auth');
    Route::resource('dataFkHcga', fkHcgaController::class)->middleware('auth');

    //COE
    Route::resource('dataSopCoe', sopCoeController::class)->middleware('auth');
    Route::resource('dataSpCoe', spCoeController::class)->middleware('auth');
    Route::resource('dataIkCoe', ikCoeController::class)->middleware('auth');
    Route::resource('dataJsaCoe', jsaCoeController::class)->middleware('auth');
    Route::resource('dataPxCoe', pxCoeController::class)->middleware('auth');
    Route::resource('dataFkCoe', fkCoeController::class)->middleware('auth');

    //ENGINEERING
    Route::resource('dataSopEngineering', sopEngineeringController::class)->middleware('auth');
    Route::resource('dataSpEngineering', spEngineeringController::class)->middleware('auth');
    Route::resource('dataIkEngineering', ikEngineeringController::class)->middleware('auth');
    Route::resource('dataJsaEngineering', jsaEngineeringController::class)->middleware('auth');
    Route::resource('dataPxEngineering', pxEngineeringController::class)->middleware('auth');
    Route::resource('dataFkEngineering', fkEngineeringController::class)->middleware('auth');

    //Informasi
    Route::resource('dataKebijakan', kebijakanController::class)->middleware('auth');
    Route::resource('dataPoster', posterController::class)->middleware('auth');
    Route::resource('dataMemo', memoController::class)->middleware('auth');
    Route::resource('dataMemoInternal', memoInternalController::class)->middleware('auth');
    Route::resource('dataMsds', msdsController::class)->middleware('auth');
    Route::resource('dataInstruksiKtt', instruksiKttController::class)->middleware('auth');
    Route::resource('dataBap', bapController::class)->middleware('auth');
    Route::resource('dataSertifikatSIO', sertifikatSIOController::class)->middleware('auth');
    Route::resource('dataMocMprp', mocmprpController::class)->middleware('auth');
    Route::resource('dataIbpr', ibprController::class)->middleware('auth');

});


Route::resource('dashboardAdmin', DashboardAdminController::class)->middleware('auth');
Route::resource('auth', AuthController::class);
Route::get('/', [AuthController::class, 'Home'])->name('Home');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/auth', 'App\Http\Controllers\AuthController@index')->name('auth.index')->middleware('auth');
Route::get('/dashboard', [DashboardAdminController::class,'index'])->middleware('auth');