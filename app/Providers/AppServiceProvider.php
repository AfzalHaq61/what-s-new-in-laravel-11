<?php

namespace App\Providers;

use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Http\Middleware\TrimStrings;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

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
        RateLimiter::for('api', function() {
            return [
                Limit::perMinute(30),
                Limit::perSecond(2)
            ];
        });

        Request::macro('identifier', function() {
            return once(fn() => Str::uuid());
        } );

        TrimStrings::except(['secret']);

        RedirectIfAuthenticated::redirectUsing(fn($request) => route('dashboard'));
    }
}
