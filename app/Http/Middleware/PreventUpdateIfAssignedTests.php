<?php

namespace App\Http\Middleware;

use App\Repositories\Exam\ExamInterface;
use App\Repositories\Part\PartInterface;
use App\Repositories\Skill\SkillInterface;
use Closure;
use Illuminate\Http\Request;

class PreventUpdateIfAssignedTests
{
    public function __construct(
        public ExamInterface $examRepository,
        public SkillInterface $skillRepository,
        public PartInterface $partRepository,
    )
    {
    }

    public function handle(Request $request, Closure $next)
    {
        $routeName = $request->route()->getName();

        if (empty($routeName)) {
            return $next($request);
        }

        $arrRouteName = explode('.', $routeName);
        $routeTarget = count($arrRouteName) > 1 ? $arrRouteName[1] : '';

        switch ($routeTarget) {
            case 'exams':
                $exam = $this->examRepository->find($request->id);
                break;
            case 'skills':
                $skill = $this->skillRepository->find($request->id);
                $exam = $skill->exam;
                break;
            case 'parts':
                $part = $this->partRepository->with('skill.exam')->find($request->id);
                $exam = $part->skill->exam;
                break;
            default:
                return $next($request);
        }

        if ($exam->tests_count) {
            abort(403);
        }

        return $next($request);
    }
}