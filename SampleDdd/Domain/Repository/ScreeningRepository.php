<?php

namespace SampleDdd\Domain\Repository;

use SampleDdd\Domain\Screening;
use SampleDdd\Domain\ScreeningId;

interface ScreeningRepository
{
    public function findById(ScreeningId $screeningId): Screening;
    public function insert(Screening $screening);
    public function update(Screening $screening);
}
