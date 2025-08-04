<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProfileController,
    MemberController,
    TenantController,
    EventController,
    ImportController,
    MembershipController,
    RoleController,
    AccountController,
    TransactionController,
    CustomMemberFieldController,
    ReceiptController,
    ProtocolController,
    InvoiceNumberRangeController,
    InvoiceController,
    PdfTestController,
    LicenseController,
    TagController,
    FeedbackController,
    EmailSettingsController,
    UserController
};

// Startseite → Weiterleitung zum Dashboard
Route::get('/', fn () => redirect()->route('dashboard'));

// Dashboard
Route::get('/dashboard', [EventController::class, 'dashboardEvents'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Öffentlich sichtbare Seiten
Route::view('/impressum', 'impressum')->name('impressum');

// Authentifizierte Bereiche
Route::middleware('auth')->group(function () {

    // Feedback
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Benutzerverwaltung ✅ NEU
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy'); // ✅ hinzugefügt

    // Mitglieder
    Route::resource('members', MemberController::class);
    Route::post('/members/bulk-action', [MemberController::class, 'bulkAction'])->name('members.bulk-action');
    Route::get('/members/{member}/datenauskunft', [MemberController::class, 'exportDatenauskunft'])->name('members.datenauskunft');
    Route::get('/members/{member}/pdf', [MemberController::class, 'exportDatenauskunft'])->name('members.pdf');

    // Mitgliedschaften
    Route::resource('memberships', MembershipController::class)->except(['show']);

    // Tags
    Route::resource('tags', TagController::class)->except(['show']);

    // CSV-Import
    Route::get('/import/mitglieder', [ImportController::class, 'showUploadForm'])->name('import.mitglieder');
    Route::post('/import/mitglieder/preview', [ImportController::class, 'preview'])->name('import.mitglieder.preview');
    Route::post('/import/mitglieder/confirm', [ImportController::class, 'confirm'])->name('import.mitglieder.confirm');

    // Vereinsprofil
    Route::get('/verein', [TenantController::class, 'show'])->name('tenant.show');
    Route::get('/verein/bearbeiten', [TenantController::class, 'edit'])->name('tenant.edit');
    Route::patch('/verein/bearbeiten', [TenantController::class, 'update'])->name('tenant.update');

    // Veranstaltungen
    Route::resource('events', EventController::class)->except(['show']);
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

    // Eigene Mitgliederfelder
    Route::prefix('einstellungen/mitgliederfelder')->name('custom-fields.')->group(function () {
        Route::get('/', [CustomMemberFieldController::class, 'index'])->name('index');
        Route::get('/create', [CustomMemberFieldController::class, 'create'])->name('create');
        Route::post('/', [CustomMemberFieldController::class, 'store'])->name('store');
        Route::get('/{customMemberField}/edit', [CustomMemberFieldController::class, 'edit'])->name('edit');
        Route::put('/{customMemberField}', [CustomMemberFieldController::class, 'update'])->name('update');
        Route::delete('/{customMemberField}', [CustomMemberFieldController::class, 'destroy'])->name('destroy');
    });

    // Rollen
    Route::get('/einstellungen/rollen', [RoleController::class, 'edit'])->name('roles.edit');
    Route::post('/einstellungen/rollen', [RoleController::class, 'update'])->name('roles.update');

    // Finanzen – Konten und Buchungen
    Route::resource('accounts', AccountController::class)->except(['show']);
    Route::resource('transactions', TransactionController::class)->except(['show', 'edit', 'update']);
    Route::get('/transactions/summary', [TransactionController::class, 'summary'])->name('transactions.summary');
    Route::get('/transactions/{transaction}/cancel', [TransactionController::class, 'cancel'])->name('transactions.cancel');
    Route::post('/transactions/{transaction}/cancel', [TransactionController::class, 'cancelStore'])->name('transactions.cancel.store');

    // Belege
    Route::get('/beleg/{filename}', [ReceiptController::class, 'show'])->name('receipts.show');

    // Beitragsrechnungen
    Route::resource('invoices', InvoiceController::class)->only(['index', 'create', 'store', 'show']);
    Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'pdf'])->name('invoices.pdf');

    // Nummernkreise
    Route::resource('number-ranges', InvoiceNumberRangeController::class)->names('number_ranges');

    // Protokolle
    Route::get('/protokolle', [ProtocolController::class, 'index'])->name('protocols.index');
    Route::get('/protokolle/neu', [ProtocolController::class, 'create'])->name('protocols.create');
    Route::post('/protokolle', [ProtocolController::class, 'store'])->name('protocols.store');
    Route::get('/protokolle/{protocol}', [ProtocolController::class, 'show'])->name('protocols.show');
    Route::get('/protokolle/{protocol}/bearbeiten', [ProtocolController::class, 'edit'])->name('protocols.edit');
    Route::put('/protokolle/{protocol}', [ProtocolController::class, 'update'])->name('protocols.update');
    Route::get('/protokolle/{protocol}/mail', [ProtocolController::class, 'mailForm'])->name('protocols.mail.form');
    Route::post('/protokolle/{protocol}/mail', [ProtocolController::class, 'sendMail'])->name('protocols.mail.send');

    // Lizenzverwaltung / Stripe
    Route::get('/license/upgrade', [LicenseController::class, 'upgrade'])->name('license.upgrade');
    Route::post('/license/checkout', [LicenseController::class, 'checkout'])->name('license.checkout');

    // SMTP-Einstellungen
    Route::get('/settings/email', [EmailSettingsController::class, 'edit'])->name('settings.email.edit');
    Route::put('/settings/email', [EmailSettingsController::class, 'update'])->name('settings.email.update');

    // PDF-Test
    Route::get('/pdf-test', [PdfTestController::class, 'test'])->name('pdf.test');

    // Debug
    Route::get('/envcheck', fn () => dd(config('app.env'), config('app.debug')));
});

// Authentifizierung (Fortify)
require __DIR__.'/auth.php';
