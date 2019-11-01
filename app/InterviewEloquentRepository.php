<?php


namespace App;

use SampleDdd\Domain\Interview;
use SampleDdd\Domain\Repository\InterviewRepository;

class InterviewEloquentRepository implements InterviewRepository
{

    public function findById(int $interviewId): Interview
    {
        $interviewModel = \App\Interview::find($interviewId);
        $interview = Interview::create($interviewModel->interview_id, $interviewModel->screening_id, $interviewModel->interview_number, $interviewModel->interview_date);
        return $interview;
    }

    public function findByScreeningId(int $screeningId): array
    {
        $interviewModels = \App\Interview::where('screening_id', $screeningId)->get();
        $interviews = [];

        foreach ($interviewModels as $interviewModel) {
            $interviews[] =Interview::create($interviewModel->interview_id, $interviewModel->screening_id, $interviewModel->interview_number, $interviewModel->interview_date);
        }

        return $interviews;
    }

    public function insert(Interview $interview)
    {
        $interviewModel = new \App\Interview();
        $interviewModel->screening_id = $interview->getScreeningId();
        $interviewModel->screening_date = $interview->getScreeningDate();
        $interviewModel->interview_number = $interview->getInterviewNumber();
        $interviewModel->screening_step_result = $interview->getScreeningStepResult();
        $interviewModel->recruiter_id = $interview->getRecruiterId();
        $interviewModel->save();
    }

    public function update(Interview $interview)
    {
        /** @var \App\Interview $interviewModel */
        $interviewModel = \App\Interview::find($interview->getInterviewId());
        $interviewModel->screening_id = $interview->getScreeningId();
        $interviewModel->screening_date = $interview->getScreeningDate();
        $interviewModel->interview_number = $interview->getInterviewNumber();
        $interviewModel->screening_step_result = $interview->getScreeningStepResult();
        $interviewModel->recruiter_id = $interview->getRecruiterId();
        $interviewModel->save();
    }
}
