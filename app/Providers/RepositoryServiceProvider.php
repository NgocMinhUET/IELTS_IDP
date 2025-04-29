<?php

namespace App\Providers;

use App\Repositories\BlankImageAnswer\BlankImageAnswerInterface;
use App\Repositories\BlankImageAnswer\BlankImageAnswerRepository;
use App\Repositories\BlankImageQuestion\BlankImageQuestionInterface;
use App\Repositories\BlankImageQuestion\BlankImageQuestionRepository;
use App\Repositories\ChoiceOption\ChoiceOptionInterface;
use App\Repositories\ChoiceOption\ChoiceOptionRepository;
use App\Repositories\ChoiceQuestion\ChoiceQuestionInterface;
use App\Repositories\ChoiceQuestion\ChoiceQuestionRepository;
use App\Repositories\ChoiceSubQuestion\ChoiceSubQuestionInterface;
use App\Repositories\ChoiceSubQuestion\ChoiceSubQuestionRepository;
use App\Repositories\Exam\ExamInterface;
use App\Repositories\Exam\ExamRepository;
use App\Repositories\LBlankContentAnswer\LBlankContentAnswerInterface;
use App\Repositories\LBlankContentAnswer\LBlankContentAnswerRepository;
use App\Repositories\LBlankContentQuestion\LBlankContentQuestionInterface;
use App\Repositories\LBlankContentQuestion\LBlankContentQuestionRepository;
use App\Repositories\Paragraph\ParagraphInterface;
use App\Repositories\Paragraph\ParagraphRepository;
use App\Repositories\Part\PartInterface;
use App\Repositories\Part\PartRepository;
use App\Repositories\QuestionOrder\QuestionOrderInterface;
use App\Repositories\QuestionOrder\QuestionOrderRepository;
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
            LBlankContentQuestionInterface::class => LBlankContentQuestionRepository::class,
            LBlankContentAnswerInterface::class => LBlankContentAnswerRepository::class,
            BlankImageQuestionInterface::class => BlankImageQuestionRepository::class,
            BlankImageAnswerInterface::class => BlankImageAnswerRepository::class,
            QuestionOrderInterface::class => QuestionOrderRepository::class,
            ParagraphInterface::class => ParagraphRepository::class,
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
