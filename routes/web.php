<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AppController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('{any}', [AppController::class, 'app'])->where('any', '.*')->name('lvs.app.any');
Route::get('/auth/{any}', [AppController::class, 'app'])->where('any', '.*')->name('lvs.app.auth.any');
Route::get('/auth', function () { return redirect()->route('lvs.app.auth', 'login'); });

Route::post('/broadcasting/auth', function () { return Auth::user(); })->middleware('auth:sanctum');
