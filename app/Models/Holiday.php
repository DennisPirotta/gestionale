<?php

namespace App\Models;

use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    public static function getWorkingDays($from, $to) {
        $holidayDays = [6,7]; # date format = N (1 = Monday, ...)
        //$holidayDays = ['']; # variable and fixed holidays
        if (gettype($from) === "string")
            $from = DateTime::createFromFormat('Y-m-d',$from);
        if (gettype($to) === "string")
            $to = DateTime::createFromFormat('Y-m-d',$to);
        //$to->modify('+1 day');
        $interval = DateInterval::createFromDateString('1 day');
        $periods = new DatePeriod($from, $interval, $to);

        $days = 0;
        foreach ($periods as $period) {
            if (!in_array($period->format('N'), $holidayDays)) $days++;
        }
        return $days;
    }
}
