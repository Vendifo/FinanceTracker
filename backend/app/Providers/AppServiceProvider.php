<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Domain\Article\Repositories\ArticleRepository;
use App\Domain\Article\Repositories\ArticleRepositoryInterface;
use App\Domain\Article\Services\ArticleService;
use App\Domain\Article\Services\ArticleServiceInterface;


use App\Domain\Expense\Repositories\ExpenseRepository;
use App\Domain\Expense\Repositories\ExpenseRepositoryInterface;
use App\Domain\Expense\Services\ExpenseService;
use App\Domain\Expense\Services\ExpenseServiceInterface;


use App\Domain\Finance\Repositories\FinanceRepository;
use App\Domain\Finance\Repositories\FinanceRepositoryInterface;
use App\Domain\Finance\Services\FinanceService;
use App\Domain\Finance\Services\FinanceServiceInterface;


use App\Interfaces\Services\UserServiceinterface;
use App\Interfaces\Services\RoleServiceInterface;
use App\Interfaces\Services\OfficeServiceInterface;
use App\Interfaces\Services\IncomeServiceInterface;

use App\Services\UserService;
use App\Services\RoleService;
use App\Services\OfficeService;
use App\Services\IncomeService;

use App\Interfaces\Repositories\UserRepositoryInterface;
use App\Interfaces\Repositories\RoleRepositoryInterface;
use App\Interfaces\Repositories\OfficeRepositoryInterface;
use App\Interfaces\Repositories\IncomeRepositoryInterface;

use App\Repositories\UserRepository;
use App\Repositories\RoleRepository;
use App\Repositories\OfficeRepository;
use App\Repositories\IncomeRepository;



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

        $this->app->bind(OfficeRepositoryInterface::class, OfficeRepository::class);
        $this->app->bind(OfficeServiceInterface::class, OfficeService::class);

        $this->app->bind(IncomeRepositoryInterface::class, IncomeRepository::class);
        $this->app->bind(IncomeServiceInterface::class, IncomeService::class);

        $this->app->bind(ExpenseRepositoryInterface::class, ExpenseRepository::class);
        $this->app->bind(ExpenseServiceInterface::class, ExpenseService::class);

        $this->app->bind(FinanceRepositoryInterface::class, FinanceRepository::class);
        $this->app->bind(FinanceServiceInterface::class, FinanceService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
