<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//PRODUKSI
use App\Http\Controllers\Produksi\Api\sopApiController;
use App\Http\Controllers\Produksi\Api\jsaApiController;
use App\Http\Controllers\Produksi\Api\ikApiController;
use App\Http\Controllers\Produksi\Api\spApiController;
use App\Http\Controllers\Produksi\Api\pxProduksiApiController;
use App\Http\Controllers\Produksi\Api\fkProduksiApiController;
use App\Http\Controllers\Produksi\Api\linkProduksiApiController;

//HCGA
use App\Http\Controllers\HCGA\Api\sopHcgaApiController;
use App\Http\Controllers\HCGA\Api\jsaHcgaApiController;
use App\Http\Controllers\HCGA\Api\ikHcgaApiController;
use App\Http\Controllers\HCGA\Api\spHcgaApiController;
use App\Http\Controllers\HCGA\Api\pxApiHcgaController;
use App\Http\Controllers\HCGA\Api\fkApiHcgaController;

//COE
use App\Http\Controllers\COE\Api\sopCoeApiController;
use App\Http\Controllers\COE\Api\jsaCoeApiController;
use App\Http\Controllers\COE\Api\ikCoeApiController;
use App\Http\Controllers\COE\Api\spCoeApiController;
use App\Http\Controllers\COE\Api\pxApiCoeController;
use App\Http\Controllers\COE\Api\fkCoeApiController;

//PLANT
use App\Http\Controllers\Plant\Api\sopPlantApiController;
use App\Http\Controllers\Plant\Api\jsaPlantApiController;
use App\Http\Controllers\Plant\Api\ikPlantApiController;
use App\Http\Controllers\Plant\Api\spPlantApiController;
use App\Http\Controllers\Plant\Api\pxPlantApiController;
use App\Http\Controllers\Plant\Api\fkPlantApiController;

//FALOG
use App\Http\Controllers\Falog\Api\sopFalogApiController;
use App\Http\Controllers\Falog\Api\jsaFalogApiController;
use App\Http\Controllers\Falog\Api\ikFalogApiController;
use App\Http\Controllers\Falog\Api\spFalogApiController;
use App\Http\Controllers\Falog\Api\pxApiFalogController;
use App\Http\Controllers\Falog\Api\fkApiFalogController;

//SHE
use App\Http\Controllers\SHE\Api\sopSheApiController;
use App\Http\Controllers\SHE\Api\jsaSheApiController;
use App\Http\Controllers\SHE\Api\ikSheApiController;
use App\Http\Controllers\SHE\Api\spSheApiController;
use App\Http\Controllers\SHE\Api\pxSheApiController;
use App\Http\Controllers\SHE\Api\fkSheApiController;

//ENGINEERING
use App\Http\Controllers\Engineering\Api\sopEngineeringApiController;
use App\Http\Controllers\Engineering\Api\jsaEngineeringApiController;
use App\Http\Controllers\Engineering\Api\ikEngineeringApiController;
use App\Http\Controllers\Engineering\Api\spEngineeringApiController;
use App\Http\Controllers\Engineering\Api\pxApiEngineeringController;
use App\Http\Controllers\Engineering\Api\fkApiEngineeringController;

//INFORMASI
use App\Http\Controllers\INFORMASI\Api\instruksiKTTApiController;
use App\Http\Controllers\INFORMASI\Api\posterApiController;
use App\Http\Controllers\INFORMASI\Api\memoApiController;
use App\Http\Controllers\INFORMASI\Api\memoInternalApiController;
use App\Http\Controllers\INFORMASI\Api\msdsApiController;
use App\Http\Controllers\INFORMASI\Api\kebijakanApiController;
use App\Http\Controllers\INFORMASI\Api\bapApiController;
use App\Http\Controllers\INFORMASI\Api\sertifikatSIOApiController;
use App\Http\Controllers\INFORMASI\Api\mocmprpApiController;
use App\Http\Controllers\INFORMASI\Api\ibprApiController;
//AUTH
use App\Http\Controllers\Api\AuthApiController;
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

