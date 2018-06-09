<?php

class SanityTest extends PHPUnit_Framework_TestCase
{
    public function testLibraryWorksWithComposer()
    {
        if (version_compare(PHP_VERSION, "5.4.0", "<")) {
            $this->markTestSkipped("Requires PHP >=5.4");
        }

        $returnValue = null;

        $testFile = escapeshellarg(realpath(__DIR__ . '/Braintree/fixtures/composer_implementation.php'));
        $command = sprintf('%s %s', PHP_BINARY, $testFile);

        system($command, $returnValue);

        $this->assertEquals(0, $returnValue);
    }
}
