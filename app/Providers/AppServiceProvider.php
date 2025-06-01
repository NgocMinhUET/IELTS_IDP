<?php

namespace App\Providers;

use App\Http\Controllers\CMS\CMSController;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Schema\Blueprint;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('S3Helper', function ($app) {
            return new \App\Helpers\S3Helper();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blueprint::macro('commonFields', function () {
            /** @var Blueprint $this */
            $this->timestamps();
            $this->softDeletes();
        });

        // append breadcrumbs to view
        View::composer('*', function ($view) {
            $routeController = optional(request()->route())?->controller;
            if ($routeController instanceof CMSController && property_exists($routeController, 'breadcrumbs')) {
                $view->with('breadcrumbs', $routeController->breadcrumbs);
            }
        });

        if (config('app.force_scheme')) {
            URL::forceScheme(config('app.force_scheme'));
        }
    }
}