Route::post('/login', [AuthApiController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthApiController::class, 'user']);
    Route::post('/logout', [AuthApiController::class, 'logout']);
});
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
//PRODUKSI
Route::apiResource('sopProduksi', sopApiController::class);
Route::apiResource('jsaProduksi', jsaApiController::class);
Route::apiResource('ikProduksi', ikApiController::class);
Route::apiResource('spProduksi', spApiController::class);
Route::apiResource('pxProduksi', pxProduksiApiController::class);
Route::apiResource('fkProduksi', fkProduksiApiController::class);
Route::apiResource('linkProduksi', linkProduksiApiController::class);

//COE
Route::apiResource('sopCoe', sopCoeApiController::class);
Route::apiResource('jsaCoe', jsaCoeApiController::class);
Route::apiResource('ikCoe', ikCoeApiController::class);
Route::apiResource('spCoe', spCoeApiController::class);
Route::apiResource('pxCoe', pxApiCoeController::class);
Route::apiResource('fkCoe', fkApiCoeController::class);

//PLANT
Route::apiResource('sopPlant', sopPlantApiController::class);
Route::apiResource('jsaPlant', jsaPlantApiController::class);
Route::apiResource('ikPlant', ikPlantApiController::class);
Route::apiResource('spPlant', spPlantApiController::class);
Route::apiResource('pxPlant', pxPlantApiController::class);
Route::apiResource('fkPlant', fkPlantApiController::class);

//SHE
Route::apiResource('sopShe', sopSheApiController::class);
Route::apiResource('jsaShe', jsaSheApiController::class);
Route::apiResource('ikShe', ikSheApiController::class);
Route::apiResource('spShe', spSheApiController::class);
Route::apiResource('pxShe', pxSheApiController::class);
Route::apiResource('fkShe', fkSheApiController::class);

//HCGA
Route::apiResource('sopHcga', sopHcgaApiController::class);
Route::apiResource('jsaHcga', jsaHcgaApiController::class);
Route::apiResource('ikHcga', ikHcgaApiController::class);
Route::apiResource('spHcga', spHcgaApiController::class);
Route::apiResource('pxHcga', pxApiHcgaController::class);
Route::apiResource('fkHcga', fkApiHcgaController::class);

//ENGINEERING
Route::apiResource('sopEngineering', sopEngineeringApiController::class);
Route::apiResource('jsaEngineering', jsaEngineeringApiController::class);
Route::apiResource('ikEngineering', ikEngineeringApiController::class);
Route::apiResource('spEngineering', spEngineeringApiController::class);
Route::apiResource('pxEngineering', pxApiEngineeringController::class);
Route::apiResource('fkEngineering', fkApiEngineeringController::class);

//FALOG
Route::apiResource('sopFalog', sopFalogApiController::class);
Route::apiResource('jsaFalog', jsaFalogApiController::class);
Route::apiResource('ikFalog', ikFalogApiController::class);
Route::apiResource('spFalog', spFalogApiController::class);
Route::apiResource('pxFalog', pxApiFalogController::class);
Route::apiResource('fkFalog', fkApiFalogController::class);

//INFORMASI
Route::apiResource('instruksiKTT', instruksiKTTApiController::class);
Route::apiResource('poster', posterApiController::class);
Route::apiResource('memo', memoApiController::class);
Route::apiResource('memoInternal', memoInternalApiController::class);
Route::apiResource('kebijakan', kebijakanApiController::class);
Route::apiResource('msds', msdsApiController::class);
Route::apiResource('bap', bapApiController::class);
Route::apiResource('sertifikatSIO', sertifikatSIOApiController::class);
Route::apiResource('mocmprp', mocmprpApiController::class);
Route::apiResource('ibpr', ibprApiController::class);