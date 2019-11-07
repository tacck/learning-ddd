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

    public function testStatusNotExists()
    {
        $this->expectException(\InvalidArgumentException::class);

        /** @var ScreeningStatus $status */
        $status = ScreeningStatus::NotExists();
    }

    public function testNextStepNotApplied()
    {
        /** @var ScreeningStatus $status */
        $status = ScreeningStatus::NotApplied();
        $nextStatus = $status->nextStep();
        $this->assertSame('DocumentScreening', $nextStatus->getValue());
    }

    public function testNextStepDocumentScreening()
    {
        /** @var ScreeningStatus $status */
        $status = ScreeningStatus::DocumentScreening();
        $nextStatus = $status->nextStep();
        $this->assertSame('Interview', $nextStatus->getValue());
    }

    public function testNextStepInterview()
    {
        /** @var ScreeningStatus $status */
        $status = ScreeningStatus::Interview();
        $nextStatus = $status->nextStep();
        $this->assertSame('Offered', $nextStatus->getValue());
    }

    public function testNextStepOffered()
    {
        /** @var ScreeningStatus $status */
        $status = ScreeningStatus::Offered();
        $nextStatus = $status->nextStep();
        $this->assertSame('Entered', $nextStatus->getValue());
    }

    public function testNextStepIllegalStatus()
    {
        $this->expectException(\InvalidArgumentException::class);

        /** @var ScreeningStatus $status */
        $status = ScreeningStatus::DocumentScreeningRejected();
        $nextStatus = $status->nextStep();
    }

    public function testPreviousStepDocumentScreeningRejected()
    {
        /** @var ScreeningStatus $status */
        $status = ScreeningStatus::DocumentScreeningRejected();
        $previousStep = $status->previousStep();
        $this->assertSame('DocumentScreening', $previousStep->getValue());
    }

    public function testPreviousStepDocumentScreeningDeclined()
    {
        /** @var ScreeningStatus $status */
        $status = ScreeningStatus::DocumentScreeningDeclined();
        $previousStep = $status->previousStep();
        $this->assertSame('DocumentScreening', $previousStep->getValue());
    }

    public function testPreviousStepInterview()
    {
        /** @var ScreeningStatus $status */
        $status = ScreeningStatus::Interview();
        $previousStep = $status->previousStep();
        $this->assertSame('DocumentScreening', $previousStep->getValue());
    }

    public function testPreviousStepInterviewRejected()
    {
        /** @var ScreeningStatus $status */
        $status = ScreeningStatus::InterviewRejected();
        $previousStep = $status->previousStep();
        $this->assertSame('Interview', $previousStep->getValue());
    }

    public function testPreviousStepInterviewDeclined()
    {
        /** @var ScreeningStatus $status */
        $status = ScreeningStatus::InterviewDeclined();
        $previousStep = $status->previousStep();
        $this->assertSame('Interview', $previousStep->getValue());
    }

    public function testPreviousStepOffered()
    {
        /** @var ScreeningStatus $status */
        $status = ScreeningStatus::Offered();
        $previousStep = $status->previousStep();
        $this->assertSame('Interview', $previousStep->getValue());
    }

    public function testPreviousStepOfferDeclined()
    {
        /** @var ScreeningStatus $status */
        $status = ScreeningStatus::OfferDeclined();
        $previousStep = $status->previousStep();
        $this->assertSame('Offered', $previousStep->getValue());
    }

    public function testPreviousStepEntered()
    {
        /** @var ScreeningStatus $status */
        $status = ScreeningStatus::Entered();
        $previousStep = $status->previousStep();
        $this->assertSame('Offered', $previousStep->getValue());
    }

    public function testPreviousStepIllegalStatus()
    {
        $this->expectException(\InvalidArgumentException::class);

        /** @var ScreeningStatus $status */
        $status = ScreeningStatus::NotApplied();
        $previousStep = $status->previousStep();
    }
}
