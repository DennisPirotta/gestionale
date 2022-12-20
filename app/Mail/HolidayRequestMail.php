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

    private string $start;

    private string $end;

    private string $user;

    private bool $approved;

    private string $old_start = '';

    private string $old_end = '';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Holiday $holiday, Holiday $old = null)
    {
        $this->start = Carbon::parse($holiday->start)->translatedFormat('l j F Y');
        $this->end = Carbon::parse($holiday->end)->translatedFormat('l j F Y');
        $this->user = $holiday->user->name.' '.$holiday->user->surname;
        $this->approved = $holiday->approved;
        if ($old !== null) {
            $this->old_start = Carbon::parse($old->start)->translatedFormat('l j F Y');
            $this->old_end = Carbon::parse($old->end)->translatedFormat('l j F Y');
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Richiesta Ferie')
                    ->markdown('mails.holidays.request', [
                        'start' => $this->start,
                        'end' => $this->end,
                        'user' => $this->user,
                        'approved' => $this->approved,
                        'old_start' => $this->old_start,
                        'old_end' => $this->old_end,
                    ]);
    }
}
