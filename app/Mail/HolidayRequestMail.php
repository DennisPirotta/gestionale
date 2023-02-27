<?php

namespace App\Mail;

use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class HolidayRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    private Holiday $holiday;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Holiday $holiday, Holiday $old = null)
    {
        $this->holiday = $holiday;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): self
    {
        return $this->subject('Richiesta Ferie')
                    ->markdown('mails.holidays.request', [
                        'holiday' => $this->holiday
                    ]);
    }
}
