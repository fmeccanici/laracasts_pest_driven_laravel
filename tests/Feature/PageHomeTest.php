<?php

use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('shows courses overview', function () {
    // Arrange
    $firstCourse = Course::factory()->released()->create();
    $secondCourse = Course::factory()->released()->create();
    $lastCourse = Course::factory()->released()->create();

    // Act & Assert
    get(route('home'))->assertSeeText([
        $firstCourse->title,
        $firstCourse->description,
        $secondCourse->title,
        $secondCourse->description,
        $lastCourse->title,
        $lastCourse->description,
    ]);
});

it('shows only released courses', function () {
    // Arrange
    $releasedCourse = Course::factory()->released()->create();
    $nonReleasedCourse = Course::factory()->create();

    // Act & Assert
    get(route('home'))
        ->assertSeeText($releasedCourse->title)
        ->assertDontSeeText($nonReleasedCourse->title);
});

it('shows courses by release date', function () {
    // Arrange
    $releasedCourse = Course::factory()->released(Carbon::yesterday())->create();
    $newestReleasedCourse = Course::factory()->released()->create();

    // Act & Assert
    get(route('home'))->assertSeeTextInOrder([
        $newestReleasedCourse->title,
        $releasedCourse->title,
    ]);
});
