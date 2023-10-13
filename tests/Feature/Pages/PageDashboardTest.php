<?php

use App\Models\Course;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('cannot be accessed by guest', function () {
    // Act & Assert
    get(route('dashboard'))
        ->assertRedirect(route('login'));
});

it('list purchased orders', function() {
    // Arrange
    $user = User::factory()
        ->has(Course::factory()->count(2)->state(
                new Sequence(['title' => 'Course A'], ['title' => 'Course B'])
            )
        )
        ->create();

    // Act & Assert
    $this->actingAs($user);
    get(route('dashboard'))
        ->assertOk()
        ->assertSeeText(['Course A', 'Course B']);
});

it('does not list other courses', function() {
    // Arrange
    $user = User::factory()->create();
    $course = Course::factory()->released()->create();

    // Act & Assert
    $this->actingAs($user);
    get(route('dashboard', $course))
        ->assertOk()
        ->assertDontSeeText($course->title);
});

it('shows latest purchased courses first', function() {
    // Arrange
    $user = User::factory()->create();
    $firstPurchasedCourse = Course::factory()->released()->create();
    $lastPurchasedCourse = Course::factory()->released()->create();
    $user->courses()->attach($firstPurchasedCourse, ['created_at' => Carbon::yesterday()]);
    $user->courses()->attach($lastPurchasedCourse, ['created_at' => Carbon::today()]);

    // Act
    $this->actingAs($user);
    get(route('dashboard'))
        ->assertOk()
        ->assertSeeTextInOrder([$lastPurchasedCourse->title, $firstPurchasedCourse->title]);
});

it('includes link to product videos', function() {
    // Arrange
    $user = User::factory()
        ->has(Course::factory())
        ->create();

    // Act & Assert
    $this->actingAs($user);
    get(route('dashboard'))
        ->assertOk()
        ->assertSeeText('Watch videos')
        ->assertSee(route('page.course-videos', Course::first()));
});
