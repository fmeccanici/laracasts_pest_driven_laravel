<?php


use App\Models\Video;

test('', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

it('gives back readble video duration', function() {
    // Arrange
    $video = Video::factory()->create(['duration_in_mins' => 10]);

    // Act & Assert
    expect($video->getReadableDuration())->toEqual('10min');
});
