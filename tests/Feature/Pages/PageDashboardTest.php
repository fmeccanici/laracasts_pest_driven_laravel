<?php

use App\Models\Course;
use App\Models\User;
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


    // Act


    // Assert

});

it('shows latest purchased courses first', function() {
    // Arrange


    // Act


    // Assert

});

it('includes link to product vidoes', function() {
    // Arrange


    // Act


    // Assert

});
