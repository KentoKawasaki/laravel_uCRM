<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
// use App\Http\Controllers\InertiaTestController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\AnalysisController;

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


/* uCRM Routing */

// Analysis Route
Route::get('analysis', [AnalysisController::class, 'index'])->name('analysis');
Route::get('analysis/rfm', [AnalysisController::class, 'rfm'])->name('analysis.rfm');

// Item Route
Route::resource('items', ItemController::class)->middleware(['auth', 'verified']);

// Customer Route
Route::resource('customers', CustomerController::class)->middleware(['auth', 'verified']);

// Purchase Route
Route::resource('purchases', PurchaseController::class)->middleware(['auth', 'verified']);

/* End uCRM Routing */

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
