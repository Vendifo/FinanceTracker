<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Interfaces\Services\UserServiceinterface;
use App\Interfaces\Services\RoleServiceInterface;
use App\Interfaces\Services\ArticleServiceInterface;

use App\Services\UserService;
use App\Services\RoleService;
use App\Services\ArticleService;

use App\Interfaces\Repositories\UserRepositoryInterface;
use App\Interfaces\Repositories\RoleRepositoryInterface;
use App\Interfaces\Repositories\ArticleRepositoryInterface;

use App\Repositories\UserRepository;
use App\Repositories\RoleRepository;
use App\Repositories\ArticleRepository;



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

        $this->app->bind(ArticleRepositoryInterface::class, ArticleRepository::class);
        $this->app->bind(ArticleServiceInterface::class, ArticleService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
