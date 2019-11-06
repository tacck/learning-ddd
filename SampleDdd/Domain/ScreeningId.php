<?php

namespace SampleDdd\Domain;

class ScreeningId
{
    /** @var int */
    private $screeningId;

    /**
     * ScreeningId constructor.
     * @param int $screeningId
     */
    public function __construct(int $screeningId)
    {
        // 元はUUIDにしていたが、ここは普通の int としている。
        $this->screeningId = $screeningId;
    }

    /**
     * 再構成用メソッド
     *
     * @param int $screeningId
     * @return $this
     */
    public function reconstruct(int $screeningId): self
    {
        return new self($screeningId);
    }

    /**
     * オブジェクトの値を取得
     *
     * @return int
     */
    public function getValue(): int
    {
        return $this->screeningId;
    }
}
