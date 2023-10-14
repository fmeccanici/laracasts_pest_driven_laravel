<?php

use App\Models\Course;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Sequence;
use function Pest\Laravel\get;

it('cannot be accessed by guest', function () {
    // Act & Assert
    get(route('pages.dashboard'))
        ->assertRedirect(route('login'));
});

it('list purchased orders', function() {
    // Arrange
    $user = User::factory()
        ->has(Course::factory()->count(2)->state(
                new Sequence(['title' => 'Course A'], ['title' => 'Course B'])
            ), 'purchasedCourses')
        ->create();

    // Act & Assert
    loginAsUser($user);
    get(route('pages.dashboard'))
        ->assertOk()
        ->assertSeeText(['Course A', 'Course B']);
});

it('does not list other courses', function() {
    // Arrange
    $course = Course::factory()->released()->create();

    // Act & Assert
    loginAsUser();
    get(route('pages.dashboard', $course))
        ->assertOk()
        ->assertDontSeeText($course->title);
});

it('shows latest purchased courses first', function() {
    // Arrange
    $user = User::factory()->create();
    $firstPurchasedCourse = Course::factory()->released()->create();
    $lastPurchasedCourse = Course::factory()->released()->create();
    $user->purchasedCourses()->attach($firstPurchasedCourse, ['created_at' => Carbon::yesterday()]);
    $user->purchasedCourses()->attach($lastPurchasedCourse, ['created_at' => Carbon::today()]);

    // Act
    loginAsUser($user);
    get(route('pages.dashboard'))
        ->assertOk()
        ->assertSeeTextInOrder([$lastPurchasedCourse->title, $firstPurchasedCourse->title]);
});

it('includes link to product videos', function() {
    // Arrange
    $user = User::factory()
        ->has(Course::factory(), 'purchasedCourses')
        ->create();

    // Act & Assert
   loginAsUser($user);
    get(route('pages.dashboard'))
        ->assertOk()
        ->assertSeeText('Watch videos')
        ->assertSee(route('page.course-videos', Course::first()));
});

it('includes logout', function() {
    // Act & Assert
    loginAsUser();
    get(route('pages.dashboard'))
        ->assertOk()
        ->assertSeeText('Log Out')
        ->assertSee(route('logout'));
});
