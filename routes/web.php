<?php

use Illuminate\Support\Facades\Route;
use Laravel\Cashier\Http\Controllers\WebhookController;
use App\Http\Controllers\Settings\EmailSettingsController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\StripeWebhookController;



// Hilfsvariablen: Namespace + Guard-Helfer
$C = '\\App\\Http\\Controllers\\';
$when = function (string $class, callable $cb) {
    if (class_exists($class)) {
        $cb($class);
    }
};

/**
 * Stripe Webhook (Cashier)
 * WICHTIG:
 * - außerhalb von auth
 * - POST
 * - CSRF-Excludes sind bei dir in bootstrap/app.php gesetzt (validateCsrfTokens)
 *
 * Hinweis: Cashier WebhookController ist NICHT invokable -> handleWebhook verwenden
 */
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])
    ->name('stripe.webhook');

// Startseite → Weiterleitung zum Dashboard
Route::get('/', fn () => redirect()->route('dashboard'));

// Öffentlich sichtbare Seiten
Route::view('/impressum', 'impressum')->name('impressum');

// Lizenzmodell muss OHNE Paywall erreichbar bleiben
Route::middleware(['auth'])->group(function () {
    Route::get('/lizenz', [SubscriptionController::class, 'index'])->name('subscription.index');
    Route::post('/lizenz/kaufen', [SubscriptionController::class, 'checkout'])->name('subscription.checkout');
});

// Dashboard (Controller, falls vorhanden; sonst Fallback-View)
// 🔥 Mit Paywall schützen
if (class_exists($C.'EventController')) {
    Route::get('/dashboard', [$C.'EventController', 'dashboardEvents'])
        ->middleware(['auth', 'verified', 'tenant.subscribed'])
        ->name('dashboard');
} else {
    Route::get('/dashboard', fn () => view('dashboard'))
        ->middleware(['auth', 'verified', 'tenant.subscribed'])
        ->name('dashboard');
}

