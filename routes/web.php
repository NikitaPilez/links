<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\RedirectController;
use Illuminate\Support\Facades\Route;

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

Route::get('/admin/export-csv', [RedirectController::class, 'exportCsv'])->name('filament.admin.pages.export-csv');
Route::get('/{alias}/{scenario}', [MainController::class, 'index']);
Route::get('/pulse-webhook', [MainController::class, 'pulseWebhook'])->name('pulse.webhook');
