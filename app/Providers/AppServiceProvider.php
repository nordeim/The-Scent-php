<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use App\Services\CartService;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CartService::class, function ($app) {
            Log::debug('Creating CartService instance');
            return new CartService();
        });
    }

    public function boot()
    {
        Blade::component('application-logo', \App\View\Components\ApplicationLogo::class);
        
        // Ensure early instantiation of CartService
        try {
            $this->app->make(CartService::class);
            Log::debug('CartService booted successfully');
        } catch (\Exception $e) {
            Log::error('CartService boot error: ' . $e->getMessage());
        }

        View::composer('*', function ($view) {
            try {
                $cartService = app(CartService::class);
                $view->with([
                    'cartItems' => $cartService->getItems(),
                    'total' => $cartService->getTotal()
                ]);
            } catch (\Exception $e) {
                Log::error('Cart view composer error: ' . $e->getMessage());
                $view->with([
                    'cartItems' => collect([]),
                    'total' => 0
                ]);
            }
        });
    }
}