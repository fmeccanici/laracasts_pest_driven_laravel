<?php


use App\Models\Course;
use App\Models\User;
use App\Models\Video;

it('gives back readable video duration', function() {
    // Arrange
    $video = Video::factory()->create(['duration_in_mins' => 10]);

    // Act & Assert
    expect($video->getReadableDuration())->toEqual('10min');
});

it('belongs to a course', function() {
    // Arrange
    $video = Video::factory()
        ->has(Course::factory())
        ->create();

    // Act & Assert
    expect($video->course)
        ->toBeInstanceOf(Course::class);
});

it('tells if current user has not yet watched given video', function() {
    // Arrange
    $video = Video::factory()->create();

    // Act & Assert
    loginAsUser();
    expect($video->alreadyWatchedByCurrentUser())->toBeFalse();
});

it('tells if current user has watched given video', function() {
    // Arrange
    $user = User::factory()
        ->has(Video::factory(), 'watchedVideos')
        ->create();

    // Act & Assert
    loginAsUser($user);
    expect($user->watchedVideos()->first()->alreadyWatchedByCurrentUser())->toBeTrue();
});
