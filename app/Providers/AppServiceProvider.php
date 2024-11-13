<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Routing\UrlGenerator;
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
        //
        $this->app->bind('User', function ($app) {
            return 'tests';
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        // if(env('APP_ENV') != 'local')
        // {
        //     $this->app['request']->server->set('HTTPS', true);
        // }

    }
}
