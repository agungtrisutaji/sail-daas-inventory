<?php

use Carbon\Carbon;

if (! function_exists('getDateTimeValue')) {
    function getDateTimeValue($date, $now = false)
    {
        if ($date !== null) {
            $finishDate = Carbon::parse($date ? $date : null)->format('Y-m-d H:i');
        } elseif ($now !== false) {
            $finishDate = Carbon::now()->format('Y-m-d H:i');
        } else {
            $finishDate = null;
        }
        return $finishDate;
    }
}
