<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//PRODUKSI
use App\Http\Controllers\Produksi\Api\sopApiController;
use App\Http\Controllers\Produksi\Api\jsaApiController;
use App\Http\Controllers\Produksi\Api\ikApiController;
use App\Http\Controllers\Produksi\Api\spApiController;

//HCGA
use App\Http\Controllers\HCGA\Api\sopHcgaApiController;
use App\Http\Controllers\HCGA\Api\jsaHcgaApiController;
use App\Http\Controllers\HCGA\Api\ikHcgaApiController;
use App\Http\Controllers\HCGA\Api\spHcgaApiController;

//COE
use App\Http\Controllers\COE\Api\sopCoeApiController;
use App\Http\Controllers\COE\Api\jsaCoeApiController;
use App\Http\Controllers\COE\Api\ikCoeApiController;
use App\Http\Controllers\COE\Api\spCoeApiController;

//PLANT
use App\Http\Controllers\Plant\Api\sopPlantApiController;
use App\Http\Controllers\Plant\Api\jsaPlantApiController;
use App\Http\Controllers\Plant\Api\ikPlantApiController;
use App\Http\Controllers\Plant\Api\spPlantApiController;

//FALOG
use App\Http\Controllers\Falog\Api\sopFalogApiController;
use App\Http\Controllers\Falog\Api\jsaFalogApiController;
use App\Http\Controllers\Falog\Api\ikFalogApiController;
use App\Http\Controllers\Falog\Api\spFalogApiController;

//SHE
use App\Http\Controllers\SHE\Api\sopSheApiController;
use App\Http\Controllers\SHE\Api\jsaSheApiController;
use App\Http\Controllers\SHE\Api\ikSheApiController;
use App\Http\Controllers\SHE\Api\spSheApiController;

//ENGINEERING
use App\Http\Controllers\Engineering\Api\sopEngineeringApiController;
use App\Http\Controllers\Engineering\Api\jsaEngineeringApiController;
use App\Http\Controllers\Engineering\Api\ikEngineeringApiController;
use App\Http\Controllers\Engineering\Api\spEngineeringApiController;

//OPD
use App\Http\Controllers\OPD\Api\sopOpdApiController;
use App\Http\Controllers\OPD\Api\jsaOpdApiController;
use App\Http\Controllers\OPD\Api\ikOpdApiController;
use App\Http\Controllers\OPD\Api\spOpdApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
//PRODUKSI
Route::apiResource('sopProduksi', sopApiController::class);
Route::apiResource('jsaProduksi', jsaApiController::class);
Route::apiResource('ikProduksi', ikApiController::class);
Route::apiResource('spProduksi', spApiController::class);

//COE
Route::apiResource('sopCoe', sopCoeApiController::class);
Route::apiResource('jsaCoe', jsaCoeApiController::class);
Route::apiResource('ikCoe', ikCoeApiController::class);
Route::apiResource('spCoe', spCoeApiController::class);

//PLANT
Route::apiResource('sopPlant', sopPlantApiController::class);
Route::apiResource('jsaPlant', jsaPlantApiController::class);
Route::apiResource('ikPlant', ikPlantApiController::class);
Route::apiResource('spPlant', spPlantApiController::class);

//SHE
Route::apiResource('sopShe', sopSheApiController::class);
Route::apiResource('jsaShe', jsaSheApiController::class);
Route::apiResource('ikShe', ikSheApiController::class);
Route::apiResource('spShe', spSheApiController::class);

//HCGA
Route::apiResource('sopHcga', sopHcgaApiController::class);
Route::apiResource('jsaHcga', jsaHcgaApiController::class);
Route::apiResource('ikHcga', ikHcgaApiController::class);
Route::apiResource('spHcga', spHcgaApiController::class);

//ENGINEERING
Route::apiResource('sopEngineering', sopEngineeringApiController::class);
Route::apiResource('jsaEngineering', jsaEngineeringApiController::class);
Route::apiResource('ikEngineering', ikEngineeringApiController::class);
Route::apiResource('spEngineering', spEngineeringApiController::class);
