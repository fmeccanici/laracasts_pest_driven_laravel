<?php

namespace App\Jobs;

use App\Models\Course;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob;
use Str;

class HandlePaddlePurchaseJob extends ProcessWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $user = User::create([
            'name' => $this->webhookCall->payload['name'],
            'email' => $this->webhookCall->payload['email'],
            'password' => bcrypt(Str::uuid()),
        ]);

        $course = Course::where('paddle_product_id', $this->webhookCall->payload['p_product_id'])->first();
        $user->purchasedCourses()->attach($course);
    }
}
