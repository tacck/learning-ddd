<?php


namespace App;

use SampleDdd\Domain\Interviews;
use SampleDdd\Domain\Repository\InterviewRepository;
use SampleDdd\Domain\Repository\ScreeningRepository;
use SampleDdd\Domain\Screening;
use SampleDdd\Domain\ScreeningId;
use SampleDdd\Domain\ScreeningStatus;

class ScreeningEloquentRepository implements ScreeningRepository
{
    /** @var InterviewRepository */
    private $interviewRepository;

    public function __construct()
    {
        // InterviewRepository を DI
        $this->interviewRepository = resolve('SampleDdd\Domain\Repository\InterviewRepository');
    }

    /**
     * ID(PK)で取得
     *
     * @param ScreeningId $screeningId
     * @return Screening
     * @throws \Exception
     */
    public function findById(ScreeningId $screeningId): Screening
    {
        $screeningModel = \App\Screening::find($screeningId->getValue());

        $interviews = $this->interviewRepository->findByScreeningId($screeningId);

        $screening = Screening::reconstruct(
            $screeningModel->id,
            $screeningModel->applicant_email_address,
            new ScreeningStatus($screeningModel->status),
            new \DateTime($screeningModel->apply_date),
            Interviews::reconstruct($interviews)
        );

        return $screening;
    }

    /**
     * Insert
     *
     * @param Screening $screening
     */
    public function insert(Screening $screening)
    {
        $screeningModel = new \App\Screening();
        $screeningModel->apply_date = $screening->getApplyDate();
        $screeningModel->status = $screening->getStatus();
        $screeningModel->applicant_email_address = $screening->getApplicantEmailAddress();
        $screeningModel->save();
    }

    /**
     * Update
     *
     * @param Screening $screening
     */
    public function update(Screening $screening)
    {
        /** @var \App\Screening $screeningModel */
        $screeningModel = \App\Screening::find($screening->getId()->getValue());
        $screeningModel->apply_date = $screening->getApplyDate();
        $screeningModel->status = $screening->getStatus();
        $screeningModel->applicant_email_address = $screening->getApplicantEmailAddress();
        $screeningModel->save();

        $this->saveInterviews($screening->getId(), $screening->getInterviews());
    }

    /**
     * 選考に紐づいた面談・面接を保存
     *
     * @param ScreeningId $screeningId
     * @param Interviews $interviews
     */
    private function saveInterviews(ScreeningId $screeningId, Interviews $interviews): void
    {
        // 面接・面談は減ることは無いので、増えるロジックのみ考える。

        $saveInterviews = [];

        $interviewsInDb = \App\Interview::where('screening_id', $screeningId->getValue())->get();
        $interviewsInEntity = $interviews->getValue();
        if ($interviewsInDb->count() != count($interviewsInEntity)) {
            // 全て新規追加対象とする
            if ($interviewsInDb->count() === 0 && count($interviewsInEntity) > 0) {
                $saveInterviews = $interviewsInEntity;
            }

            // DBとの差分を確認して追加対象を絞る
            foreach ($interviewsInDb as $interviewInDb) {
                foreach ($interviewsInEntity as $interview) {
                    if ($interviewInDb->id === $interview->getInterviewId()) {
                        $saveInterviews[] = $interview;
                    }
                }
            }
        } else {
            // 面接回数が変わらないので処理なし。
            return;
        }

        foreach ($saveInterviews as $saveInterview) {
            $this->interviewRepository->insert($saveInterview);
        }
    }
}
