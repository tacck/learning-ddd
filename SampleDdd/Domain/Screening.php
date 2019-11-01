<?php

namespace SampleDdd\Domain;

class Screening
{
    /** @var integer 採用選考ID */
    private $id;

    /** @var \DateTime 応募日 */
    private $apply_date;

    /** @var ScreeningStatus 採用選考ステータス */
    private $status;

    /** @var string 応募者メールアドレス */
    private $applicant_email_address;

    /** @var array インタビュー回数 */
    private $interviews = [];

    /**
     * コンストラクタをプライベートにしてファクトリーメソッド経由での作成に強制
     */
    private function __construct()
    {
    }

    /**
     * オブジェクト作成用ファクトリーメソッド
     *
     * @param int $screeningId
     * @param string $applicant_email_address
     * @param ScreeningStatus $status
     * @param \DateTime|null $apply_date
     * @param array $interviews
     * @return Screening
     */
    public static function create(int $screeningId, string $applicant_email_address, ScreeningStatus $status, ?\DateTime $apply_date, array $interviews): Screening
    {
        $object = new Screening();
        $object->id = $screeningId;
        $object->applicant_email_address = $applicant_email_address;
        $object->status = $status;
        $object->apply_date = $apply_date;
        $object->interviews = $interviews;
        return $object;
    }

    /**
     * 面談から採用選考を登録する際のファクトリメソッド
     *
     * @param string $applicantEmailAddress
     * @return Screening
     * @throws \InvalidArgumentException
     */
    public static function startFromPreInterview(string $applicantEmailAddress): Screening
    {
        if (self::isEmpty($applicantEmailAddress)
            || self::isInvalidFormatEmailAddress($applicantEmailAddress)) {
            throw new \InvalidArgumentException("メールアドレスが正しくありません");
        }

        $object = new Screening();

        // メールアドレスは引数のものを登録
        $object->applicant_email_address = $applicantEmailAddress;
        // 面談からの場合はステータス「未応募」で登録
        $object->status = ScreeningStatus::NotApplied();
        // 未応募なので応募日はnull
        $object->apply_date = null;
        // インタビュー回数のArray作成
        $object->interviews = [];

        return $object;
    }

    /**
     * 面接から採用選考を登録する際のファクトリメソッド
     *
     * @param string $applicantEmailAddress
     * @return Screening
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public static function apply(string $applicantEmailAddress): Screening
    {
        if (self::isEmpty($applicantEmailAddress)
            || self::isInvalidFormatEmailAddress($applicantEmailAddress)) {
            throw new \InvalidArgumentException("メールアドレスが正しくありません");
        }

        $object = new Screening();

        // メールアドレスは引数のものを登録
        $object->applicant_email_address = $applicantEmailAddress;
        // 面接からの場合はステータス「面接選考中」で登録
        $object->status = ScreeningStatus::Interview();
        // 応募日は操作日付を使用
        $object->apply_date = new \DateTime();
        // インタビュー回数のArray作成
        $object->interviews = [];

        return $object;
    }

    /**
     * ミューテーションメソッド
     *
     * @param \DateTime $interviewDate
     */
    public function addNextInterview(\DateTime $interviewDate): void
    {
        // ① 選考ステータスが「選考中」以外のときには
        //    設定できない
        if ($this->status != ScreeningStatus::Interview()) {
            throw new \UnexpectedValueException("不正な操作です");
        }

        // ② 面接次数は1からインクリメントされる
        $nextInterviewNumber = count($this->interviews) + 1;
        $nextInterview = Interview::create(null, $this->getId(), $interviewDate, $nextInterviewNumber);

        $this->interviews[] = $nextInterview;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getApplyDate(): ?\DateTime
    {
        return $this->apply_date;
    }

    /**
     * @return ScreeningStatus
     */
    public function getStatus(): ScreeningStatus
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getApplicantEmailaddress(): string
    {
        return $this->applicant_email_address;
    }

    /**
     * @return array
     */
    public function getInterviews(): array
    {
        return $this->interviews;
    }

    /**
     * 文字列の空白チェック用メソッド
     *
     * @param string $value
     * @return bool
     */
    private static function isEmpty(string $value): bool
    {
        return strlen($value) === 0;
    }

    /**
     * メールアドレスのバリデーション用メソッド
     *
     * @param string $email
     * @return bool
     */
    private static function isInvalidFormatEmailAddress(string $email): bool
    {
        if ($email == null) {
            return true;
        }

        return false;
    }
}
