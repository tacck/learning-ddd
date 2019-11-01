<?php

namespace SampleDdd\Domain\Repository;

use SampleDdd\Domain\Screening;

interface ScreeningRepository
{
    public function findById(int $screeningId): Screening;
    public function insert(Screening $screening);
    public function update(Screening $screening);
}
