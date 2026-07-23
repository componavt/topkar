<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\RistikanzaToponymController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware(['auth.topkar', 'api.locale', 'topkar.database'])
    ->prefix('ristikanza/nladoga')
    ->group(function () {
        Route::get('oikonyms/form-values', [RistikanzaToponymController::class, 'oikonymFormValues']);
        Route::get('oikonyms/map', [RistikanzaToponymController::class, 'map']);
        Route::get('oikonyms/sources', [RistikanzaToponymController::class, 'oikonymSources']);
        Route::get('oikonyms/selsovets1926', [RistikanzaToponymController::class, 'oikonymSelsovets1926']);
        Route::get('oikonyms/settlements', [RistikanzaToponymController::class, 'oikonymSettlements']);
        Route::get('oikonyms/settlements1926', [RistikanzaToponymController::class, 'oikonymSettlements1926']);
        Route::get('oikonyms/{id}', [RistikanzaToponymController::class, 'show']);
        Route::get('oikonyms', [RistikanzaToponymController::class, 'index']);
    });
