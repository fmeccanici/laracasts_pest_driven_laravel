<?php

use App\Models\Course;
use App\Models\Video;
use function Pest\Laravel\get;

it('does not find unreleased courses', function() {
    // Arrange
    $course = Course::factory()->create();

    // Act & Assert
    get(route('pages.course-details', $course))
        ->assertNotFound();
});

it('shows course details', function () {
    // Arrange
    $course = Course::factory()->released()->create();

    // Act & Assert
    get(route('pages.course-details', $course))
        ->assertOk()
        ->assertSeeText([
            $course->title,
            $course->description,
            $course->tagline,
            ...$course->learnings,
        ])
        ->assertSee(asset("images/$course->image_name"));
});

it('shows course video count', function () {
    // Arrange
    $course = Course::factory()
        ->released()
        ->has(Video::factory()->count(3))
        ->create();

    // Act & Assert
    get(route('pages.course-details', $course))
        ->assertOk()
        ->assertSeeText('3 videos');
});

it('includes paddle checkout button', function() {
    // Arrange
    config()->set('services.paddle.vendor-id', 'vendor-id');

    $course = Course::factory()->released()->create([
        'paddle_product_id' => 'product-id'
    ]);

    // Act & Assert
    get(route('page.course-details', $course))
        ->assertOk()
        ->assertSee('Paddle.Setup({vendor: vendor-id});')
        ->assertSee('<script src="https://cdn.paddle.com/paddle/paddle.js"></script>', false)
        ->assertSee('<a href="#!" data-product="product-id" data-theme="none" class="paddle_button', false);
});
