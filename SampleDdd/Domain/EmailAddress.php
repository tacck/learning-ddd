<?php

namespace SampleDdd\Domain;

class EmailAddress
{
    /** @var string */
    private $value;

    /**
     * EmailAddress constructor.
     *
     * @param string $value
     * @throws \InvalidArgumentException
     */
    public function __construct(string $value)
    {
        if (self::isEmpty($value)
            || self::isInvalidFormatEmailAddress($value)) {
            throw new \InvalidArgumentException("メールアドレスが正しくありません");
        }

        $this->value = $value;
    }

    /**
     * Repository からの再構成用メソッド
     *
     * @param string $value
     * @return EmailAddress
     */
    public static function reconstruct(string $value): EmailAddress
    {
        // Javaと異なり、 __construct() のオーバーライドによる可視範囲の切り分けなどができないので、通常のコンストラクタを使うようにしている。
        // reconstruct() で検証なしとするためには、通常の生成用ファクトリーメソッドを作るようにしておくべきだが、ここでは省略。
        // また、可視性も public とする。
        // 厳密に同じ処理になるようにする場合、呼び出し元のクラスの名前空間を、このクラスの名前空間と比較するなどのガードが必要。
        // 下記は実装例。 debug_backtrace() を使っているので、PHPで同様の実装にするのはやや無理がありそう。
        /*
        $backtrace = debug_backtrace();
        if (count($backtrace) > 1) {
            $nameSpaceOfCalledClass = \substr($backtrace[1]['class'], 0, strrpos($backtrace[0]['class'], '\\'));
            if ($nameSpaceOfCalledClass !== __NAMESPACE__) {
                throw new \UnexpectedValueException('呼び元の名前空間の不一致');
            }
        }
        */

        $emailAddress = new EmailAddress($value);
        return $emailAddress;
    }

    /**
     * 値の文字列取得
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * 比較
     *
     * @param EmailAddress $emailAddress
     * @return bool
     */
    public function equals(EmailAddress $emailAddress): bool
    {
        return $this->value === $emailAddress->getValue();
    }

    /**
     * 値取得
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
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
