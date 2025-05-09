<?php

namespace App\Providers;

use App\Enum\UserRole;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        Auth::shouldUse('admin');
        $this->registerBladeDirectives();
    }

    public function registerBladeDirectives(): void
    {
        $this->registerAdminDirective();
        $this->registerTeacherDirective();
    }

    public function registerAdminDirective(): void
    {
        Blade::if('admin', function () {
            $user = Auth::user();
            return $user && $user->role === UserRole::ADMIN;
        });
    }

    public function registerTeacherDirective(): void
    {
        Blade::if('teacher', function () {
            $user = Auth::user();
            return $user && $user->role === UserRole::TEACHER;
        });
    }
}
