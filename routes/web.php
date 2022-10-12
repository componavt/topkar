<?php

//use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\WelcomeController;

use App\Http\Controllers\Library\ServiceController;

use App\Http\Controllers\Dict\DistrictController;
use App\Http\Controllers\Dict\District1926Controller;
use App\Http\Controllers\Dict\RegionController;
use App\Http\Controllers\Dict\Selsovet1926Controller;
use App\Http\Controllers\Dict\SettlementController;
use App\Http\Controllers\Dict\Settlement1926Controller;
use App\Http\Controllers\Dict\SourceController;
use App\Http\Controllers\Dict\TopnameController;
use App\Http\Controllers\Dict\ToponymController;

use App\Http\Controllers\Misc\GeotypeController;
use App\Http\Controllers\Misc\InformantController;
use App\Http\Controllers\Misc\RecorderController;
use App\Http\Controllers\Misc\StructController;

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

Route::group(['prefix' => LaravelLocalization::setLocale()], function()
{
    /** ALL LOCALIZED ROUTES INSIDE THIS GROUP **/
    Route::get('/', function () {
        return view('welcome'); // return view('dashboard');
    });

//    Route::get('/service/tmp_fill_events', [ServiceController::class, 'tmp_fill_events']);
//    Route::get('/service/tmp_fill_settlements', [ServiceController::class, 'tmp_fill_settlements']);
//    Route::get('/service/tmp_fill_name_for_search', [ServiceController::class, 'tmp_fill_name_for_search']);
//    Route::get('/service/tmp_fill_sources', [ServiceController::class, 'tmp_fill_sources']);
//    Route::get('/service/tmp_fill_topnames_from_variants', [ServiceController::class, 'tmp_fill_topnames_from_variants']);

    Route::get('/misc/informants/list', [InformantController::class, 'list']);
    Route::get('/misc/recorders/list', [RecorderController::class, 'list']);
    Route::get('/misc/structs/list', [StructController::class, 'list']);
    
    Route::get('/dict/districts/list', [DistrictController::class, 'list']);
    Route::get('/dict/districts/store', [DistrictController::class, 'simpleStore']);
    Route::get('/dict/districts1926/list', [District1926Controller::class, 'list']);
    Route::get('/dict/districts1926/store', [District1926Controller::class, 'simpleStore']);
    Route::get('/dict/geotypes/store', [GeotypeController::class, 'simpleStore']);
    Route::get('/dict/selsovets1926/list', [Selsovet1926Controller::class, 'list']);
    Route::get('/dict/selsovets1926/store', [Selsovet1926Controller::class, 'simpleStore']);
    Route::get('/dict/settlements/list', [SettlementController::class, 'sList']);    
    Route::get('/dict/settlements/store', [SettlementController::class, 'simpleStore']);
    Route::get('/dict/settlements1926/list', [Settlement1926Controller::class, 'list']);    
    Route::get('/dict/settlements1926/store', [Settlement1926Controller::class, 'simpleStore']);
    Route::get('/dict/sources/create', [SourceController::class, 'create']);
    Route::get('/dict/topnames/create', [TopnameController::class, 'create']);

    //Route::get('/{param1}', [WelcomeController::class, 'indexParam'])->name('welcome');
    Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
        return view('welcome');
    })->name('dashboard');


/** OTHER PAGES THAT SHOULD NOT BE LOCALIZED **/
//Route::get('/', [WelcomeController::class, 'index'])->name('welcome');


    // Route::get('/dict/toponyms', [ToponymController::class, 'index'])->name('dict-toponyms');
Route::resources([
    'dict/districts' => DistrictController::class,
    'dict/districts1926' => District1926Controller::class,
    'dict/regions' => RegionController::class,
    'dict/selsovets1926' => Selsovet1926Controller::class,
    'dict/settlements' => SettlementController::class,
    'dict/settlements1926' => Settlement1926Controller::class,
    'dict/toponyms' => ToponymController::class,
    
    'misc/geotypes' => GeotypeController::class,
    'misc/informants' => InformantController::class,
    'misc/recorders' => RecorderController::class,
    'misc/structs' => StructController::class,
]);


}); // eo LaravelLocalization