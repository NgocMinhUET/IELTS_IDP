<?php

namespace App\Http\Middleware;

use App\Common\ResponseApi;
use Closure;

class AccountIsActive
{
    public function handle($request, Closure $next)
    {
        if (auth()->check() && !auth()->user()?->is_active) {
            return ResponseApi::unauthorized(__('auth.account_not_active'));
        }

        return $next($request);
    }
}
