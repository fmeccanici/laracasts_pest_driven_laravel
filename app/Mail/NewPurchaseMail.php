<?php

namespace App\Mail;

use App\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewPurchaseMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Course $course)
    {
    }

    public function build(): self
    {
        return $this->markdown('emails.new-purchase');
    }
}
