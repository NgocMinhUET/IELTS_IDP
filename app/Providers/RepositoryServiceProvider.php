<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\PasswordReset\PasswordResetRepository;
use App\Repositories\PasswordReset\PasswordResetInterface;

use App\Repositories\Otp\OtpRepository;
use App\Repositories\Otp\OtpInterface;

use App\Repositories\User\UserRepository;
use App\Repositories\User\UserInterface;

use App\Repositories\TeamMatchingSetting\TeamMatchingSettingRepository;
use App\Repositories\TeamMatchingSetting\TeamMatchingSettingInterface;

use App\Repositories\TeamSchedule\TeamScheduleRepository;
use App\Repositories\TeamSchedule\TeamScheduleInterface;

use App\Repositories\TeamStadium\TeamStadiumRepository;
use App\Repositories\TeamStadium\TeamStadiumInterface;

use App\Repositories\Conversation\ConversationRepository;
use App\Repositories\Conversation\ConversationInterface;

use App\Repositories\Message\MessageRepository;
use App\Repositories\Message\MessageInterface;

use App\Repositories\Team\TeamInterface;
use App\Repositories\Team\TeamRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
