<?php

namespace App\Providers;

use App\Repositories\AuthRepository;
use App\Repositories\ArticleRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\AuthRepositoryInterface;
use App\Repositories\ArticleRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        // $this->app->bind(ArticleRepositoryInterface::class, ArticleRepository::class);
    }
}
