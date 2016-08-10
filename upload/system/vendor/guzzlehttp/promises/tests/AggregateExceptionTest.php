<?php
namespace GuzzleHttp\Promise\Tests;

use GuzzleHttp\Promise\AggregateException;

class AggregateExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testHasReason()
    {
        $e = new AggregateException('foo', ['baz', 'bar']);
        $this->assertContains('foo', $e->getMessage());
        $this->assertEquals(['baz', 'bar'], $e->getReason());
    }
}
