<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Date;
use App\Exceptions\Handler;
use Illuminate\Contracts\Debug\ExceptionHandler;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ExceptionHandler::class, Handler::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        // Match Google's standard OAuth2 token expiration (1 hour)
        Passport::tokensExpireIn(Date::now()->addHour());

        // Set refresh tokens to expire in 10 days
        Passport::refreshTokensExpireIn(Date::now()->addDays(10));

        // Set personal access tokens to expire in 6 months
        Passport::personalAccessTokensExpireIn(Date::now()->addMonths(6));
    }
}
