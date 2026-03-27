<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;


use App\Repository\interfaces\UserRepositoryInterface;
use App\Repository\implement\UserRepository;

use App\Repository\interfaces\CourseRepositoryInterface;
use App\Repository\implement\CourseRepository;

use App\Repository\interfaces\GroupRepositoryInterface;
use App\Repository\implement\GroupRepository;

use App\Repository\interfaces\DomainRepositoryInterface;
use App\Repository\implement\DomainRepository;
use App\Repository\interfaces\EnrollmentRepositoryInterface;
use App\Repository\implement\EnrollmentRepository;
use App\Repository\interfaces\FavoriteRepositoryInterface;
use App\Repository\implement\FavoriteRepository;
use App\Repository\interfaces\PaymentRepositoryInterface;
use App\Repository\implement\PaymentRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
        $this->app->bind(GroupRepositoryInterface::class, GroupRepository::class);
        $this->app->bind(DomainRepositoryInterface::class, DomainRepository::class);
        $this->app->bind(EnrollmentRepositoryInterface::class, EnrollmentRepository::class);
        $this->app->bind(FavoriteRepositoryInterface::class, FavoriteRepository::class);
        $this->app->bind(PaymentRepositoryInterface::class, PaymentRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
