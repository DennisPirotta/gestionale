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

    public string $start;
    public string $end;
    public string $user;
    public bool $approved;
    public string $old_start = '';
    public string $old_end = '';


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Holiday $holiday,Holiday $old)
    {
        $this->start = Carbon::parse($holiday->start)->translatedFormat('l j F Y');
        $this->end = Carbon::parse($holiday->end)->translatedFormat('l j F Y');
        $this->user = $holiday->user->name . ' ' . $holiday->user->surname;
        $this->approved = $holiday->approved;
        if ($old !== null){
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
                    ->markdown('holidays.email',[
                        'start' => $this->start,
                        'end' => $this->end,
                        'user' => $this->user,
                        'approved' => $this->approved,
                        'old_start' => $this->old_start,
                        'old_end' => $this->old_end
                    ]);
    }
}
