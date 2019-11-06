<?php


namespace SampleDdd\ApplicationService;

use Illuminate\Support\Facades\DB;
use SampleDdd\Domain\Repository\ScreeningRepository;
use SampleDdd\Domain\Screening;
use SampleDdd\Domain\EmailAddress;

class ScreeningApplicationService
{
    /** @var ScreeningRepository */
    private $screeningRepository;

    public function __construct(ScreeningRepository $screeningRepository)
    {
        $this->screeningRepository = $screeningRepository;

        // こちらの記法でDIできるが、Laravelへの依存度が高くなる。
        // $this->screeningRepository = resolve('SampleDdd\Domain\Repository\ScreeningRepository');
    }

    /**
     * 面談から新規候補者を登録する
     *
     * @param EmailAddress $applicantEmailAddress
     */
    public function startFromPreInterview(EmailAddress $applicantEmailAddress): void
    {
        DB::transaction(function () use ($applicantEmailAddress) {
            /** @var \SampleDdd\Domain\Screening $screening */
            $screening = Screening::startFromPreInterview($applicantEmailAddress);

            $this->screeningRepository->insert($screening);
        });
    }

    /**
     * 新規応募者を登録する
     *
     * @param EmailAddress $applicantEmailAddress
     */
    public function apply(EmailAddress $applicantEmailAddress): void
    {
        DB::transaction(function () use ($applicantEmailAddress) {
            /** @var \SampleDdd\Domain\Screening $screening */
            $screening = Screening::apply($applicantEmailAddress);

            $this->screeningRepository->insert($screening);
        });
    }

    /**
     * 次の面接を設定する
     *
     * @param string $screeningId
     * @param \DateTime $interviewDate
     */
    public function addNextInterview(string $screeningId, \DateTime $interviewDate): void
    {
        DB::transaction(function () use ($screeningId, $interviewDate) {
            // 永続化されたオブジェクトを「集約単位で」取得
            /** @var \SampleDdd\Domain\Screening $screening */
            $screening = $this->screeningRepository->findById($screeningId);

            $screening->addNextInterview($interviewDate);
            $this->screeningRepository->update($screening);
        });
    }

    // 面談から面接に進む処理は省略
}
