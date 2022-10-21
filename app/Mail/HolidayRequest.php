<?php

namespace App\Mail;

use App\Models\Holiday;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class HolidayRequest extends Mailable
{
    use Queueable, SerializesModels;

    private string $start;
    private string $end;
    private string $user;
    private bool $approved;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Holiday $holiday)
    {
        $this->start = $holiday->start;
        $this->end = $holiday->end;
        $this->user = $holiday->user->name . ' ' . $holiday->user->surname;
        $this->approved = $holiday->approved;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Richiesta Ferie')
                    ->markdown('holidays.email',[
                        'start' => Carbon::parse($this->start)->translatedFormat('l j F Y'),
                        'end' => Carbon::parse($this->end)->modify('-1 day')->translatedFormat('l j F Y'),
                        'user' => $this->user,
                        'approved' => $this->approved
                    ]);
    }
}
