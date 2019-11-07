<?php

namespace SampleDdd\Domain;

/**
 * Class ScreeningStatus
 * @package SampleDdd\Domain
 */
class ScreeningStatus
{
    // 状態の定義部分で元と似たような記法と振る舞いを持たせるために、MyCLabs\Enum\Enum パッケージをやめる。

    /** 未応募 */
    private const NotApplied = false;

    /** 書類選考 */
    private const DocumentScreening = false;
    /** 書類不合格 */
    private const DocumentScreeningRejected = false;
    /** 書類選考辞退 */
    private const DocumentScreeningDeclined = false;

    /** 面接選考中 */
    private const Interview = true;
    /** 面接不合格 */
    private const InterviewRejected = false;
    /** 面接辞退 */
    private const InterviewDeclined = false;

    /** 内定 */
    private const Offered = false;
    /** 内定辞退 */
    private const OfferDeclined = false;

    /** 入社済 */
    private const Entered = false;

    /** @var string $value */
    private static $value;

    /** @var array $cache */
    private static $cache = [];

    /**
     * ScreeningStatus constructor.
     *
     * @param string $value
     * @throws \ReflectionException
     * @throws \InvalidArgumentException
     */
    public function __construct(string $value)
    {
        // Model から Entity へ変換する際は文字列ベースで生成する必要があるため、コンストラクタを利用する。
        // そのため、ここで引数チェックを行なう。
        if (!\array_key_exists($value, static::toArray())) {
            throw new \InvalidArgumentException('存在しないステータスです');
        }

        self::$value = $value;
    }

    /**
     * 静的メソッド呼び出しからオブジェクト作成
     * ScreeningStatus::NotApplied() という使い方が可能。
     *
     * @param $value
     * @param $arguments
     * @return static
     * @throws \ReflectionException
     */
    public static function __callStatic($value, $arguments): self
    {
        // コンストラクタでチェックしているので、そのまま渡す。
        return new static($value);
    }

    /**
     * オブジェクトの値を文字列で取得
     * @return string
     */
    public function __toString(): string
    {
        return self::$value;
    }

    /**
     * オブジェクトの値を取得
     *
     * @return string
     */
    public function getValue(): string
    {
        return self::$value;
    }

    /**
     * オブジェクトの持つ状態が面接可能か
     *
     * @return bool
     * @throws \ReflectionException
     */
    public function canAddInterview(): bool
    {
        $statuses = self::toArray();
        return $statuses[self::$value];
    }

    /**
     * 次のステップのステータスを取得
     *
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function nextStep(): self
    {
        switch ($this->getValue()) {
            case self::NotApplied():
                return self::DocumentScreening();
            case self::DocumentScreening():
                return self::Interview();
            case self::Interview():
                return self::Offered();
            case self::Offered():
                return self::Entered();
            default:
                throw new \InvalidArgumentException('許可されていない状態遷移です。');
        }
    }

    /**
     * 「戻る」した時のステータスを取得する
     *
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function previousStep(): self
    {
        switch ($this->getValue()) {
            case self::DocumentScreeningRejected():
                return self::DocumentScreening();
            case self::DocumentScreeningDeclined():
                return self::DocumentScreening();
            case self::Interview():
                return self::DocumentScreening();
            case self::InterviewRejected():
                return self::Interview();
            case self::InterviewDeclined():
                return self::Interview();
            case self::Offered():
                return self::Interview();
            case self::OfferDeclined():
                return self::Offered();
            case self::Entered():
                return self::Offered();
            default:
                throw new \InvalidArgumentException('許可されていない状態遷移です。');
        }
    }

    /**
     * 「不合格」した時のステータスを取得する
     *
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function rejectStep(): self
    {
        switch ($this->getValue()) {
            case self::DocumentScreening():
                return self::DocumentScreeningRejected();
            case self::Interview():
                return self::InterviewRejected();
            default:
                throw new \InvalidArgumentException('許可されていない状態遷移です。');
        }
    }

    /**
     * 「辞退」した時のステータスを取得する
     *
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function declineStep(): self
    {
        switch ($this->getValue()) {
            case self::DocumentScreening():
                return self::DocumentScreeningDeclined();
            case self::Interview():
                return self::InterviewDeclined();
            case self::Offered():
                return self::OfferDeclined();
            default:
                throw new \InvalidArgumentException('許可されていない状態遷移です。');
        }
    }

    /**
     * クラス変数を配列で取得
     * 静的呼び出しの場合にキャッシュする。
     *
     * @return mixed
     * @throws \ReflectionException
     */
    private static function toArray()
    {
        $class = \get_called_class();
        if (!isset(static::$cache[$class])) {
            $reflection = new \ReflectionClass($class);
            static::$cache[$class] = $reflection->getConstants();
        }

        return static::$cache[$class];
    }
}
