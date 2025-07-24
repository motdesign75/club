<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;

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
        if (Auth::check()) {
            $tenant = Auth::user()->tenant;

            if ($tenant && $tenant->mail_host) {
                Config::set('mail.mailers.smtp.transport', $tenant->mail_mailer ?? 'smtp');
                Config::set('mail.mailers.smtp.host', $tenant->mail_host);
                Config::set('mail.mailers.smtp.port', $tenant->mail_port);
                Config::set('mail.mailers.smtp.username', $tenant->mail_username);
                Config::set('mail.mailers.smtp.password', $tenant->mail_password);
                Config::set('mail.mailers.smtp.encryption', $tenant->mail_encryption);
                Config::set('mail.from.address', $tenant->mail_from_address);
                Config::set('mail.from.name', $tenant->mail_from_name);
            }
        }
    }
}
