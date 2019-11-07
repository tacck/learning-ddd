<?php


namespace App;

use SampleDdd\Domain\Interview;
use SampleDdd\Domain\Repository\InterviewRepository;
use SampleDdd\Domain\ScreeningId;

class InterviewEloquentRepository implements InterviewRepository
{

    /**
     * @param int $interviewId
     * @return Interview
     */
    public function findById(int $interviewId): Interview
    {
        $interviewModel = \App\Interview::find($interviewId);
        $interview = Interview::reconstruct($interviewModel->interview_id, $interviewModel->screening_id, $interviewModel->interview_number, $interviewModel->interview_date);
        return $interview;
    }

    /**
     * @param ScreeningId $screeningId
     * @return array
     */
    public function findByScreeningId(ScreeningId $screeningId): array
    {
        $interviewModels = \App\Interview::where('screening_id', $screeningId->getValue())->get();
        $interviews = [];

        foreach ($interviewModels as $interviewModel) {
            $interviews[] = Interview::reconstruct($interviewModel->interview_id, $interviewModel->screening_id, $interviewModel->interview_number, $interviewModel->interview_date);
        }

        return $interviews;
    }

    public function insert(Interview $interview)
    {
        $interviewModel = new \App\Interview();
        $interviewModel->screening_id = $interview->getScreeningId()->getValue();
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
        $interviewModel->screening_id = $interview->getScreeningId()->getValue();
        $interviewModel->screening_date = $interview->getScreeningDate();
        $interviewModel->interview_number = $interview->getInterviewNumber();
        $interviewModel->screening_step_result = $interview->getScreeningStepResult();
        $interviewModel->recruiter_id = $interview->getRecruiterId();
        $interviewModel->save();
    }
}
