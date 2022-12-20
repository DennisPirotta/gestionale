<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessHour extends Model
{
    use HasFactory;

    protected $fillable = [
        'morning_start', 'morning_end', 'afternoon_start', 'afternoon_end', 'week_day', 'user_id', 'total',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function init(User $user): void
    {
        for ($i = 0; $i < 5; $i++) {
            $date = Carbon::parse('5-1-1970')->addDays($i);
            self::create([
                'user_id' => $user->id,
                'week_day' => strtolower($date->format('l')),
                'morning_start' => Carbon::parse('8:00'),
                'morning_end' => Carbon::parse('12:30'),
                'afternoon_start' => Carbon::parse('13:30'),
                'afternoon_end' => Carbon::parse('17:00'),
            ]);
        }
    }

    public static function getWorkingHours(User $user): array
    {
        $hours = self::where('user_id', $user->id)->get();
        $data = [];
        foreach ($hours as $hour) {
            $data[$hour->week_day] = [
                Carbon::parse($hour->morning_start)->format('H:i').'-'.Carbon::parse($hour->morning_end)->format('H:i'),
                Carbon::parse($hour->afternoon_start)->format('H:i').'-'.Carbon::parse($hour->afternoon_end)->format('H:i'),
            ];
        }
        $data['saturday'] = [];
        $data['sunday'] = [];

        $exceptions = [
            '04-25' => ['00:00-23:59'],
            '05-01' => ['00:00-23:59'],
            '06-02' => ['00:00-23:59'],
            '01-01' => ['00:00-23:59'],
            '01-06' => ['00:00-23:59'],
            '08-15' => ['00:00-23:59'],
            '11-01' => ['00:00-23:59'],
            '12-08' => ['00:00-23:59'],
            '12-25' => ['00:00-23:59'],
            '12-26' => ['00:00-23:59'],
        ];

        $data['exceptions'] = $exceptions;

        return $data;
    }
}