// Authentifizierte UND lizenzpflichtige Bereiche
Route::middleware(['auth', 'tenant.subscribed'])->group(function () use ($when, $C) {

    // --- Billing (Tenant-basiert, Cashier) ---
    $when($C.'BillingController', function($cls){
        // NEU: Pläne anzeigen (Starter/Basic/Enterprise)
        Route::get('/billing/plans', [$cls, 'plans'])->name('billing.plans');

        // Bestehend: Subscription starten (Price-ID kommt aus der Plan-Auswahl)
        Route::post('/billing/subscribe/{priceId}', [$cls, 'subscribe'])->name('billing.subscribe');

        // Bestehend: Customer Portal
        Route::get('/billing/portal', [$cls, 'portal'])->name('billing.portal');
    });

    // Projekte – Übersicht, Anlegen, Anzeigen, Bearbeiten, Löschen
    $when($C.'ProjectIndexController', function($cls){
        Route::get('/projects', $cls)->name('projects.index');
    });

    $when($C.'ProjectController', function($cls){
        Route::get('/projects/create', [$cls, 'create'])->name('projects.create');
        Route::post('/projects', [$cls, 'store'])->name('projects.store');
        Route::get('/projects/{project}/edit', [$cls, 'edit'])->name('projects.edit');
        Route::put('/projects/{project}', [$cls, 'update'])->name('projects.update');
        Route::delete('/projects/{project}', [$cls, 'destroy'])->name('projects.destroy');
    });

    $when($C.'ProjectShowController', function($cls){
        Route::get('/projects/{project}', $cls)->name('projects.show');
    });

    // Projekt-Aufgaben (Tasks)
    $when($C.'TaskController', function($cls){
        Route::get('/projects/{project}/tasks/create', [$cls, 'create'])->name('projects.tasks.create');
        Route::post('/projects/{project}/tasks', [$cls, 'store'])->name('projects.tasks.store');
        Route::get('/projects/{project}/tasks/{task}/edit', [$cls, 'edit'])->name('projects.tasks.edit');
        Route::put('/projects/{project}/tasks/{task}', [$cls, 'update'])->name('projects.tasks.update');
    });

    // Projekt-Dokumente
    $when($C.'ProjectDocumentController', function($cls){
        Route::get('/projects/{project}/documents/create', [$cls, 'create'])->name('projects.documents.create');
        Route::post('/projects/{project}/documents', [$cls, 'store'])->name('projects.documents.store');
        Route::get('/projects/{project}/documents/{document}/download', [$cls, 'download'])->name('projects.documents.download');
        Route::delete('/projects/{project}/documents/{document}', [$cls, 'destroy'])->name('projects.documents.destroy');
    });

    // Gantt
    $when($C.'ProjectGanttController', function($cls){
        Route::get('/projects/{project}/gantt.json', [$cls, 'json'])->name('projects.gantt.json');
    });

    Route::get('/projects/{project}/gantt', function (\Illuminate\Http\Request $request, \App\Models\Project $project) {
        if (!$request->user() || (string)$request->user()->tenant_id !== (string)$project->tenant_id) {
            abort(404);
        }

        return view('projects.gantt', ['project' => $project]);
    })->name('projects.gantt');

    // Feedback
    $when($C.'FeedbackController', function($cls){
        Route::post('/feedback', [$cls, 'store'])->name('feedback.store');
    });

    // Profil
    $when($C.'ProfileController', function($cls){
        Route::get('/profile', [$cls, 'edit'])->name('profile.edit');
        Route::patch('/profile', [$cls, 'update'])->name('profile.update');
        Route::delete('/profile', [$cls, 'destroy'])->name('profile.destroy');
    });

    // Benutzerverwaltung
    $when($C.'UserController', function($cls){
        Route::get('/users', [$cls, 'index'])->name('users.index');
        Route::get('/users/create', [$cls, 'create'])->name('users.create');
        Route::post('/users', [$cls, 'store'])->name('users.store');
        Route::delete('/users/{user}', [$cls, 'destroy'])->name('users.destroy');
    });

    // Mitglieder
    $when($C.'MemberController', function($cls){

        // Limit-Blocker nur für "Neuanlage" + Import (Soft/Hard Limit)
        Route::get('/members/create', [$cls, 'create'])
            ->middleware('member.limit')
            ->name('members.create');

        Route::post('/members', [$cls, 'store'])
            ->middleware('member.limit')
            ->name('members.store');

        // Restliche Member-Routen ohne Limit (anzeigen/bearbeiten ok)
        Route::get('/members', [$cls, 'index'])->name('members.index');
        Route::get('/members/{member}', [$cls, 'show'])->name('members.show');
        Route::get('/members/{member}/edit', [$cls, 'edit'])->name('members.edit');
        Route::put('/members/{member}', [$cls, 'update'])->name('members.update');
        Route::patch('/members/{member}', [$cls, 'update']);
        Route::delete('/members/{member}', [$cls, 'destroy'])->name('members.destroy');

        Route::post('/members/bulk-action', [$cls, 'bulkAction'])->name('members.bulk-action');
        Route::get('/members/{member}/datenauskunft', [$cls, 'exportDatenauskunft'])->name('members.datenauskunft');
        Route::get('/members/{member}/pdf', [$cls, 'exportDatenauskunft'])->name('members.pdf');
    });

    // Mitgliedschaften
    $when($C.'MembershipController', function($cls){
        Route::resource('memberships', $cls)->except(['show']);
    });

    // Tags
    $when($C.'TagController', function($cls){
        Route::resource('tags', $cls)->except(['show']);
    });

    // CSV-Import (mit Limit-Blocker)
    $when($C.'ImportController', function($cls){
        Route::get('/import/mitglieder', [$cls, 'showUploadForm'])
            ->middleware('member.limit')
            ->name('import.mitglieder');

        Route::post('/import/mitglieder/preview', [$cls, 'preview'])
            ->middleware('member.limit')
            ->name('import.mitglieder.preview');

        Route::post('/import/mitglieder/confirm', [$cls, 'confirm'])
            ->middleware('member.limit')
            ->name('import.mitglieder.confirm');
    });

    // Vereinsprofil
    $when($C.'TenantController', function($cls){
        Route::get('/verein', [$cls, 'show'])->name('tenant.show');
        Route::get('/verein/bearbeiten', [$cls, 'edit'])->name('tenant.edit');
        Route::patch('/verein/bearbeiten', [$cls, 'update'])->name('tenant.update');
    });

    // Veranstaltungen
    $when($C.'EventController', function($cls){
        Route::resource('events', $cls)->except(['show']);
        Route::get('/events/{event}', [$cls, 'show'])->name('events.show');
    });

    // Eigene Mitgliederfelder
    $when($C.'CustomMemberFieldController', function($cls){
        Route::prefix('einstellungen/mitgliederfelder')->name('custom-fields.')->group(function () use ($cls) {
            Route::get('/', [$cls, 'index'])->name('index');
            Route::get('/create', [$cls, 'create'])->name('create');
            Route::post('/', [$cls, 'store'])->name('store');
            Route::get('/{customMemberField}/edit', [$cls, 'edit'])->name('edit');
            Route::put('/{customMemberField}', [$cls, 'update'])->name('update');
            Route::delete('/{customMemberField}', [$cls, 'destroy'])->name('destroy');
        });
    });

    // Rollen
    $when($C.'RoleController', function($cls){
        Route::get('/einstellungen/rollen', [$cls, 'edit'])->name('roles.edit');
        Route::post('/einstellungen/rollen', [$cls, 'update'])->name('roles.update');
    });

    // Finanzen – Konten und Buchungen
    $when($C.'AccountController', function($cls){
        Route::resource('accounts', $cls)->except(['show']);
    });

    $when($C.'TransactionController', function($cls){
        Route::resource('transactions', $cls)->except(['show']);
        Route::get('/transactions/summary', [$cls, 'summary'])->name('transactions.summary');
        Route::get('/transactions/{transaction}/cancel', [$cls, 'cancel'])->name('transactions.cancel');
        Route::post('/transactions/{transaction}/cancel', [$cls, 'cancelStore'])->name('transactions.cancel.store');
    });

    // Belege
    $when($C.'ReceiptController', function($cls){
        Route::get('/beleg/{path}', [$cls, 'show'])
    ->where('path', '.*')
    ->name('receipts.show');
		 });

    // Beitragsrechnungen
    $when($C.'InvoiceController', function($cls){
        Route::resource('invoices', $cls)->only(['index', 'create', 'store', 'show']);
        Route::get('/invoices/{invoice}/pdf', [$cls, 'pdf'])->name('invoices.pdf');
    });

    // Nummernkreise
    $when($C.'InvoiceNumberRangeController', function($cls){
        Route::resource('number-ranges', $cls)->names('number_ranges');
    });

    // Protokolle
    $when($C.'ProtocolController', function($cls){
        Route::get('/protokolle', [$cls, 'index'])->name('protocols.index');
        Route::get('/protokolle/neu', [$cls, 'create'])->name('protocols.create');
        Route::post('/protokolle', [$cls, 'store'])->name('protocols.store');
        Route::get('/protokolle/{protocol}', [$cls, 'show'])->name('protocols.show');
        Route::get('/protokolle/{protocol}/bearbeiten', [$cls, 'edit'])->name('protocols.edit');
        Route::put('/protokolle/{protocol}', [$cls, 'update'])->name('protocols.update');
        Route::get('/protokolle/{protocol}/mail', [$cls, 'mailForm'])->name('protocols.mail.form');
        Route::post('/protokolle/{protocol}/mail', [$cls, 'sendMail'])->name('protocols.mail.send');
    });

    // SMTP-Einstellungen
    Route::get('/settings/email', [EmailSettingsController::class, 'edit'])
        ->name('settings.email.edit');

    Route::put('/settings/email', [EmailSettingsController::class, 'update'])
        ->name('settings.email.update');

    // PDF-Test
    $when($C.'PdfTestController', function($cls){
        Route::get('/pdf-test', [$cls, 'test'])->name('pdf.test');
    });

    // Debug
    Route::get('/envcheck', fn () => dd(config('app.env'), config('app.debug')));

    // --- Kontakte (eigenes Modul, getrennt von Mitgliedern) ---
    $when($C.'ContactController', function($cls){
        Route::resource('contacts', $cls);
    });
});

