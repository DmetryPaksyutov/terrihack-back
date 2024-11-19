<?php

namespace App\Services\AI;

use Illuminate\Support\ServiceProvider;
use OpenAI\Client;

class AiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(AiService::class, fn($app) => new AiService(
            $app->make(Client::class),
            env('OPENAI_MODEL'),
        ));
    }
}