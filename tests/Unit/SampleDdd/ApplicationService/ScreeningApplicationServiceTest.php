<?php

namespace Tests\Unit\SampleDdd\ApplicationService;

use App\Interview;
use App\Screening;
use App\ScreeningEloquentRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use SampleDdd\ApplicationService\ScreeningApplicationService;
use SampleDdd\Domain\ScreeningId;
use SampleDdd\Domain\ScreeningStatus;
use SampleDdd\Domain\EmailAddress;
use Tests\TestCase;

class ScreeningApplicationServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @var ScreeningApplicationService */
    private $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ScreeningApplicationService(new ScreeningEloquentRepository());
    }

    /**
     * @throws \Exception
     */
    public function testApply_応募者登録_正常()
    {
        $expected = 'testing@example.com';
        $this->service->apply(EmailAddress::reconstruct($expected));

        $screening = Screening::where('applicant_email_address', $expected)->get();
        $this->assertSame(1, count($screening));
        $this->assertSame($expected, $screening[0]->applicant_email_address);
        $this->assertSame(ScreeningStatus::Interview()->getValue(), $screening[0]->status);
    }

    public function testApply_応募者登録後次のステップへ_正常()
    {
        $expected = 'testing@example.com';
        $this->service->apply(EmailAddress::reconstruct($expected));

        $screening = Screening::where('applicant_email_address', $expected)->get();
        $this->service->stepToNext(new ScreeningId($screening[0]->id));

        $screeningUpdated = Screening::where('applicant_email_address', $expected)->get();
        $this->assertSame(ScreeningStatus::Offered()->getValue(), $screeningUpdated[0]->status);
    }

    public function testApply_応募者登録_メールアドレス不正_空文字()
    {
        $this->expectException(\InvalidArgumentException::class);

        $expected = '';
        $this->service->apply(EmailAddress::reconstruct($expected));
    }

    public function testApply_応募者登録_メールアドレス不正_null()
    {
        $this->expectException(\TypeError::class);

        $expected = null;
        $this->service->apply(EmailAddress::reconstruct($expected));
    }

    public function testApply_面談から新規候補者を登録_正常()
    {
        $expected = 'testing@example.com';
        $this->service->startFromPreInterview(EmailAddress::reconstruct($expected));

        $screening = Screening::where('applicant_email_address', $expected)->get();
        $this->assertSame(1, count($screening));
        $this->assertSame($expected, $screening[0]->applicant_email_address);
        $this->assertSame(ScreeningStatus::NotApplied()->getValue(), $screening[0]->status);
    }

    public function testApply_面談から新規候補者を登録_メールアドレス不正_空文字()
    {
        $this->expectException(\InvalidArgumentException::class);

        $expected = '';
        $this->service->startFromPreInterview(EmailAddress::reconstruct($expected));
    }

    public function testApply_面談から新規候補者を登録_メールアドレス不正_null()
    {
        $this->expectException(\TypeError::class);

        $expected = null;
        $this->service->startFromPreInterview($expected);
    }

    public function testApply_次の面接を設定_正常()
    {
        // 応募で登録
        $expected = 'testing@example.com';
        $this->service->apply(EmailAddress::reconstruct($expected));
        // 登録した情報からID取得して面接の設定に進む
        $screening = Screening::where('applicant_email_address', $expected)->get();
        $screeningId = new ScreeningId($screening[0]->id);

        $interviewDate = new \DateTime();
        $this->service->addNextInterview($screeningId, $interviewDate);

        $interview = Interview::where('screening_id', $screeningId->getValue())->get();

        $this->assertSame(1, count($interview));
        $this->assertEquals(1, $interview[0]->interview_number);
        $this->assertSame($interviewDate->format('Y-m-d H:i:s'),  $interview[0]->screening_date);
    }

}
