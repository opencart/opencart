<?php
namespace GuzzleHttp\Tests\Promise;

use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Promise\FulfilledPromise;

/**
 * @covers GuzzleHttp\Promise\FulfilledPromise
 */
class FulfilledPromiseTest extends \PHPUnit_Framework_TestCase
{
    public function testReturnsValueWhenWaitedUpon()
    {
        $p = new FulfilledPromise('foo');
        $this->assertEquals('fulfilled', $p->getState());
        $this->assertEquals('foo', $p->wait(true));
    }

    public function testCannotCancel()
    {
        $p = new FulfilledPromise('foo');
        $this->assertEquals('fulfilled', $p->getState());
        $p->cancel();
        $this->assertEquals('foo', $p->wait());
    }

    /**
     * @expectedException \LogicException
     * @exepctedExceptionMessage Cannot resolve a fulfilled promise
     */
    public function testCannotResolve()
    {
        $p = new FulfilledPromise('foo');
        $p->resolve('bar');
    }

    /**
     * @expectedException \LogicException
     * @exepctedExceptionMessage Cannot reject a fulfilled promise
     */
    public function testCannotReject()
    {
        $p = new FulfilledPromise('foo');
        $p->reject('bar');
    }

    public function testCanResolveWithSameValue()
    {
        $p = new FulfilledPromise('foo');
        $p->resolve('foo');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCannotResolveWithPromise()
    {
        new FulfilledPromise(new Promise());
    }

    public function testReturnsSelfWhenNoOnFulfilled()
    {
        $p = new FulfilledPromise('a');
        $this->assertSame($p, $p->then());
    }

    public function testAsynchronouslyInvokesOnFulfilled()
    {
        $p = new FulfilledPromise('a');
        $r = null;
        $f = function ($d) use (&$r) { $r = $d; };
        $p2 = $p->then($f);
        $this->assertNotSame($p, $p2);
        $this->assertNull($r);
        \GuzzleHttp\Promise\queue()->run();
        $this->assertEquals('a', $r);
    }

    public function testReturnsNewRejectedWhenOnFulfilledFails()
    {
        $p = new FulfilledPromise('a');
        $f = function () { throw new \Exception('b'); };
        $p2 = $p->then($f);
        $this->assertNotSame($p, $p2);
        try {
            $p2->wait();
            $this->fail();
        } catch (\Exception $e) {
            $this->assertEquals('b', $e->getMessage());
        }
    }

    public function testOtherwiseIsSugarForRejections()
    {
        $c = null;
        $p = new FulfilledPromise('foo');
        $p->otherwise(function ($v) use (&$c) { $c = $v; });
        $this->assertNull($c);
    }

    public function testDoesNotTryToFulfillTwiceDuringTrampoline()
    {
        $fp = new FulfilledPromise('a');
        $t1 = $fp->then(function ($v) { return $v . ' b'; });
        $t1->resolve('why!');
        $this->assertEquals('why!', $t1->wait());
    }
}
