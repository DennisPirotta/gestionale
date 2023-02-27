<?php

namespace App\Mail;

use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class HolidayDeletedMail extends Mailable
{
    use Queueable, SerializesModels;

    private Holiday $holiday;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Holiday $holiday)
    {
        $this->holiday = $holiday;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Ferie Eliminate')
            ->markdown('mails.holidays.deleted', [
                'holiday' => $this->holiday
            ]);
    }
}
