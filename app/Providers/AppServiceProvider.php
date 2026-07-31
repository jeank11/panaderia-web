<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
use App\Models\Cliente;

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
        // Forzar HTTPS en producción
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        View::composer('*', function ($view) {

            $clientePortal = null;

            if (session()->has('cliente_id')) {

                $clientePortal = Cliente::find(
                    session('cliente_id')
                );

            }

            $view->with(
                'clientePortal',
                $clientePortal
            );

        });
    }
}