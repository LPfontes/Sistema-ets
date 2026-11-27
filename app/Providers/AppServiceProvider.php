<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        if (
            str_starts_with((string) config('app.url'), 'https://') ||
            request()->header('X-Forwarded-Proto') === 'https'
        ) {
            URL::forceScheme('https');
        }

        try {
            if (Schema::hasTable('users')) {
                $adminEmail = strtolower(trim((string) env('ADMIN_EMAIL', 'admin@spd.local')));
                if (! User::where('email', $adminEmail)->exists()) {
                    User::create([
                        'name' => trim((string) env('ADMIN_NAME', 'Administrador do Sistema')),
                        'email' => $adminEmail,
                        'password' => (string) env('ADMIN_PASSWORD', 'admin123'),
                        'perfil' => 'admin',
                    ]);
                }
            }
        } catch (\Throwable) {
            // Ignora caso o banco ainda esteja inicializando
        }
    }
}
