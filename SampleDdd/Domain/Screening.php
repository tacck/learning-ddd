<?php

namespace SampleDdd\Domain;

use Exception;

class Screening
{
    /** @var ScreeningId 採用選考ID */
    private $id;

    /** @var \DateTime 応募日 */
    private $applyDate;

    /** @var ScreeningStatus 採用選考ステータス */
    private $status;

    /** @var EmailAddress 応募者メールアドレス */
    private $applicantEmailAddress;

    /** @var Interviews インタビュー回数 */
    private $interviews;

    /**
     * コンストラクタをプライベートにしてファクトリーメソッド経由での作成に強制
     */
    private function __construct()
    {
    }

    /**
     * 面談から採用選考を登録する際のファクトリメソッド
     *
     * @param EmailAddress $applicantEmailAddress
     * @return Screening
     */
    public static function startFromPreInterview(EmailAddress $applicantEmailAddress): Screening
    {
        $object = new Screening();

        // EmailAddressインスタンスは正しいものしか生成されないので、
        // Screeningクラスでのバリデーションは不要になっている
        $object->applicantEmailAddress = $applicantEmailAddress;


        // 面談からの場合はステータス「未応募」で登録
        $object->status = ScreeningStatus::NotApplied();
        // 未応募なので応募日はnull
        $object->applyDate = null;
        // インタビュー回数のInterviews作成
        $object->interviews = new Interviews();

        return $object;
    }

    /**
     * 面接から採用選考を登録する際のファクトリメソッド
     *
     * @param EmailAddress $applicantEmailAddress
     * @return Screening
     * @throws Exception
     */
    public static function apply(EmailAddress $applicantEmailAddress): Screening
    {
        $object = new Screening();

        // メールアドレスは引数のものを登録
        $object->applicantEmailAddress = $applicantEmailAddress;
        // 面接からの場合はステータス「面接選考中」で登録
        $object->status = ScreeningStatus::Interview();
        // 応募日は操作日付を使用
        $object->applyDate = new \DateTime();
        // インタビュー回数のInterviews作成
        $object->interviews = new Interviews();

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

        // インタビュー次数の判断はInterviewsクラスに委譲している
        $this->interviews->addNextInterview($this->getId(), $interviewDate);
    }

    /**
     * 次のステップへ進む
     *
     * @throws \InvalidArgumentException
     */
    public function stepToNext(): void
    {
        // 状態遷移は ScreeningStatus の責務なのでただ呼び出すだけ。
        $this->status = $this->status->nextStep();
    }

    /**
     * Repository からの再構成用メソッド
     *
     * @param int $screeningId
     * @param string $applicantEmailAddress
     * @param ScreeningStatus $status
     * @param \DateTime|null $applyDate
     * @param Interviews $interviews
     * @return static
     */
    public static function reconstruct(int $screeningId, string $applicantEmailAddress, ScreeningStatus $status, ?\DateTime $applyDate, Interviews $interviews): self
    {
        $object = new Screening();
        $object->id = ScreeningId::reconstruct($screeningId);
        $object->applicantEmailAddress = EmailAddress::reconstruct($applicantEmailAddress);
        $object->status = $status;
        $object->applyDate = $applyDate;
        $object->interviews = $interviews;
        return $object;
    }

    /**
     * @return ScreeningId
     */
    public function getId(): ScreeningId
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getApplyDate(): ?\DateTime
    {
        return $this->applyDate;
    }

    /**
     * @return ScreeningStatus
     */
    public function getStatus(): ScreeningStatus
    {
        return $this->status;
    }

    /**
     * @return EmailAddress
     */
    public function getApplicantEmailAddress(): EmailAddress
    {
        return $this->applicantEmailAddress;
    }

    /**
     * @return Interviews
     */
    public function getInterviews(): Interviews
    {
        return $this->interviews;
    }
}
