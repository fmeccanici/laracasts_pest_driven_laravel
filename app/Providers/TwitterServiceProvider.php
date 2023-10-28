<?php

namespace App\Providers;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Services\Twitter\NullTwitterClient;
use App\Services\Twitter\TwitterClient;
use App\Services\Twitter\TwitterClientInterface;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class TwitterServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TwitterOAuth::class, function () {
            return new TwitterOAuth(
                config('services.twitter.consumer_key'),
                config('services.twitter.consumer_secret'),
                config('services.twitter.access_token'),
                config('services.twitter.access_token_secret'),
            );
        });

        $this->app->bind(TwitterClientInterface::class, function (Application $app) {
            if ($app->environment() === 'production')
            {
                return app(TwitterClient::class);
            }

            return new NullTwitterClient;
        });
    }

    public function boot(): void
    {
    }
}
