<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TemplateMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(private string $subjectText, private string $bodyText) {}

    public function build(): self
    {
        return $this->subject($this->subjectText)
            ->html($this->bodyText);
    }
}
