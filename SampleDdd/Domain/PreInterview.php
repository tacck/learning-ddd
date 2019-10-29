<?php


namespace SampleDdd\Domain;


class PreInterview
{
    /** @var integer 面談担当者ID */
    private $interviewerId;

    /** @var \DateTime 面談日 */
    private $preInterviewDate;

    /**
     * @return int
     */
    public function getInterviewerId(): int
    {
        return $this->interviewerId;
    }

    /**
     * @param int $interviewerId
     */
    public function setInterviewerId(int $interviewerId): void
    {
        $this->interviewerId = $interviewerId;
    }

    /**
     * @return \DateTime
     */
    public function getPreInterviewDate(): \DateTime
    {
        return $this->preInterviewDate;
    }

    /**
     * @param \DateTime $preInterviewDate
     */
    public function setPreInterviewDate(\DateTime $preInterviewDate): void
    {
        $this->preInterviewDate = $preInterviewDate;
    }
}
