<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessHour extends Model
{
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public static function init(User $user): void
    {
        for($i = 0 ; $i < 5 ; $i++){
            $date = Carbon::parse('5-1-1970')->addDays($i);
            BusinessHour::factory()->create([
                'user_id' => $user->id,
                'week_day' => strtolower($date->format('l')),
                'morning_start' => Carbon::parse('8:00'),
                'morning_end' => Carbon::parse('12:30'),
                'afternoon_start' => Carbon::parse('13:30'),
                'afternoon_end' => Carbon::parse('17:00'),
                'total' => 8
            ]);
        }
    }
}
