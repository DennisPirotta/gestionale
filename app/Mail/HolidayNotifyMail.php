<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class HolidayNotifyMail extends Mailable
{
    use Queueable, SerializesModels;

    private Collection $holidays;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Collection $holidays)
    {
        $this->holidays = $holidays;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): self
    {
        return $this->subject('Notifica Ferie')
            ->markdown('mails.holidays.notify', [
                'holidays' => $this->holidays
            ]);
    }
}
