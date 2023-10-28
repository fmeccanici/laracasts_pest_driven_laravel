<?php

use App\Jobs\HandlePaddlePurchaseJob;
use App\Models\Course;
use App\Models\PurchasedCourse;
use App\Models\User;
use Spatie\WebhookClient\Models\WebhookCall;

it('stores paddle purchase', function () {
    // Assert
    $this->assertDatabaseEmpty(User::class);
    $this->assertDatabaseEmpty(PurchasedCourse::class);

    // Arrange
    $course = Course::factory()->released()->create([
        'paddle_product_id' => 'product-id'
    ]);
    $webhookCall = WebhookCall::create([
        'name' => 'default',
        'url' => 'some-url',
        'payload' => [
            'email' => 'test@test.nl',
            'name' => 'Test User',
            'p_product_id' => 'product-id',
        ]
    ]);

    // Act
    (new HandlePaddlePurchaseJob($webhookCall))->handle();

    // Assert
    $this->assertDatabaseHas(User::class, [
        'email' => 'test@test.nl',
        'name' => 'Test User',
    ]);

    $user = User::where('email', 'test@test.nl')->first();
    $this->assertDatabaseHas(PurchasedCourse::class, [
        'user_id' => $user->id,
        'course_id' => $course->id,
    ]);
});

it('stores paddle purchase for given user', function () {
    // Arrange


    // Act & Assert

});

it('sends out purchase email', function () {
    // Arrange


    // Act & Assert

});
