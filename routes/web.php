<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\AuthController;
//PRODUKSI
use App\Http\Controllers\Produksi\sopController;
use App\Http\Controllers\Produksi\spController;
use App\Http\Controllers\Produksi\ikController;
use App\Http\Controllers\Produksi\jsaController;
//SHE
use App\Http\Controllers\SHE\sopSheController;
use App\Http\Controllers\SHE\spSheController;
use App\Http\Controllers\SHE\ikSheController;
use App\Http\Controllers\SHE\jsaSheController;
//PLANT
use App\Http\Controllers\Plant\sopPlantController;
use App\Http\Controllers\Plant\spPlantController;
use App\Http\Controllers\Plant\ikPlantController;
use App\Http\Controllers\Plant\jsaPlantController;
//FALOG
use App\Http\Controllers\Falog\sopFalogController;
use App\Http\Controllers\Falog\spFalogController;
use App\Http\Controllers\Falog\ikFalogController;
use App\Http\Controllers\Falog\jsaFalogController;
//HCGA
use App\Http\Controllers\HCGA\sopHcgaController;
use App\Http\Controllers\HCGA\spHcgaController;
use App\Http\Controllers\HCGA\ikHcgaController;
use App\Http\Controllers\HCGA\jsaHcgaController;
//COE
use App\Http\Controllers\COE\sopCoeController;
use App\Http\Controllers\COE\spCoeController;
use App\Http\Controllers\COE\ikCoeController;
use App\Http\Controllers\COE\jsaCoeController;
//ENGINEERING
use App\Http\Controllers\Engineering\sopEngineeringController;
use App\Http\Controllers\Engineering\spEngineeringController;
use App\Http\Controllers\Engineering\ikEngineeringController;
use App\Http\Controllers\Engineering\jsaEngineeringController;
//OPD
use App\Http\Controllers\OPD\sopOpdController;
use App\Http\Controllers\OPD\spOpdController;
use App\Http\Controllers\OPD\ikOpdController;
use App\Http\Controllers\OPD\jsaOpdController;


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

    //SHE
    Route::resource('dataSopShe', sopSheController::class)->middleware('auth');
    Route::resource('dataSpShe', spSheController::class)->middleware('auth');
    Route::resource('dataIkShe', ikSheController::class)->middleware('auth');
    Route::resource('dataJsaShe', jsaSheController::class)->middleware('auth');

    //PLANT
    Route::resource('dataSopPlant', sopPlantController::class)->middleware('auth');
    Route::resource('dataSpPlant', spPlantController::class)->middleware('auth');
    Route::resource('dataIkPlant', ikPlantController::class)->middleware('auth');
    Route::resource('dataJsaPlant', jsaPlantController::class)->middleware('auth');

    //FALOG
    Route::resource('dataSopFalog', sopFalogController::class)->middleware('auth');
    Route::resource('dataSpFalog', spFalogController::class)->middleware('auth');
    Route::resource('dataIkFalog', ikFalogController::class)->middleware('auth');
    Route::resource('dataJsaFalog', jsaFalogController::class)->middleware('auth');

    //HCGA
    Route::resource('dataSopHcga', sopHcgaController::class)->middleware('auth');
    Route::resource('dataSpHcga', spHcgaController::class)->middleware('auth');
    Route::resource('dataIkHcga', ikHcgaController::class)->middleware('auth');
    Route::resource('dataJsaHcga', jsaHcgaController::class)->middleware('auth');

    //COE
    Route::resource('dataSopCoe', sopCoeController::class)->middleware('auth');
    Route::resource('dataSpCoe', spCoeController::class)->middleware('auth');
    Route::resource('dataIkCoe', ikCoeController::class)->middleware('auth');
    Route::resource('dataJsaCoe', jsaCoeController::class)->middleware('auth');

    //ENGINEERING
    Route::resource('dataSopEngineering', sopEngineeringController::class)->middleware('auth');
    Route::resource('dataSpEngineering', spEngineeringController::class)->middleware('auth');
    Route::resource('dataIkEngineering', ikEngineeringController::class)->middleware('auth');
    Route::resource('dataJsaEngineering', jsaEngineeringController::class)->middleware('auth');

    //OPD
    Route::resource('dataSopOpd', sopOpdController::class)->middleware('auth');
    Route::resource('dataSpOpd', spOpdController::class)->middleware('auth');
    Route::resource('dataIkOpd', ikOpdController::class)->middleware('auth');
    Route::resource('dataJsaOpd', jsaOpdController::class)->middleware('auth');
});


Route::resource('dashboardAdmin', DashboardAdminController::class)->middleware('auth');
Route::resource('auth', AuthController::class);
Route::get('/', [AuthController::class, 'Home'])->name('Home');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/auth', 'App\Http\Controllers\AuthController@index')->name('auth.index')->middleware('auth');
Route::get('/dashboard', [DashboardAdminController::class,'index'])->middleware('auth');