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

    public static function getWorkingDays($from, $to): int
    {
        $workDays = [1, 2, 3, 4, 5]; # date format = N (1 = Monday, ...)
        if (is_string($from)) {
            $from = DateTime::createFromFormat('Y-m-d', $from);
        }
        if (is_string($to)) {
            $to = DateTime::createFromFormat('Y-m-d', $to);
        }
        $interval = DateInterval::createFromDateString('1 day');
        $periods = new DatePeriod($from, $interval, $to);

        $days = 0;
        foreach ($periods as $period) {
            if (!in_array($period->format('N'), $workDays, true)) {
                $days++;
            }
        }
        return $days;
    }
}
