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


use App\Domain\Income\Repositories\IncomeRepository;
use App\Domain\Income\Repositories\IncomeRepositoryInterface;
use App\Domain\Income\Services\IncomeService;
use App\Domain\Income\Services\IncomeServiceInterface;


use App\Domain\Office\Repositories\OfficeRepository;
use App\Domain\Office\Repositories\OfficeRepositoryInterface;
use App\Domain\Office\Services\OfficeService;
use App\Domain\Office\Services\OfficeServiceInterface;

use App\Domain\Role\Repositories\RoleRepository;
use App\Domain\Role\Repositories\RoleRepositoryInterface;
use App\Domain\Role\Services\RoleService;
use App\Domain\Role\Services\RoleServiceInterface;


use App\Domain\User\Repositories\UserRepository;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\Services\UserService;
use App\Domain\User\Services\UserServiceinterface;


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
