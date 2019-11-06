<?php

namespace Tests\Unit\SampleDdd\Domain;

use SampleDdd\Domain\ScreeningStatus;
use PHPUnit\Framework\TestCase;

class ScreeningStatusTest extends TestCase
{
    public function testStatusNotApplied()
    {
        /** @var ScreeningStatus $status */
        $status = ScreeningStatus::NotApplied();
        $this->assertSame('NotApplied', $status->getValue());
        $this->assertFalse($status->canAddInterview());
    }

    public function testStatusInterview()
    {
        /** @var ScreeningStatus $status */
        $status = ScreeningStatus::Interview();
        $this->assertSame('Interview', $status->getValue());
        $this->assertTrue($status->canAddInterview());
    }
}
