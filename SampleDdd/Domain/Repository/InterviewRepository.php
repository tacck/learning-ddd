<?php

namespace SampleDdd\Domain\Repository;

use SampleDdd\Domain\Interview;

interface InterviewRepository
{
    public function findById(int $interviewId): Interview;
    public function findByScreeningId(int $screeningId): array;
    public function insert(Interview $interview);
    public function update(Interview $interview);
}
