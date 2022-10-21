<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BugReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $object;
    public string $description;
    public string $sender;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($object,$description,$sender)
    {
        $this->object = $object;
        $this->description = $description;
        $this->sender = $sender;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Bug Report')
                    ->markdown('debug.email',[
                        'sender' => $this->sender,
                        'object' => $this->object,
                        'description' => $this->description
                    ]);
    }
}
