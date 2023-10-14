<?php

use App\Livewire\VideoPlayer;
use App\Models\Course;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Sequence;

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
            ->count(3)
            ->state(new Sequence(
                ['title' => 'First video'],
                ['title' => 'Second video'],
                ['title' => 'Third video'],
            )))
        ->create();

    // Act & Assert
    Livewire::test(VideoPlayer::class, ['video' => $course->videos()->first()])
        ->assertSee([
            'First video',
            'Second video',
            'Third video',
        ])->assertSeeHtml([
            route('page.course-videos', Video::where('title', 'First video')->first()),
            route('page.course-videos', Video::where('title', 'Second video')->first()),
            route('page.course-videos', Video::where('title', 'Third video')->first()),
        ]);
});

it('marks video as completed', function () {
    // Arrange
    $user = User::factory()->create();
    $course = Course::factory()
        ->has(Video::factory()->state(['title' => 'First video']))
        ->create();

    $user->courses()->attach($course);

    // Assert
    expect($user->videos)->toHaveCount(0);

    // Act & Assert
    loginAsUser($user);
    Livewire::test(VideoPlayer::class, ['video' => $course->videos()->first()])
        ->call('markVideoAsCompleted');

    // Assert
    $user->refresh();

    expect($user->videos)
        ->toHaveCount(1)
        ->first()->title->toEqual('First video');
});

it('marks video as not completed', function () {
    // Arrange
    $user = User::factory()->create();
    $course = Course::factory()
        ->has(Video::factory()->state(['title' => 'First video']))
        ->create();

    $user->courses()->attach($course);
    $user->videos()->attach($course->videos()->first());

    // Assert
    expect($user->videos)->toHaveCount(1);

    // Act & Assert
    loginAsUser($user);
    Livewire::test(VideoPlayer::class, ['video' => $course->videos()->first()])
        ->call('markVideoAsNotCompleted');

    // Assert
    $user->refresh();

    expect($user->videos)
        ->toHaveCount(0);
});
