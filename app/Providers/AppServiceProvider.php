<?php

namespace App\Providers;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (env('REDIRECT_HTTPS')) {
            $this->app['request']->server->set('HTTPS', true);
        }
    }

    public function boot()
    {
        if (env('REDIRECT_HTTPS')) {
            URL::forceScheme('https');
        }
    }
}
