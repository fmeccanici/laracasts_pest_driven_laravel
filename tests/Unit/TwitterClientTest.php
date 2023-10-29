<?php

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Services\Twitter\TwitterClient;

it('calls oauth client for a tweet', function() {
    // Assert
    $mock = \Pest\Laravel\mock(TwitterOAuth::class)
                ->shouldReceive('post')
                ->withArgs(['statuses/update', ['status' => 'My tweet message']])
                ->andReturn(['status' => 'My tweet message'])
                ->getMock();

    // Act
    $twitterClient = new TwitterClient($mock);

    // Act & Assert
    expect($twitterClient->tweet('My tweet message'))
        ->toBeArray()
        ->toHaveKey('status')
        ->toMatchArray(['status' => 'My tweet message']);
});
