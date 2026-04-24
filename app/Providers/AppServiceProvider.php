<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;
use Livewire\Livewire;
use App\Http\Livewire\DashboardMemberStats;
use Laravel\Cashier\Cashier;
use App\Models\Tenant;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /**
         * 🔥 WICHTIG:
         * Cashier auf Tenant umstellen (Multi-Tenant Billing)
         */
        Cashier::useCustomerModel(Tenant::class);

        /**
         * Livewire-Komponente registrieren
         */
        Livewire::component('dashboard-member-stats', DashboardMemberStats::class);

        /**
         * Dynamische SMTP-Konfiguration pro Tenant
         */
        if (Auth::check()) {
            $tenant = Auth::user()->tenant ?? null;

            if ($tenant && $tenant->mail_host) {

                Config::set('mail.default', 'smtp');

                Config::set('mail.mailers.smtp.transport', $tenant->mail_mailer ?? 'smtp');
                Config::set('mail.mailers.smtp.host', $tenant->mail_host);
                Config::set('mail.mailers.smtp.port', $tenant->mail_port);
                Config::set('mail.mailers.smtp.username', $tenant->mail_username);
                Config::set('mail.mailers.smtp.password', $tenant->mail_password);
                Config::set('mail.mailers.smtp.encryption', $tenant->mail_encryption);

                Config::set('mail.from.address', $tenant->mail_from_address ?? 'noreply@clubano.de');
                Config::set('mail.from.name', $tenant->mail_from_name ?? 'Clubano');
            }
        }
    }
}