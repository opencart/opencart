<?php
declare(strict_types=1);

namespace StubTests\TestData\Providers;

use StubTests\Model\StubsContainer;
use StubTests\Parsers\StubParser;

class PhpStormStubsSingleton
{
    private static ?StubsContainer $phpstormStubs = null;

    public static function getPhpStormStubs(): StubsContainer
    {
        if (self::$phpstormStubs === null) {
            self::$phpstormStubs = StubParser::getPhpStormStubs();
        }
        return self::$phpstormStubs;
    }
}
