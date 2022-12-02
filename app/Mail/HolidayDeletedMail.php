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

    public string $start;

    public string $end;

    public string $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Holiday $holiday)
    {
        $this->start = Carbon::parse($holiday->start)->translatedFormat('l j F Y');
        $this->end = Carbon::parse($holiday->end)->translatedFormat('l j F Y');
        $this->user = $holiday->user->name.' '.$holiday->user->surname;
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
                'start' => $this->start,
                'end' => $this->end,
                'user' => $this->user,
            ]);
    }
}
