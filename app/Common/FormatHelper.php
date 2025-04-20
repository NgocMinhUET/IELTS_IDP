<?php

namespace App\Common;

use Carbon\Carbon;
use Illuminate\Container\Container;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Route;

class FormatHelper
{
    /**
     * @param $date
     * @return string|null
     */
    public static function formatSetDate($date): ?string
    {
        return $date ? Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d') : null;
    }

    /**
     * @param $date
     * @return string|null
     */
    public static function formatGetDate($date): ?string
    {
        return $date ? Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y') : null;
    }

    /**
     * @param $date
     * @return string|null
     */
    public static function formatGetMonth($date): ?string
    {
        return $date ? Carbon::createFromFormat('Y-m-d', $date)->format('Y/m') : null;
    }

    /**
     * @param $date
     * @return string|null
     */
    public static function formatDateTimeToDate($date): ?string
    {
        return $date ? Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y/m/d') : null;
    }

    /**
     * @param $date
     * @return string|null
     */
    public static function formatGetDateTime($date): ?string
    {
        return $date ? Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y/m/d H:i:s') : null;
    }

    public static function formatHour($hour): string
    {
        return $hour != "" ? Carbon::createFromFormat('H:i:s', $hour)->format('H:i') : "";
    }

    public static function formatDayOfWeek($date): string
    {
        return Carbon::createFromFormat('Y-m-d', $date)->format('l');
    }

    public static function formatString($string): array|string|null
    {
        return preg_replace("/[^a-zA-Z]+/", "", $string);
    }

    public static function sortByKey($data): array
    {
        $data = collect($data)->sortKeys()->all();

        return array_values($data);
    }

    public static function slugAndUppercase($text): string
    {
        return strtoupper(strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $text))));
    }


    /**
     * @param $info
     * @param bool $showTime
     * @return void
     */
    public static function logInfoFile($info, bool $showTime = false): void
    {
        if (app()->environment(['local', 'dev'])) {
            // Save info to file
            $logFile = fopen(
                storage_path('logs' . DIRECTORY_SEPARATOR . date('Y-m-d') . '_info.log'),
                'a+'
            );
            $content = $showTime ? date('Y-m-d H:i:s') . ': ' . $info . PHP_EOL : $info . PHP_EOL;
            fwrite($logFile, $content);
            fclose($logFile);
        }
    }

    /**
     * @param $item
     * @param $result
     * @param string $type
     * @return mixed
     */
    public static function showOrganizationName($item, &$result, string $type = 'burden'): mixed
    {
        if ($type == 'burden' && isset($item->burdenOrganization->name)) {
            $result[] = $item->burdenOrganization->name;
            self::showOrganizationName($item->burdenOrganization, $result, $type);
        } elseif ($type == 'parent' && isset($item->parentOrganization->name)) {
            $result[] = $item->parentOrganization->name;
            self::showOrganizationName($item->parentOrganization, $result, $type);
        }

        return $result;
    }

    public static function logErrorMessage($exception): void
    {
        $content = !empty($exception->getMessage()) ? $exception->getMessage() : $exception->getTraceAsString();
        $info = 'MESSAGE ERROR CONTENT: ' . $content . PHP_EOL;
        self::logInfoFile($info);
    }

    public static function logInfoSuccess($payload): void
    {
        $info = 'API CODE:' . Route::currentRouteName() . PHP_EOL;
        $info .= 'RESULT: SUCCESS' . PHP_EOL;
        $info .= 'RESPONSE STATUS CODE: 200';
        $info .= 'RESPONSE CONTENT: ' . json_encode($payload) . PHP_EOL;
        $info .= '--------------------API END--------------------' . PHP_EOL;
        self::logInfoFile($info);
    }

    public static function logInfoError($errorCode, $response): void
    {
        $response = json_encode($response);
        $info = 'API CODE: ' . Route::currentRouteName() . PHP_EOL;
        $info .= 'RESULT: ERROR' . PHP_EOL;
        $info .= 'RESPONSE STATUS CODE: ' . $errorCode . PHP_EOL;
        $info .= 'RESPONSE CONTENT: ' . $response . PHP_EOL;
        $info .= '--------------------API END--------------------' . PHP_EOL;
        self::logInfoFile($info);
    }

    public static function sortDataCustom($data, $sortBy, $sortColumn)
    {
        if (strtolower($sortBy) == 'desc') {
            return collect($data)->sortByDesc($sortColumn)->values()->all();
        } else {
            return collect($data)->sortBy($sortColumn)->values()->all();
        }
    }

    public static function convertDateJapan($day): string
    {
        $days = ["日","月","火","水","木","金","土"];
        $day = date('w',strtotime($day));
        return date('d/m/Y', strtotime($day))." ($days[$day])";
    }

    /**
     * @param $data
     * @return array
     */
    public static function paginate($data): array
    {
        $items = $data->items();
        if (!is_array($items)) {
            $items = $items->toArray();
        }

        return [
            'list' => array_values($items),
            'paginate' => [
                'current_page' => $data->currentPage(),
                'from' => $data->firstItem(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'to' => $data->lastItem(),
                'total' => $data->total(),
            ]
        ];
    }

    /**
     * @param $startTime
     * @param $endTime
     * @return int
     * @throws \Exception
     */
    public static function diffDate($startTime, $endTime): int
    {
        $datetime1 = new \DateTime($startTime);
        $datetime2 = new \DateTime($endTime);
        $interval = $datetime1->diff($datetime2);
        return (int)$interval->format('%a');
    }

    /**
     * @param $startTime
     * @param $endTime
     * @return float
     */
    public static function diffDateToHours($startTime, $endTime): float
    {
        $startTime = strtotime($startTime);
        $endTime = strtotime($endTime);

        return round(($endTime - $startTime) / 3600);
    }

    /**
     * @param $results
     * @param $pageSize
     * @return array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public static function paginateCustom($results, $pageSize): array
    {
        $page = Paginator::resolveCurrentPage('page');

        $total = $results->count();

        $results = self::paginator($results->forPage($page, $pageSize), $total, $pageSize, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);
        return self::paginate($results);
    }

    /**
     * @param $items
     * @param $total
     * @param $perPage
     * @param $currentPage
     * @param $options
     * @return \Closure|mixed|object|null
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected static function paginator($items, $total, $perPage, $currentPage, $options)
    {
        return Container::getInstance()->makeWith(
            LengthAwarePaginator::class,
            compact(
                'items',
                'total',
                'perPage',
                'currentPage',
                'options'
            )
        );
    }
}
