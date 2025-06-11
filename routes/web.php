<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\MembershipController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Dashboard (geschützt) – mit Events
Route::get('/dashboard', [EventController::class, 'dashboardEvents'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Authentifizierte Routen
Route::middleware('auth')->group(function () {

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // CSV-Mitgliederimport
    Route::get('/import/mitglieder', [ImportController::class, 'showUploadForm'])->name('import.mitglieder');
    Route::post('/import/mitglieder/preview', [ImportController::class, 'preview'])->name('import.mitglieder.preview');
    Route::post('/import/mitglieder/confirm', [ImportController::class, 'confirm'])->name('import.mitglieder.confirm');

    // Mitgliederverwaltung
    Route::resource('members', MemberController::class);

    // DSGVO-Datenauskunft als PDF
    Route::get('/members/{member}/datenauskunft', [MemberController::class, 'exportDatenauskunft'])
        ->name('members.datenauskunft');

    // Vereinsprofil anzeigen und bearbeiten
    Route::get('/verein', [TenantController::class, 'show'])->name('tenant.show');
    Route::get('/verein/bearbeiten', [TenantController::class, 'edit'])->name('tenant.edit');
    Route::patch('/verein/bearbeiten', [TenantController::class, 'update'])->name('tenant.update');

    // Event-Formular explizit definieren, damit kein Konflikt mit {event}
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');

    // Resource-Routen für Events – ohne show & create (weil create oben schon definiert)
    Route::resource('events', EventController::class)->except(['show', 'create']);

    // Mitgliedschaften
    Route::resource('memberships', MembershipController::class)->except(['show']);
    
    // DSGVO PDF-Auskunfts-Download
    Route::get('/members/{member}/pdf', [\App\Http\Controllers\MemberController::class, 'exportDatenauskunft'])->name('members.pdf');

});

require __DIR__.'/auth.php';
