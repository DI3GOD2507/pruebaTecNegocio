<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Interfaces\CustomerRepositoryInterface::class,
            \App\Repositories\CustomerRepository::class
        );

        $this->app->bind(
            \App\Repositories\Interfaces\OrderRepositoryInterface::class,
            \App\Repositories\OrderRepository::class
        );

        $this->app->bind(
            \App\Repositories\Interfaces\PersonRepositoryInterface::class,
            \App\Repositories\PersonRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
