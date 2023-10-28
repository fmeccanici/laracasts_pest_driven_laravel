<?php

use App\Jobs\HandlePaddlePurchaseJob;
use Spatie\WebhookClient\Models\WebhookCall;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\post;

it('stores a paddle purchase request', function() {
    // Arrange
    Queue::fake();
    assertDatabaseCount(WebhookCall::class, 0);

    // Act
    post('webhooks', getValidPaddleRequestData());

    // Assert
    assertDatabaseCount(WebhookCall::class, 1);
});

it('does not store invalid paddle purchase requests', function() {
    // Arrange
    assertDatabaseCount(WebhookCall::class, 0);

    // Act
    post('webhooks', getInvalidPaddleRequestData());

    // Assert
    assertDatabaseCount(WebhookCall::class, 0);
});

it('dispatches a job for a valid paddle request', function() {
    // Arrange
    Queue::fake();

    // Act & Assert
    post('webhooks', getValidPaddleRequestData());

    Queue::assertPushed(HandlePaddlePurchaseJob::class);
});

it('does not dispatcha job for an invalid paddle request', function() {
    // Arrange
    Queue::fake();

    // Act & Assert
    post('webhooks', getInvalidPaddleRequestData());

    Queue::assertNothingPushed();
});

/**
 * @return array
 */
function getValidPaddleRequestData(): array
{
    return [
        'event_time' => '2022-09-06 11:19:25',
        'p_country' => 'NL',
        'p_coupon' => null,
        'p_coupon_savings' => '0',
        'p_currency' => 'EUR',
        'p_custom_data' => null,
        'p_earnings' => '{"4736":"55.5500"}',
        'p_order_id' => '422907',
        'p_paddle_fee' => '3.45',
        'p_price' => '59',
        'p_product_id' => '34779',
        'p_quantity' => '1',
        'p_sale_gross' => '59',
        'p_tax_amount' => '0',
        'p_used_price_override' => '1',
        'passthrough' => 'Example passthrough',
        'quantity' => '1',
        'p_signature' => 'bcun+yyV0eFwkPXNhkh+v8Zzr+esBYyVifnGvc19Dq1lPXnN/voTnQIf9s480AC9oR7joL2RHONfr0K1OBiuOvWC/Nj0gehUVimt9Y8Ie+o0ybeJmUA4bq+a8mTCoRosJpDdjjfWGba6vdvmvGCIg33q7bBSl97Zo2Gm1dkBgaKXlH6J8LVTLZANn0hXLgP4oWPH5+SgHv5A9gbjDAMRaZoKtFAtCcJ0RGGZFGZqr+Hj+gLWncvgMoEa1XMOo4aoZu6gws31EJL/x74iRdV1Gpuf0urKXh7ItqiRYvhKa4kT30T1wvUEhy+zmu+UbY/eGUm73bVSgHvbkCej+nRshof7Xhrdes0Y8jFccI5X5J3XSbvZW8AVUo6LgzfYoYLXJHruUH7NbR2EyW0nSaZOFspSwtOQIhhSmb94En2NeVLDzHJkzlSXx7/YPY7grjJuWQaemiX0b2ukTwgKQuLzcqisMwIURPdJ88NljKoo3th6COWmmurH3MVHKlmJezjdOoYoBTqdM3pEKLfmjasDsYpOlkzms0zEYNDE24VposzaO2YVnBnwY2ElFruKYtYC1+h61sqqFFgV5ckU8xIUmKuSg9VqM6hDhTJ2VaykcKsnnDy/6ZW/NaoBskWc9g5FFmMnX2cVfM5T/1u6+upN93XihoW4JDq8/U5esZN5DIw=',
    ];
}

/**
 * @return array
 */
function getInvalidPaddleRequestData(): array
{
    return [];
}
