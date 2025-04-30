<?php

//use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\WelcomeController;

//use App\Http\Controllers\Library\ServiceController;

use App\Http\Controllers\Dict\DistrictController;
use App\Http\Controllers\Dict\District1926Controller;
use App\Http\Controllers\Dict\RegionController;
use App\Http\Controllers\Dict\Selsovet1926Controller;
use App\Http\Controllers\Dict\SettlementController;
use App\Http\Controllers\Dict\Settlement1926Controller;
use App\Http\Controllers\Dict\TopnameController;
use App\Http\Controllers\Dict\ToponymController;
use App\Http\Controllers\Dict\WrongnameController;

use App\Http\Controllers\Library\StatsController;

use App\Http\Controllers\Misc\GeotypeController;
use App\Http\Controllers\Misc\InformantController;
use App\Http\Controllers\Misc\RecorderController;
use App\Http\Controllers\Misc\SourceController;
use App\Http\Controllers\Misc\SourceToponymController;
use App\Http\Controllers\Misc\StructController;

use App\Http\Controllers\HomeController;
//use App\Http\Controllers\DumpDownloadController;

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
/*    Route::get('/map_example', function () {
        return view('map_example'); // return view('dashboard');
    });*/

//    Route::get('/service/tmp_fill_events', [ServiceController::class, 'tmp_fill_events']);
//    Route::get('/service/tmp_fill_settlements', [ServiceController::class, 'tmp_fill_settlements']);
//    Route::get('/service/tmp_fill_name_for_search', [ServiceController::class, 'tmp_fill_name_for_search']);
//    Route::get('/service/tmp_fill_sources', [ServiceController::class, 'tmp_fill_sources']);
//    Route::get('/service/tmp_fill_topnames_from_variants', [ServiceController::class, 'tmp_fill_topnames_from_variants']);

    Route::get('/misc/informants/list', [InformantController::class, 'informantList']);
    Route::get('/misc/recorders/list', [RecorderController::class, 'recorderList']);
    Route::get('/misc/structs/list', [StructController::class, 'structList']);
    
    Route::get('/dict/districts/list', [DistrictController::class, 'districtList']);
    Route::get('/dict/districts/store', [DistrictController::class, 'simpleStore']);
    Route::get('/dict/districts1926/list', [District1926Controller::class, 'district1926List']);
    Route::get('/dict/districts1926/store', [District1926Controller::class, 'simpleStore']);
    Route::get('/dict/geotypes/store', [GeotypeController::class, 'simpleStore']);
    Route::get('/dict/selsovets1926/list', [Selsovet1926Controller::class, 'selsovet1926List']);
    Route::get('/dict/selsovets1926/store', [Selsovet1926Controller::class, 'simpleStore']);
    Route::get('/dict/settlements/list', [SettlementController::class, 'sList']);    
    Route::get('/dict/settlements/list_with_districts', [SettlementController::class, 'listWithDistricts']);    
    Route::get('/dict/settlements/store', [SettlementController::class, 'simpleStore']);
    Route::get('/dict/settlements1926/list', [Settlement1926Controller::class, 'slist']);    
    Route::get('/dict/settlements1926/list_with_districts', [Settlement1926Controller::class, 'listWithDistricts']);    
    Route::get('/dict/settlements1926/store', [Settlement1926Controller::class, 'simpleStore']);
    
    Route::get('/dict/topnames/create', [TopnameController::class, 'create']);
    Route::get('/dict/toponyms/duplicates', [ToponymController::class, 'duplicates'])->name('toponyms.duplicates');
    Route::post('/dict/toponyms/export', [ToponymController::class, 'export'])->name('toponyms.export');
    Route::get('/dict/toponyms/nladoga', [ToponymController::class, 'nLadoga'])->name('toponyms.nladoga');
    Route::get('/dict/toponyms/nladoga/on_map', [ToponymController::class, 'nladogaOnMap'])->name('toponyms.nladoga.on_map');
    Route::get('/dict/toponyms/link_to_settl', [ToponymController::class, 'linkToSettlement'])->name('toponyms.link_to_settl');
    Route::post('/dict/toponyms/link_to_settl', [ToponymController::class, 'linkToSettlementSave'])->name('toponyms.link_to_settl.save');
    Route::get('/dict/toponyms/list_for_export', [ToponymController::class, 'listForExport'])->name('toponyms.list_for_export');
    Route::get('/dict/toponyms/on_map', [ToponymController::class, 'onMap'])->name('toponyms.on_map');
    Route::get('/dict/toponyms/shaidomozero', [ToponymController::class, 'shaidomozero'])->name('toponyms.shaidomozero');
    Route::get('/dict/toponyms/with_coords', [ToponymController::class, 'withCoords'])->name('toponyms.with_coords');
    Route::get('/dict/toponyms/with_wd', [ToponymController::class, 'withWD'])->name('toponyms.with_wd');
    Route::get('/dict/toponyms/with_wrongnames', [ToponymController::class, 'withWrongnames'])->name('toponyms.with_wrongnames');
    Route::get('/dict/toponyms/with_legends', [ToponymController::class, 'withLegends'])->name('toponyms.with_legends');

    Route::get('/dict/wrongnames/create', [WrongnameController::class, 'create']);

    Route::get('/misc/source_toponym/create', [SourceToponymController::class, 'create']);
    Route::get('/misc/source_toponym/extract_sources', [SourceToponymController::class, 'extractSources']);
    Route::get('/misc/source_toponym', [SourceToponymController::class, 'index']);

    Route::get('/pages/{page}', [HomeController::class, 'page'])->name('pages');        

    Route::get('/stats', [StatsController::class, 'index'])->name('stats');        
    
    //Route::get('/{param1}', [WelcomeController::class, 'indexParam'])->name('welcome');
    Route::middleware(['auth:sanctum', 'verified'])
        ->get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    Route::get('/', [HomeController::class, 'index']);

//    Route::get('/dumps', [DumpDownloadController::class, 'index']);

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
    'misc/sources' => SourceController::class,
    'misc/structs' => StructController::class,
]);


}); // eo LaravelLocalization
