<?php


namespace SampleDdd\Domain;


class Interviews
{
    /** @var array $interviews */
    private $interviews = [];

    public function __construct()
    {
        $this->interviews = [];
    }

    public function addNextInterview(int $interviewId, \DateTime $interviewDate)
    {
        $this->interviews[] = Interview::create($interviewId, $interviewDate, $this->getNextInterviewNumber());
    }

    public function getNextInterviewNumber(): int
    {
        return count($this->interviews) + 1;
    }

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
