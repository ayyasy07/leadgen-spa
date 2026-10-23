<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
       // Share variable ke semua view (buat switcher)
        View::composer('*', function ($view) {
            $view->with('color_scheme', session('color_scheme', 'default'));
            $view->with('dark_mode', session('dark_mode', false));
        });
    }
}