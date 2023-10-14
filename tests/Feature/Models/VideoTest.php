<?php


use App\Models\Course;
use App\Models\Video;

it('gives back readble video duration', function() {
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
