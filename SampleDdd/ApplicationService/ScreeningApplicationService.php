<?php


namespace SampleDdd\ApplicationService;

use App\Interview;
use App\Screening;
use Illuminate\Support\Facades\DB;
use SampleDdd\Domain\ScreeningStatus;
use SampleDdd\Domain\ScreeningStepResult;

class ScreeningApplicationService
{
    /**
     * 面談から新規候補者を登録する
     *
     * @param string $applicantEmailAddress
     */
    public function startFromPreInterview(string $applicantEmailAddress): void
    {
        if ($this->isEmpty($applicantEmailAddress)
            || $this->isInvalidFormatEmailAddress($applicantEmailAddress)) {
            throw new \InvalidArgumentException("メールアドレスが正しくありません");
        }

        DB::transaction(function () use ($applicantEmailAddress) {
            // デフォルトコンストラクタでインスタンス作成
            /** @var Screening $screening */
            $screeningDao = new Screening();

            // 面談からの場合はステータス「未応募」で登録
            $screeningDao->status = ScreeningStatus::NotApplied();
            // 未応募なので応募日はnull
            $screeningDao->apply_date = null;
            // メールアドレスは引数のものを登録
            $screeningDao->applicant_email_address = $applicantEmailAddress;

            $screeningDao->save();
        });
    }

    /**
     * 新規応募者を登録する
     *
     * @param string $applicantEmailAddress
     * @throws \Exception
     */
    public function apply(string $applicantEmailAddress): void
    {
        if ($this->isEmpty($applicantEmailAddress)
            || $this->isInvalidFormatEmailAddress($applicantEmailAddress)) {
            throw new \InvalidArgumentException("メールアドレスが正しくありません");
        }

        DB::transaction(function () use ($applicantEmailAddress) {
            /** @var Screening $screening */
            $screeningDao = new Screening();

            // 面接からの場合はステータス「面接」で登録
            $screeningDao->status = ScreeningStatus::Interview();
            // 応募日は操作日付を使用
            $screeningDao->apply_date = new \DateTime();

            $screeningDao->applicant_email_address = $applicantEmailAddress;

            $screeningDao->save();
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
            // 保存されている採用選考オブジェクトを取得
            /** @var Screening $screening */
            $screeningDao = Screening::find($screeningId);

            // 面接設定をしてよいステータスかをチェック
            if ($screeningDao->status != ScreeningStatus::Interview()) {
                throw new \InvalidArgumentException("不正な操作です");
            }

            // 保存されている面接オブジェクトの一覧を取得
            $interviews = Interview::where('screening_id', $screeningId)->get();

            $interview = new Interview();
            $interview->screening_id = $screeningId;
            // 面接次数は保存されているインタビューの数+1とする
            $interview->interview_number = count($interviews) + 1;
            $interview->screening_date = $interviewDate;

            $interview->save();
        });
    }

    // 面談から面接に進む処理は省略


    /**
     * 文字列の空白チェック用メソッド
     *
     * @param string $value
     * @return bool
     */
    private function isEmpty(string $value): bool
    {
        return strlen($value) === 0;
    }

    /**
     * メールアドレスのバリデーション用メソッド
     *
     * @param string $email
     * @return bool
     */
    private function isInvalidFormatEmailAddress(string $email): bool
    {
        if ($email == null) {
            return true;
        }

        return false;
//        // CONST_EMAIL_REGEXは適切な正規表現が記述されているとする
//        String emailRegex = CONST_EMAIL_REGEX;
//        return !Pattern . compile(emailRegex) . matcher(email) . matches();
    }

}
