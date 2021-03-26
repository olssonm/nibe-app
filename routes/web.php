<?php

use App\Http\Controllers\AuthorizationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SetupController;
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

Route::group(['middleware' => 'auth.very_basic'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Systems
    Route::get('/setup', [SetupController::class, 'index'])->name('setup.index');
    Route::post('/setup', [SetupController::class, 'store'])->name('setup.store');

    // Auth
    Route::get('/auth', [AuthorizationController::class, 'auth'])->name('auth.auth');
    Route::get('/auth/callback', [AuthorizationController::class, 'callback'])->name('auth.callback');
});
