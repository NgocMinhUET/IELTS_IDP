<?php

namespace App\Http\Middleware;

use App\Enum\UserRole;
use App\Models\Admin;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleTeacher
{
    public function handle(Request $request, Closure $next)
    {
        Auth::shouldUse('admin');
        $user = Auth::user();

        if (($user instanceof Admin) && ($user->role == UserRole::TEACHER)) {
            return $next($request);
        }

        return redirect()->route($user->role->redirectCMSRoute());
    }
}
