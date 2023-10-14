<?php

use App\Livewire\VideoPlayer;
use App\Models\Course;
use App\Models\User;
use App\Models\Video;

it('shows details for given video', function () {
    // Arrange
    $course = Course::factory()->has(Video::factory())->create();

    // Act & Assert
    $video = $course->videos->first();
    Livewire::test(VideoPlayer::class, ['video' => $video])
        ->assertSeeText([
            $video->title,
            $video->description,
            "({$video->duration_in_mins}min)"
        ]);
});

it('shows given video', function () {
    // Arrange
    $course = Course::factory()
        ->has(Video::factory())->create();

    // Act & Assert
    $video = $course->videos->first();
    Livewire::test(VideoPlayer::class, ['video' => $video])
        ->assertSeeHtml('<iframe src="https://player.vimeo.com/video/'.$video->vimeo_id.'"');
});

it('shows list of all course videos', function () {
    // Arrange
    $course = Course::factory()
        ->has(Video::factory()
            ->count(3))
        ->create();

    // Act & Assert
    Livewire::test(VideoPlayer::class, ['video' => $course->videos()->first()])
        ->assertSee([
            ...$course->videos->pluck('title')->toArray()
        ])->assertSeeHtml([
            route('page.course-videos', $course->videos[0]),
            route('page.course-videos', $course->videos[1]),
            route('page.course-videos', $course->videos[2]),
        ]);
});

it('marks video as completed', function () {
    // Arrange
    $user = User::factory()->create();
    $course = Course::factory()
        ->has(Video::factory())
        ->create();

    $user->purchasedCourses()->attach($course);

    // Assert
    expect($user->watchedVideos)->toHaveCount(0);

    // Act & Assert
    loginAsUser($user);
    Livewire::test(VideoPlayer::class, ['video' => $course->videos()->first()])
        ->call('markVideoAsCompleted');

    // Assert
    $user->refresh();

    expect($user->watchedVideos)
        ->toHaveCount(1)
        ->first()->title->toEqual($course->videos()->first()->title);
});

it('marks video as not completed', function () {
    // Arrange
    $user = User::factory()->create();
    $course = Course::factory()
        ->has(Video::factory())
        ->create();

    $user->purchasedCourses()->attach($course);
    $user->watchedVideos()->attach($course->videos()->first());

    // Assert
    expect($user->watchedVideos)->toHaveCount(1);

    // Act & Assert
    loginAsUser($user);
    Livewire::test(VideoPlayer::class, ['video' => $course->videos()->first()])
        ->call('markVideoAsNotCompleted');

    // Assert
    $user->refresh();

    expect($user->watchedVideos)
        ->toHaveCount(0);
});
