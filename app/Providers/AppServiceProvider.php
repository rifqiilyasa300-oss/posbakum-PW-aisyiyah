<?php

namespace App\Providers;

use App\Models\Client; // Import model Client
use App\Observers\ClientObserver; // Import class Observer
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
        // Mendaftarkan Observer agar memantau tabel Client
        Client::observe(ClientObserver::class);
    }
}
