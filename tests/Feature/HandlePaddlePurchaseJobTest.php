<?php

use App\Jobs\HandlePaddlePurchaseJob;
use App\Mail\NewPurchaseMail;
use App\Models\Course;
use App\Models\PurchasedCourse;
use App\Models\User;
use Spatie\WebhookClient\Models\WebhookCall;

it('stores paddle purchase', function () {
    // Assert
    Mail::fake();
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
    Mail::fake();
    $user = User::factory()->create(['email' => 'test@test.nl']);
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
    $this->assertDatabaseCount(User::class, 1);
    $this->assertDatabaseHas(User::class, [
        'email' => $user->email,
        'name' => $user->name,
    ]);

    $this->assertDatabaseHas(PurchasedCourse::class, [
        'user_id' => $user->id,
        'course_id' => $course->id,
    ]);
});

it('sends out purchase email', function () {
    // Arrange
    Mail::fake();
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
    Mail::assertSent(NewPurchaseMail::class);
});
