<?php

namespace App\Services\Resume;

use App\Services\Resume\Contracts\ResumeRepoContract;
use Illuminate\Support\ServiceProvider;

class ResumeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(
            ResumeService::class,
            fn($app) => new ResumeService($app->make(ResumeRepoContract::class))
        );
    }
}