<?php

declare(strict_types = 1);

namespace CodeLts\CliTools\Tests;

use CodeLts\CliTools\Utils;

class UtilsTest extends AbstractTestCase
{

    public function testIsCiDetected(): void
    {
        $this->assertIsBool(Utils::isCiDetected());
    }

}
