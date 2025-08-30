<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Interfaces\Services\UserServiceinterface;
use App\Services\UserService;

use App\Interfaces\Repositories\UserRepositoryInterface;
use App\Repositories\UserRepository;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserServiceinterface::class, UserService::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
