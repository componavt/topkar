<?php

//use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Dict\RegionController;
use App\Http\Controllers\Dict\District1926Controller;
use App\Http\Controllers\Dict\Selsovet1926Controller;
use App\Http\Controllers\Dict\Settlement1926Controller;
use App\Http\Controllers\Dict\ToponymController;
use App\Http\Controllers\Aux\GeotypeController;

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
    /** ADD ALL LOCALIZED ROUTES INSIDE THIS GROUP **/
    Route::get('/', function () {
        return view('welcome'); // return view('dashboard');
    });

/*    Route::get('/dict/regions',       [RegionController::class, 'index'])->name('dict-regions');
    Route::get('/dict/districts1926', [District1926Controller::class, 'index'])->name('dict-districts1926');
    Route::get('/dict/selsovets1926', [Selsovet1926Controller::class, 'index'])->name('dict-selsovets1926');
    Route::get('/dict/settlements1926', [Settlement1926Controller::class, 'index'])->name('dict-settlements1926');*/
    
    

    //Route::get('/{param1}', [WelcomeController::class, 'indexParam'])->name('welcome');
    Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');


/** OTHER PAGES THAT SHOULD NOT BE LOCALIZED **/
//Route::get('/', [WelcomeController::class, 'index'])->name('welcome');


    // Route::get('/dict/toponyms', [ToponymController::class, 'index'])->name('dict-toponyms');
Route::resources([
    'dict/districts1926' => District1926Controller::class,
    'dict/regions' => RegionController::class,
    'dict/selsovets1926' => Selsovet1926Controller::class,
    'dict/settlements1926' => Settlement1926Controller::class,
    'dict/toponyms' => ToponymController::class,
    
    'aux/geotypes' => GeotypeController::class,
]);


}); // eo LaravelLocalization