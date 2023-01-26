<?php

namespace App\Models;

use App\Mail\HolidayApprovedMail;
use App\Mail\HolidayDeletedMail;
use App\Mail\HolidayRequestMail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Mail;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'approved', 'start', 'end','permission'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sendMail(Holiday $old = null, $approved = false, $deleted = false): void
    {
        if ($this->user->company->id === 1) { // 3D
            $to = 'amministrazione@3dautomation.it';
            $cc = ['administration@sphtechnology.ch'];
        } elseif ($this->user->company->id === 2) { // SPH
            $to = 'administration@sphtechnology.ch';
            $cc = ['amministrazione@3dautomation.it'];
        }
        $cc[] = 'angelo.dariol@sphtechnology.ch';
        $cc[] = 'andrea.dariol@sphtechnology.ch';
        if ($deleted) {
            Mail::to($to)->cc($cc)->send(new HolidayDeletedMail($this));
//            Mail::to('dennispirotta@gmail.com')->send(new HolidayDeletedMail($this));
        } elseif ($approved) {
            Mail::to($this->user->email)->send(new HolidayApprovedMail($this));
        } else {
            Mail::to($to)->cc($cc)->send(new HolidayRequestMail($this, $old));
//            Mail::to('dennispirotta@gmail.com')->send(new HolidayRequestMail($this, $old));
        }
    }
}
