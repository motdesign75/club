<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CustomMemberFieldController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\ProtocolController;
use App\Http\Controllers\InvoiceNumberRangeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PdfTestController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [EventController::class, 'dashboardEvents'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('/impressum', 'impressum')->name('impressum');

Route::middleware('auth')->group(function () {

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Mitglieder
    Route::resource('members', MemberController::class);
    Route::get('/members/{member}/datenauskunft', [MemberController::class, 'exportDatenauskunft'])->name('members.datenauskunft');
    Route::get('/members/{member}/pdf', [MemberController::class, 'exportDatenauskunft'])->name('members.pdf');

    // Vereinsprofil
    Route::get('/verein', [TenantController::class, 'show'])->name('tenant.show');
    Route::get('/verein/bearbeiten', [TenantController::class, 'edit'])->name('tenant.edit');
    Route::patch('/verein/bearbeiten', [TenantController::class, 'update'])->name('tenant.update');

    // Mitgliedschaften
    Route::resource('memberships', MembershipController::class)->except(['show']);

    // Nummernkreise für Rechnungen
    Route::resource('number-ranges', InvoiceNumberRangeController::class)->names('number_ranges');

    // Beitragsrechnungen (inkl. Detailansicht)
    Route::resource('invoices', InvoiceController::class)->only(['index', 'create', 'store', 'show']);
    Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'pdf'])->name('invoices.pdf'); // ← NEU

    // CSV-Import
    Route::get('/import/mitglieder', [ImportController::class, 'showUploadForm'])->name('import.mitglieder');
    Route::post('/import/mitglieder/preview', [ImportController::class, 'preview'])->name('import.mitglieder.preview');
    Route::post('/import/mitglieder/confirm', [ImportController::class, 'confirm'])->name('import.mitglieder.confirm');

    // Veranstaltungen
    Route::resource('events', EventController::class)->except(['show']);

    // Rollen
    Route::get('/einstellungen/rollen', [RoleController::class, 'edit'])->name('roles.edit');
    Route::post('/einstellungen/rollen', [RoleController::class, 'update'])->name('roles.update');

    // Eigene Mitgliederfelder
    Route::prefix('einstellungen/mitgliederfelder')->name('custom-fields.')->group(function () {
        Route::get('/', [CustomMemberFieldController::class, 'index'])->name('index');
        Route::get('/create', [CustomMemberFieldController::class, 'create'])->name('create');
        Route::post('/', [CustomMemberFieldController::class, 'store'])->name('store');
        Route::get('/{customMemberField}/edit', [CustomMemberFieldController::class, 'edit'])->name('edit');
        Route::put('/{customMemberField}', [CustomMemberFieldController::class, 'update'])->name('update');
        Route::delete('/{customMemberField}', [CustomMemberFieldController::class, 'destroy'])->name('destroy');
    });

    // Finanzen – Konten & Buchungen
    Route::resource('accounts', AccountController::class)->except(['show']);
    Route::resource('transactions', TransactionController::class)->except(['show', 'edit', 'update']);
    Route::get('/transactions/summary', [TransactionController::class, 'summary'])->name('transactions.summary');
    Route::get('/transactions/{transaction}/cancel', [TransactionController::class, 'cancel'])->name('transactions.cancel');
    Route::post('/transactions/{transaction}/cancel', [TransactionController::class, 'cancelStore'])->name('transactions.cancel.store');

    // Belege
    Route::get('/beleg/{filename}', [ReceiptController::class, 'show'])->name('receipts.show');

    // Protokolle
    Route::get('/protokolle', [ProtocolController::class, 'index'])->name('protocols.index');
    Route::get('/protokolle/neu', [ProtocolController::class, 'create'])->name('protocols.create');
    Route::post('/protokolle', [ProtocolController::class, 'store'])->name('protocols.store');
    Route::get('/protokolle/{protocol}', [ProtocolController::class, 'show'])->name('protocols.show');
    Route::get('/protokolle/{protocol}/bearbeiten', [ProtocolController::class, 'edit'])->name('protocols.edit');
    Route::put('/protokolle/{protocol}', [ProtocolController::class, 'update'])->name('protocols.update');
    
    //Test-PDF
    Route::get('/pdf-test', [PdfTestController::class, 'test'])->name('pdf.test');

    // Debug
    Route::get('/envcheck', function () {
        dd(config('app.env'), config('app.debug'));
    });
});

require __DIR__.'/auth.php';
