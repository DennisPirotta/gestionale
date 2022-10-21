<?php

namespace App\Models;

use App\Mail\HolidayRequest;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','approved','start','end'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function sendMail(): void
    {
        $to = '';
        if ($this->user->company->id === 1) // 3D
        {
            $to = 'amministrazione@3dautomation.it';
            $cc = ['administration@sphtechnology.ch'];
        }else if ($this->user->company->id === 2) // SPH
        {
            $to = 'administration@sphtechnology.ch';
            $cc = ['amministrazione@3dautomation.it'];
        }
        $cc[] = 'angelo.dariol@sphtechnology.ch';
        $cc[] = 'andrea.dariol@sphtechnology.ch';
        Mail::to($to)->cc($cc)->send(new HolidayRequest($this));
        //Mail::to('dennispirotta@gmail.com')->send(new HolidayRequest($this));
    }
}
