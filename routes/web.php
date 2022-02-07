<?php

//use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Dict\RegionController;

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

Route::get('/', function () {
//    return 'TopKar site';
    return view('welcome'); // return view('dashboard');
});

Route::get('/dict/regions', [RegionController::class, 'index']);

Route::get('/dict/districts1926', function () {
    return view('main');
});


//Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

//Route::get('/{param1}', [WelcomeController::class, 'indexParam'])->name('welcome');
Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
