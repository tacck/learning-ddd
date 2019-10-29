<?php


namespace SampleDdd\Domain;


use MyCLabs\Enum\Enum;

class ScreeningStepResult extends Enum
{
    /** @var string 未評価 */
    private const NotEvaluated = 'NotEvaluated';

    /** @var string 通過 */
    private const Pass = 'Pass';

    /** @var string 不合格 */
    private const Fail = 'Fail';
}
