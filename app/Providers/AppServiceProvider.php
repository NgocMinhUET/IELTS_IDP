<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Schema\Blueprint;

use App\Models\BaseModel;

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
    }
}
