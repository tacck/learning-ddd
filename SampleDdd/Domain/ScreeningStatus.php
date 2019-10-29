<?php


namespace SampleDdd\Domain;


use MyCLabs\Enum\Enum;

class ScreeningStatus extends Enum
{
    /** @var string 未応募 */
    private const NotApplied = 'NotApplied';

    /** @var string 面接選考中 */
    private const Interview = 'Interview';

    /** @var string 不合格 */
    private const Rejected = 'Rejected';

    /** @var string 合格 */
    private const Passed = 'Passed';
}
