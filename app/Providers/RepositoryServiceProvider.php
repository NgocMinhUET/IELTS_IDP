<?php

namespace App\Providers;

use App\Repositories\ChoiceOption\ChoiceOptionInterface;
use App\Repositories\ChoiceOption\ChoiceOptionRepository;
use App\Repositories\ChoiceQuestion\ChoiceQuestionInterface;
use App\Repositories\ChoiceQuestion\ChoiceQuestionRepository;
use App\Repositories\ChoiceSubQuestion\ChoiceSubQuestionInterface;
use App\Repositories\ChoiceSubQuestion\ChoiceSubQuestionRepository;
use App\Repositories\Exam\ExamInterface;
use App\Repositories\Exam\ExamRepository;
use App\Repositories\Part\PartInterface;
use App\Repositories\Part\PartRepository;
use App\Repositories\Skill\SkillInterface;
use App\Repositories\Skill\SkillRepository;
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
        $repositories = [
            PasswordResetInterface::class => PasswordResetRepository::class,
            OtpInterface::class => OtpRepository::class,
            UserInterface::class => UserRepository::class,
            ExamInterface::class => ExamRepository::class,
            SkillInterface::class => SkillRepository::class,
            PartInterface::class => PartRepository::class,
            ChoiceQuestionInterface::class => ChoiceQuestionRepository::class,
            ChoiceSubQuestionInterface::class => ChoiceSubQuestionRepository::class,
            ChoiceOptionInterface::class => ChoiceOptionRepository::class,
        ];

        foreach ($repositories as $interface => $repository) {
            $this->app->bind($interface, $repository);
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
