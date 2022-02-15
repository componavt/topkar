<?php

//use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Dict\RegionController;
use App\Http\Controllers\Dict\District1926Controller;

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

    Route::get('/dict/regions',       [RegionController::class, 'index'])->name('dict-regions');
    Route::get('/dict/districts1926', [District1926Controller::class, 'index'])->name('dict-districts1926');

    //Route::get('/{param1}', [WelcomeController::class, 'indexParam'])->name('welcome');
    Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

/** OTHER PAGES THAT SHOULD NOT BE LOCALIZED **/

//Route::get('/', [WelcomeController::class, 'index'])->name('welcome');