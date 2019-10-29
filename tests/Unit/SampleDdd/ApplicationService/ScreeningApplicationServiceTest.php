<?php

namespace Tests\Unit\SampleDdd\ApplicationService;

use App\Interview;
use App\Screening;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use SampleDdd\ApplicationService\ScreeningApplicationService;
use SampleDdd\Domain\ScreeningStatus;
use Tests\TestCase;

class ScreeningApplicationServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @throws \Exception
     */
    public function testApply_応募者登録_正常()
    {
        $service = new ScreeningApplicationService();

        $expected = 'testing@example.com';
        $service->apply($expected);

        $screening = Screening::where('applicant_email_address', $expected)->get();
        $this->assertSame(1, count($screening));
        $this->assertSame($expected, $screening[0]->applicant_email_address);
        $this->assertSame(ScreeningStatus::Interview()->getValue(), $screening[0]->status);
    }

    public function testApply_応募者登録_メールアドレス不正_空文字()
    {
        $this->expectException(\InvalidArgumentException::class);

        $service = new ScreeningApplicationService();

        $expected = '';
        $service->apply($expected);
    }

    public function testApply_応募者登録_メールアドレス不正_null()
    {
        $this->expectException(\TypeError::class);

        $service = new ScreeningApplicationService();

        $expected = null;
        $service->apply($expected);
    }

    public function testApply_面談から新規候補者を登録_正常()
    {
        $service = new ScreeningApplicationService();

        $expected = 'testing@example.com';
        $service->startFromPreInterview($expected);

        $screening = Screening::where('applicant_email_address', $expected)->get();
        $this->assertSame(1, count($screening));
        $this->assertSame($expected, $screening[0]->applicant_email_address);
        $this->assertSame(ScreeningStatus::NotApplied()->getValue(), $screening[0]->status);
    }

    public function testApply_面談から新規候補者を登録_メールアドレス不正_空文字()
    {
        $this->expectException(\InvalidArgumentException::class);

        $service = new ScreeningApplicationService();

        $expected = '';
        $service->startFromPreInterview($expected);
    }

    public function testApply_面談から新規候補者を登録_メールアドレス不正_null()
    {
        $this->expectException(\TypeError::class);

        $service = new ScreeningApplicationService();

        $expected = null;
        $service->startFromPreInterview($expected);
    }

    public function testApply_次の面接を設定_正常()
    {
        $service = new ScreeningApplicationService();

        // 応募で登録
        $expected = 'testing@example.com';
        $service->apply($expected);
        // 登録した情報からID取得して面接の設定に進む
        $screening = Screening::where('applicant_email_address', $expected)->get();
        $screeningId = $screening[0]->id;

        $interviewDate = new \DateTime();
        $service->addNextInterview($screeningId, $interviewDate);

        $interview = Interview::where('screening_id', $screeningId)->get();

        $this->assertSame(1, count($interview));
        $this->assertEquals(1, $interview[0]->interview_number);
        $this->assertSame($interviewDate->format('Y-m-d H:i:s'),  $interview[0]->screening_date);
    }

}
