<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    public static function addBusinessDays(Carbon $date, int $days, array $holidays = []): Carbon
    {
        $count = 0;
        $current = $date->copy();

        while ($count < $days) {
            $current->addDay();
            $weekDay = $current->dayOfWeekIso;
            $dateStr = $current->format('Y-m-d');

            if ($weekDay <= 5 && !in_array($dateStr, $holidays)) {
                $count++;
            }
        }

        return $current;
    }

    public static function businessDaysBetween(Carbon $start, Carbon $end, array $holidays = []): int
    {
        $count = 0;
        $current = $start->copy();

        while ($current->lte($end)) {
            $weekDay = $current->dayOfWeekIso;
            $dateStr = $current->format('Y-m-d');

            if ($weekDay <= 5 && !in_array($dateStr, $holidays)) {
                $count++;
            }

            $current->addDay();
        }

        return $count;
    }
}
