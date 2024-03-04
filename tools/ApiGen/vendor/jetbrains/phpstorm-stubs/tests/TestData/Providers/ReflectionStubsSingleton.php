<?php

namespace StubTests\TestData\Providers;

use StubTests\Model\StubsContainer;
use StubTests\Parsers\PHPReflectionParser;

class ReflectionStubsSingleton
{
    /**
     * @var StubsContainer|null
     */
    private static $reflectionStubs;

    /**
     * @return StubsContainer
     */
    public static function getReflectionStubs()
    {
        if (self::$reflectionStubs === null) {
            self::$reflectionStubs = PHPReflectionParser::getStubs();
        }
        return self::$reflectionStubs;
    }
}
