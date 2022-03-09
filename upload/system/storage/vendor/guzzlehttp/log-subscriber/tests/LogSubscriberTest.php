<?php
namespace GuzzleHttp\Tests\Subscriber\LogSubscriber;

use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Log\Formatter;
use GuzzleHttp\Subscriber\Log\LogSubscriber;
use GuzzleHttp\Subscriber\Log\SimpleLogger;
use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Message\Response;

/**
 * @covers GuzzleHttp\Subscriber\Log\LogSubscriber
 */
class LogSubscriberTest extends \PHPUnit_Framework_TestCase
{
    public function testUsesSimpleLogger()
    {
        $s = new LogSubscriber();
        $this->assertInstanceOf('GuzzleHttp\\Subscriber\\Log\\SimpleLogger', $this->readAttribute($s, 'logger'));
        $s = new LogSubscriber(function () {});
        $this->assertInstanceOf('GuzzleHttp\\Subscriber\\Log\\SimpleLogger', $this->readAttribute($s, 'logger'));
        $r = fopen('php://temp', 'r+');
        $s = new LogSubscriber($r);
        $this->assertInstanceOf('GuzzleHttp\\Subscriber\\Log\\SimpleLogger', $this->readAttribute($s, 'logger'));
        fclose($r);
    }

    public function testUsesLogger()
    {
        $logger = new SimpleLogger();
        $log = new LogSubscriber($logger);
        $this->assertSame($logger, $this->readAttribute($log, 'logger'));
    }

    public function testUsesFormatString()
    {
        $log = new LogSubscriber(null, '{test}');
        $formatter = $this->readAttribute($log, 'formatter');
        $this->assertEquals('{test}', $this->readAttribute($formatter, 'template'));
    }

    public function testUsesFormatter()
    {
        $formatter = new Formatter();
        $log = new LogSubscriber(null, $formatter);
        $this->assertSame($formatter, $this->readAttribute($log, 'formatter'));
    }

    public function testLogsAfterSending()
    {
        $resource = fopen('php://temp', 'r+');
        $logger = new LogSubscriber($resource, '{code}');
        $client = new Client();
        $client->getEmitter()->attach($logger);
        $client->getEmitter()->attach(new Mock([new Response(200)]));
        $client->get('http://httbin.org/get');
        rewind($resource);
        $this->assertEquals("[info] 200\n", stream_get_contents($resource));
        fclose($resource);
    }

    public function testLogsAfterError()
    {
        $resource = fopen('php://temp', 'r+');
        $logger = new LogSubscriber($resource, '{code}');
        $client = new Client();
        $client->getEmitter()->attach($logger);
        $client->getEmitter()->attach(new Mock([new Response(500)]));
        try {
            $client->get('http://httbin.org/get');
        } catch (\Exception $e) {}
        rewind($resource);
        $this->assertEquals("[critical] 500\n", stream_get_contents($resource));
        fclose($resource);
    }
}