// Template
Route::middleware(['auth', 'tenant.subscribed'])->group(function () {
    Route::get('/templates/{template}/preview', [TemplateController::class, 'preview'])
        ->name('templates.preview');

    Route::resource('templates', TemplateController::class);
});

// MailParser
Route::middleware(['auth', 'tenant.subscribed'])->group(function () {
    Route::get('/mail/send', [MailController::class, 'create'])
        ->name('mail.create');

    Route::post('/mail/send', [MailController::class, 'send'])
        ->name('mail.send');
});

// Buchungsjournal
Route::middleware(['auth', 'tenant.subscribed'])->group(function () {
    Route::get('/transactions/journal', [TransactionController::class, 'journal'])
        ->name('transactions.journal');

    Route::get('/transactions/journal/pdf', [TransactionController::class, 'journalPdf'])
        ->name('transactions.journal.pdf');

    Route::get('/transactions/eur', [TransactionController::class, 'eur'])
        ->name('transactions.eur');

    // Payment
    Route::get('/invoices/{invoice}/payment', [PaymentController::class, 'create'])
        ->name('payments.create');

    Route::post('/invoices/{invoice}/payment', [PaymentController::class, 'store'])
        ->name('payments.store');

    // Mitgliederabrechnung
    Route::post('/invoices/generate-memberships', [InvoiceController::class, 'generateMembershipInvoices'])
        ->name('invoices.generateMemberships');
});

// Admin-Dashboard bleibt unabhängig von der Paywall
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
});

// Authentifizierung (Fortify/Jetstream etc.)
require __DIR__.'/auth.php';