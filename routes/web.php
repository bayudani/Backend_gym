<?php

use App\Http\Controllers\AbsenController;
use Illuminate\Support\Facades\Route;

// Route::view('/', 'welcome');
Route::get('/', function () {
        return redirect()->route('filament.admin.pages.dashboard');
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');
Route::post('/admin/absens/scan', [AbsenController::class, 'store'])->name('absens.scan.store');
Route::get('/admin/absens/scan', function () {
    return view('filament.pages.scan-qr');
})->name('absens.scan.page');

require __DIR__.'/auth.php';
