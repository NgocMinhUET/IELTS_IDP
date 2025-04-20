<?php

namespace App\Helpers;

use Carbon\Carbon;

class FormatHelper
{
    public static function formatSetDate($date)
    {
        return Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
    }

    public static function formatGetDate($date)
    {
        return Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
    }

    public static function formatHour($hour)
    {
        return $hour != "" ? Carbon::createFromFormat('H:i:s', $hour)->format('H:i') : "";
    }

    public static function formatDayOfWeek($date)
    {
        return Carbon::createFromFormat('Y-m-d', $date)->format('l');
    }

    public static function slugAndUppercase($text)
    {
        $result = strtoupper(strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $text))));

        return $result;
    }

    public static function paginate($data)
    {
        return [
            'list' => $data->items(),
            'paginate' => [
                'current_page'   =>   $data->currentPage(),
                'from'           =>   $data->firstItem(),
                'last_page'      =>   $data->lastPage(),
                'per_page'       =>   $data->perPage(),
                'to'             =>   $data->lastItem(),
                'total'          =>   $data->total(),
            ]
        ];
    }
}
