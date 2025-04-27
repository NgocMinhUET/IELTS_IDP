<?php

namespace App\Providers;

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
            if (property_exists($controller = optional(request()->route())->controller, 'breadcrumbs')) {
                $view->with('breadcrumbs', $controller->breadcrumbs);
            }
        });
    }
}
