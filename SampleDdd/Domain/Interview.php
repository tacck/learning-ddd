<?php

namespace SampleDdd\Domain;

class Interview
{
    /** @var integer 面接ID */
    private $interviewId;

    /** @var integer 採用選考ID */
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
     * コンストラクタをプライベートにしてファクトリーメソッド経由での作成に強制
     */
    private function __construct()
    {
    }

    /**
     * オブジェクト作成用ファクトリーメソッド
     *
     * @param int|null $interviewId
     * @param int $screeningId
     * @param \DateTime $interviewDate
     * @param int $interviewNumber
     * @return Interview
     */
    public static function create(?int $interviewId, int $screeningId, \DateTime $interviewDate, int $interviewNumber): Interview
    {
        $object = new Interview();

        $object->interviewId = $interviewId;
        $object->screeningId = $screeningId;
        $object->screeningDate = $interviewDate;
        $object->interviewNumber = $interviewNumber;
        $object->screeningStepResult = ScreeningStepResult::NotEvaluated(); // TODO: 選考過程仮登録
        $object->recruiterId = 1; // TODO: 担当者仮登録

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
     * @return int
     */
    public function getScreeningId(): int
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
