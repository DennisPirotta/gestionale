<?php

namespace App\Models;

use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Holiday extends Model
{
    use HasFactory;

    /**
     * @throws Exception
     */
    public static function isValid(Request $request): bool
    {
        $start = new DateTime($request->start);
        $end = new DateTime($request->end);

        if ($request->allDay){
            $start->setTime(0,0);
            $end->setTime(0,0);
        }

        return self::getLeftHours() - abs(Carbon::parse($request->start)->diffInBusinessHours($request->end)) + abs(Carbon::parse($request->old_start)->diffInBusinessHours($request->old_end)) >= 0;
    }

    /**
     * @throws Exception
     */
    public static function getLeftHours(): int
    {
        $count = 0;
        $hours = Hour::all();
        foreach (self::where('user', auth()->user()->id)->get() as $holiday) {
            $start = new DateTime($hours->where('holiday',$holiday->id)->pluck('start')->first());
            $end = new DateTime($hours->where('holiday',$holiday->id)->pluck('end')->first());

            if ($holiday->allDay){
                $start->setTime(0,0);
                $end->setTime(0,0);
            }

            $count += abs(Carbon::parse($start)->diffInBusinessHours($end));
        }
        return auth()->user()->holidays - $count;
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
