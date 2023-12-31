<?php

use App\Models\Course;
use App\Models\User;

use App\Models\Video;
use function Pest\Laravel\get;

it('returns a successful response', function () {
    // Act & Assert
    get(route('pages.home'))
        ->assertOk();
});

it('gives back successful response for course details page', function() {
    // Arrange
    $course = Course::factory()->released()->create();

    // Act & Assert
    get(route('pages.course-details', $course))
        ->assertOk();
});

it('gives back successful response for dashboard page', function() {
    // Arrange
    $course = Course::factory()->released()->create();

    // Act & Assert
    loginAsUser();

    get(route('pages.dashboard', $course))
        ->assertOk();
});

it('does not find JetStream registration page', function() {
    // Arrange & Act
    get('register')
        ->assertNotFound();
});

it('gives successful response for videos page', function() {
    // Arrange
    $course = Course::factory()
        ->has(Video::factory())
        ->create();

    // Act & Assert
    loginAsUser();
    get(route('page.course-videos', $course))
        ->assertOk();
});
