<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\TenantController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Dashboard (geschützt)
Route::get('/dashboard', function () {
    $tenant = app('currentTenant');
    return view('dashboard', compact('tenant'));
})->middleware(['auth', 'verified'])->name('dashboard');

// Authentifizierte Routen
Route::middleware('auth')->group(function () {
    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Mitgliederverwaltung
    Route::resource('members', MemberController::class);

    // Vereinsprofil anzeigen und bearbeiten
    Route::get('/verein', [TenantController::class, 'show'])->name('tenant.show');
    Route::get('/verein/bearbeiten', [TenantController::class, 'edit'])->name('tenant.edit');
    Route::post('/verein/bearbeiten', [TenantController::class, 'update'])->name('tenant.update');
});

require __DIR__.'/auth.php';
