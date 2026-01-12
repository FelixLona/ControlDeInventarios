<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Inventario;
use App\Observers\InventarioObserver;
use App\Observers\TestInventarioObserver;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Assets\Css;

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
        Inventario::observe(InventarioObserver::class);
        //Inventario::observe(TestInventarioObserver::class);
        
    }
}
