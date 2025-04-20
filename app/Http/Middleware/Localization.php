<?php

namespace App\Http\Middleware;

use Closure;

class Localization
{
    public function handle($request, Closure $next)
    {
        $lang = ($request->hasHeader('X-localization')) ? $request->header('X-localization') : 'ja';

        app()->setLocale($lang);

        return $next($request);
    }
}
