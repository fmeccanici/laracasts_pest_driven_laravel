<?php

use App\Services\Twitter\NullTwitterClient;
use App\Services\Twitter\TwitterClient;
use App\Services\Twitter\TwitterClientInterface;

it('returns null twitter client for testing environment', function() {
    // Act & Assert
    expect(app(TwitterClientInterface::class))
        ->toBeInstanceOf(NullTwitterClient::class);
});

it('returns twitter client in production environment', function() {
    // Act & Assert
    config()->set('app.env', 'production');
    expect(app(TwitterClientInterface::class))
        ->not->toBeInstanceOf(TwitterClient::class);
});
