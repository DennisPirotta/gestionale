<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

class BusinessHour extends Model
{
    use HasFactory;

    protected $fillable = [
      'morning_start','morning_end','afternoon_start','afternoon_end','week_day','user_id','total'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public static function init(User $user): void
    {
        Log::channel('dev')->info("Creating business hour for user " . $user->email);
        for($i = 0 ; $i < 5 ; $i++){
            $date = Carbon::parse('5-1-1970')->addDays($i);
            $hour = self::create([
                'user_id' => $user->id,
                'week_day' => strtolower($date->format('l')),
                'morning_start' => Carbon::parse('8:00'),
                'morning_end' => Carbon::parse('12:30'),
                'afternoon_start' => Carbon::parse('13:30'),
                'afternoon_end' => Carbon::parse('17:00')
            ]);
            Log::channel('dev')->info($hour);
        }
    }

    public static function getWorkingHours(User $user): array
    {
        $hours = self::where('user_id',$user->id)->get();
        $data = [];
        foreach ($hours as $hour){
            $data[$hour->week_day] = [
                Carbon::parse($hour->morning_start)->format('H:i') . "-" . Carbon::parse($hour->morning_end)->format('H:i') ,
                Carbon::parse($hour->afternoon_start)->format('H:i') . "-" . Carbon::parse($hour->afternoon_end)->format('H:i')
            ];
        }
        $data['saturday'] = [];
        $data['sunday'] = [];
        return $data;
    }
}
