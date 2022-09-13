<?php

namespace App\Models;

use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','hour_id'
    ];

    /**
     * @throws Exception
     */
    public static function isValid(Request $request): bool
    {
        $start = new DateTime($request->start);
        $end = new DateTime($request->end);
        $old_start = new DateTime($request->old_start);
        $old_end = new DateTime($request->old_end);

        if ($request->allDay){
            $start->setTime(0,0);
            $end->setTime(0,0);
            $old_end->setTime(0,0);
            $old_start->setTime(0,0);
        }

        return self::getLeftHours() - abs(Carbon::parse($start)->diffInBusinessHours($end)) + abs(Carbon::parse($old_start)->diffInBusinessHours($old_end)) >= 0;
    }

    /**
     * @throws Exception
     */
    public static function getLeftHours(): int
    {
        $count = 0;

        foreach (self::with(['hour'])->where('user_id', auth()->id())->get() as $holiday) {
            $start = new DateTime($holiday->hour->start);
            $end = new DateTime($holiday->hour->end);

            if ($holiday->allDay){
                $start->setTime(0,0);
                $end->setTime(0,0);
            }

            $count += abs(Carbon::parse($start)->diffInBusinessHours($end));
        }
        return auth()->user()->holidays - $count;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function hour(): BelongsTo
    {
        return $this->belongsTo(Hour::class,'hour_id');
    }
}
