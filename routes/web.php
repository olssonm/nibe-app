<?php

use App\Http\Controllers\AuthorizationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SystemController;
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
Route::group([], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Systems
    Route::get('/systems', [SystemController::class, 'index'])->name('systems.index');
    Route::post('/systems', [SystemController::class, 'save'])->name('systems.save');

    // Auth
    Route::get('/auth', [AuthorizationController::class, 'nibe'])->name('auth.nibe');
    Route::get('/auth/callback', [AuthorizationController::class, 'callback'])->name('auth.callback');
});
