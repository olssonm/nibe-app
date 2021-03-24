<?php

use App\Http\Controllers\AuthorizeController;
use App\Http\Controllers\NibeController;
use Illuminate\Support\Facades\Route;

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

// Route::group(['middleware' => 'auth.very_basic'], function () {
    Route::get('/', [NibeController::class, 'index'])->name('dashboard');
    Route::get('/test', [NibeController::class, 'test']);
// });

Route::get('/oauth', [AuthorizeController::class, 'capture'])->name('authorize.capture');
