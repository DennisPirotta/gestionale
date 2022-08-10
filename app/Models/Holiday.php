<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Holiday extends Model
{
    use HasFactory;

    public static function isValid(Request $request): bool
    {
        return self::getLeftHours() - abs(Carbon::parse($request->start)->diffInBusinessHours($request->end)) + abs(Carbon::parse($request->old_start)->diffInBusinessHours($request->old_end)) >= 0;
    }

    public static function getLeftHours(): int
    {
        $count = 0;
        foreach (self::where('user', auth()->user()->id)->get() as $holiday) {
            $count += abs(Carbon::parse($holiday->start)->diffInBusinessHours($holiday->end));
        }
        return auth()->user()->holidays - $count;
    }
}
