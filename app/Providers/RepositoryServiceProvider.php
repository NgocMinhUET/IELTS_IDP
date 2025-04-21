<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\PasswordReset\PasswordResetRepository;
use App\Repositories\PasswordReset\PasswordResetInterface;

use App\Repositories\Otp\OtpRepository;
use App\Repositories\Otp\OtpInterface;

use App\Repositories\User\UserRepository;
use App\Repositories\User\UserInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            PasswordResetInterface::class,
            PasswordResetRepository::class
        );

        $this->app->bind(
            OtpInterface::class,
            OtpRepository::class
        );

        $this->app->bind(
            UserInterface::class,
            UserRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
