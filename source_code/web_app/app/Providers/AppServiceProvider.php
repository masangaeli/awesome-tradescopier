<?php

namespace App\Providers;

use View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //String Default Length
        Schema::defaultStringLength(191);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
         //Forcing SSL
         if (env('APP_ENV') == 'production') {
            $this->app['request']->server->set('HTTPS', true);
         }        
 
 
    }
}
