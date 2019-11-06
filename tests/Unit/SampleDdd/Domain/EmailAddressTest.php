<?php

namespace Test\Unit\SampleDdd\Domain;

use SampleDdd\Domain\EmailAddress;
use PHPUnit\Framework\TestCase;

class EmailAddressTest extends TestCase
{

    public function testReconstruct()
    {
        $emailAddress = EmailAddress::reconstruct('test@example.com');
        $this->assertSame('test@example.com', $emailAddress->getValue());
    }
}
