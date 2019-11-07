<?php

namespace SampleDdd\Domain\Repository;

use SampleDdd\Domain\Interview;
use SampleDdd\Domain\ScreeningId;

interface InterviewRepository
{
    public function findById(int $interviewId): Interview;
    public function findByScreeningId(ScreeningId $screeningId): array;
    public function insert(Interview $interview);
    public function update(Interview $interview);
}
