<?php
declare(strict_types=1);

namespace StubTests;

use PHPUnit\Framework\TestCase;

class CheckStubMapTest extends TestCase
{
    private $oldMapFile = __DIR__ . '/../PhpStormStubsMap.php';
    private $newMapFile;

    public function testStubMapIsUpToDate(): void
    {
        $this->assertFileEquals(
            $this->newMapFile,
            $this->oldMapFile,
            'The commited stub map is not up to date. Please regenerate it using ./generate-stub-map'
        );
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->newMapFile = tempnam(__DIR__ . '/../', 'stub');
        $generator = escapeshellarg(__DIR__ . '/Tools/generate-stub-map');
        $newStubMap = escapeshellarg($this->newMapFile);
        exec("php $generator $newStubMap", $output, $exitCode);
        if ($exitCode) {
            $this->fail("PHP script $generator exited with code $exitCode: " . implode("\n", $output));
        }
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        unlink($this->newMapFile);
    }
}
