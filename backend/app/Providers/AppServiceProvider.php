<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Interfaces\Services\UserServiceinterface;
use App\Interfaces\Services\RoleServiceInterface;

use App\Services\UserService;
use App\Services\RoleService;

use App\Interfaces\Repositories\UserRepositoryInterface;
use App\Interfaces\Repositories\RoleRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\RoleRepository;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserServiceinterface::class, UserService::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        $this->app->bind(RoleServiceInterface::class, RoleService::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
