<?php

namespace App\Services\API;

use App\Models\Skill;
use App\Repositories\BlankImageQuestion\BlankImageQuestionInterface;
use App\Repositories\ChoiceQuestion\ChoiceQuestionInterface;
use App\Repositories\LBlankContentQuestion\LBlankContentQuestionInterface;
use App\Repositories\Part\PartInterface;
use App\Repositories\Skill\SkillInterface;
use App\Repositories\WritingQuestion\WritingQuestionInterface;

class SkillService
{
    public function __construct(
        public SkillInterface $skillRepository,
        public PartInterface $partRepository,
        public WritingQuestionInterface $writingQuestionRepository,
        public ChoiceQuestionInterface $choiceQuestionRepository,
        public LBlankContentQuestionInterface $lBlankContentQuestionRepository,
        public BlankImageQuestionInterface $blankImageQuestionRepository,
    ) {}

    public function getSkill($id)
    {
        return $this->skillRepository->find($id);
    }

    public function getSkillByExam($examId)
    {
        return $this->skillRepository->findWhere([
            'exam_id' => $examId,
        ]);
    }

    public function getAllListenOrReadingSkillQuestionsAndAnswers(Skill $skill)
    {
        $parts = $this->getPartsOfSkill($skill->id);

        $questionAndAnswers = collect();
        foreach ($parts as $part) {
            $questionAndAnswers = $questionAndAnswers->merge($this->getAllListeningOrReadingQuestionsOfPart($part->id));
        }

        dd($questionAndAnswers);
    }

    public function getAllWritingSkillQuestionsAndAnswers(Skill $skill)
    {
        dd(123);
    }

    public function getPartsOfSkill($skillId)
    {
        return $this->partRepository->findByField('skill_id', $skillId);
    }

    public function getAllListeningOrReadingQuestionsOfPart($partId)
    {
        $choiceQuestions = $this->choiceQuestionRepository->with('choiceSubQuestions.choiceOptions')
            ->findWhere(['part_id' => $partId]);

        $fillInBlankQuestions = $this->lBlankContentQuestionRepository->with('answers')
            ->findWhere(['part_id' => $partId]);

        $fillInImageQuestions = $this->blankImageQuestionRepository->with('answers')
            ->findWhere(['part_id' => $partId]);

        dd($choiceQuestions->toArray());

        return $choiceQuestions->merge($fillInBlankQuestions->merge($fillInImageQuestions));
    }
}
