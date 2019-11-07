<?php


namespace SampleDdd\Domain;


class Interviews
{
    /** @var array $interviews */
    private $interviews = [];

    /**
     * Interviews constructor.
     */
    public function __construct()
    {
        $this->interviews = [];
    }

    /**
     * @param ScreeningId $interviewId
     * @param \DateTime $interviewDate
     */
    public function addNextInterview(ScreeningId $interviewId, \DateTime $interviewDate)
    {
        $this->interviews[] = Interview::create($interviewId, $interviewDate, $this->getNextInterviewNumber());
    }

    /**
     * @return int
     */
    public function getNextInterviewNumber(): int
    {
        return count($this->interviews) + 1;
    }

    /**
     * @return array
     */
    public function getValue(): array
    {
        return $this->interviews;
    }

    /**
     * 再構成用メソッド
     *
     * @param array $interviews
     * @return $this
     */
    public static function reconstruct(array $interviews): self
    {
        // $interviews の中を厳密にチェックする必要あり。

        $object = new Interviews();
        $object->interviews = $interviews;
        return $object;
    }
}
