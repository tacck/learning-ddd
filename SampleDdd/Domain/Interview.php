<?php

namespace SampleDdd\Domain;

class Interview
{
    /** @var integer 面接ID */
    private $interviewId;

    /** @var ScreeningId 採用選考ID */
    private $screeningId;

    /** @var \DateTime 選考日 */
    private $screeningDate;

    /** @var integer 面接次数 */
    private $interviewNumber;

    /** @var ScreeningStepResult 面接結果 */
    private $screeningStepResult;

    /** @var integer 採用担当者ID */
    private $recruiterId;

    /**
     * Interview constructor.
     */
    private function __construct()
    {
    }

    /**
     * オブジェクト作成用ファクトリーメソッド
     *
     * @param ScreeningId $screeningId
     * @param \DateTime $interviewDate
     * @param int $interviewNumber
     * @return Interview
     */
    public static function create(ScreeningId $screeningId, \DateTime $interviewDate, int $interviewNumber): Interview
    {
        $object = new Interview();

        $object->interviewId = null;
        $object->screeningId = $screeningId;
        $object->screeningDate = $interviewDate;
        $object->interviewNumber = $interviewNumber;
        $object->screeningStepResult = ScreeningStepResult::NotEvaluated(); // TODO: 選考過程仮登録
        $object->recruiterId = 1; // TODO: 担当者仮登録

        return $object;
    }

    /**
     * Repository からの再構成用メソッド
     *
     * @param int $interviewId
     * @param int $screeningId
     * @param \DateTime $interviewDate
     * @param int $interviewNumber
     * @return self
     */
    public static function reconstruct(int $interviewId, int $screeningId, \DateTime $interviewDate, int $interviewNumber): self
    {
        $object = self::create(ScreeningId::reconstruct($screeningId), $interviewDate, $interviewNumber);
        $object->interviewId = $interviewId;
        return $object;
    }

    /**
     * @return int
     */
    public function getInterviewId(): int
    {
        return $this->interviewId;
    }

    /**
     * @return ScreeningId
     */
    public function getScreeningId(): ScreeningId
    {
        return $this->screeningId;
    }

    /**
     * @return \DateTime
     */
    public function getScreeningDate(): ?\DateTime
    {
        return $this->screeningDate;
    }

    /**
     * @return int
     */
    public function getInterviewNumber(): int
    {
        return $this->interviewNumber;
    }

    /**
     * @return ScreeningStepResult
     */
    public function getScreeningStepResult(): ScreeningStepResult
    {
        return $this->screeningStepResult;
    }

    /**
     * @return int
     */
    public function getRecruiterId(): int
    {
        return $this->recruiterId;
    }
}
