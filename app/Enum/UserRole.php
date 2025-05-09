<?php

namespace App\Enum;

enum UserRole: string
{
    case ADMIN = 'admin';
    case TEACHER = 'teacher';
    case STUDENT = 'student';

    /**
     * @throws \Exception
     */
    public function redirectCMSRoute(): string
    {
        return match ($this) {
            self::ADMIN => route('admin.tests.index'),
            self::TEACHER => route('admin.exams.index'),
            self::STUDENT => throw new \Exception('To be implemented')
        };
    }
}