<?php

namespace App\Providers;

use App\Repositories\ResumeRepository;
use App\Services\Resume\Contracts\ResumeRepoContract;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;
use SocialiteProviders\Manager\SocialiteWasCalled;
use SocialiteProviders\Telegram\Provider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ResumeRepoContract::class, ResumeRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
      //  Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
        Event::listen(function (SocialiteWasCalled $event) {
            $event->extendSocialite('telegram', Provider::class);
        });
    }
}
