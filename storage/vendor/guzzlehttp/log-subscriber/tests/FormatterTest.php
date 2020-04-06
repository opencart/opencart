<?php
namespace GuzzleHttp\Tests\Subscriber\LogSubscriber;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Message\Request;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Subscriber\Log\Formatter;

/**
 * @covers GuzzleHttp\Subscriber\Log\Formatter
 */
class FormatterTest extends \PHPUnit_Framework_TestCase
{
    public function testCreatesWithClfByDefault()
    {
        $f = new Formatter();
        $this->assertEquals(Formatter::CLF, $this->readAttribute($f, 'template'));
        $f = new Formatter(null);
        $this->assertEquals(Formatter::CLF, $this->readAttribute($f, 'template'));
    }

    public function testFormatsMessagesWithCustomData()
    {
        $f = new Formatter('{foo} - {method} - {code}');
        $request = new Request('GET', '/');
        $response = new Response(200);
        $result = $f->format($request, $response, null, ['foo' => 'bar']);
        $this->assertEquals('bar - GET - 200', $result);
    }

    public function testFormatsTimestamps()
    {
        $f = new Formatter('{ts}');
        $request = new Request('GET', '/');
        $result = $f->format($request);
        // Ensure it matches this format: '2014-03-02T00:18:41+00:00';
        $this->assertEquals(1, preg_match('/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}/', $result));
    }

    public function formatProvider()
    {
        $request = new Request('PUT', '/', ['x-test' => 'abc'], Stream::factory('foo'));
        $response = new Response(200, ['X-Baz' => 'Bar'], Stream::factory('baz'));
        $err = new RequestException('Test', $request, $response);

        return [
            ['{request}', [$request], (string) $request],
            ['{response}', [$request, $response], (string) $response],
            ['{request} {response}', [$request, $response], $request . ' ' . $response],
            // Empty response yields no value
            ['{request} {response}', [$request], $request . ' '],
            ['{req_headers}', [$request], "PUT / HTTP/1.1\r\nx-test: abc"],
            ['{res_headers}', [$request, $response], "HTTP/1.1 200 OK\r\nX-Baz: Bar"],
            ['{res_headers}', [$request], 'NULL'],
            ['{req_body}', [$request], 'foo'],
            ['{res_body}', [$request, $response], 'baz'],
            ['{res_body}', [$request], 'NULL'],
            ['{method}', [$request], $request->getMethod()],
            ['{url}', [$request], $request->getUrl()],
            ['{resource}', [$request], $request->getResource()],
            ['{req_version}', [$request], $request->getProtocolVersion()],
            ['{res_version}', [$request, $response], $response->getProtocolVersion()],
            ['{res_version}', [$request], 'NULL'],
            ['{host}', [$request], $request->getHost()],
            ['{hostname}', [$request, $response], gethostname()],
            ['{hostname}{hostname}', [$request, $response], gethostname() . gethostname()],
            ['{code}', [$request, $response], $response->getStatusCode()],
            ['{code}', [$request], 'NULL'],
            ['{phrase}', [$request, $response], $response->getReasonPhrase()],
            ['{phrase}', [$request], 'NULL'],
            ['{error}', [$request, $response, $err], 'Test'],
            ['{error}', [$request], 'NULL'],
            ['{req_header_x-test}', [$request], 'abc'],
            ['{req_header_x-not}', [$request], ''],
            ['{res_header_X-Baz}', [$request, $response], 'Bar'],
            ['{res_header_x-not}', [$request, $response], ''],
            ['{res_header_X-Baz}', [$request], 'NULL'],
        ];
    }

    /**
     * @dataProvider formatProvider
     */
    public function testFormatsMessages($template, $args, $result)
    {
        $f = new Formatter($template);
        $this->assertEquals((string) $result, call_user_func_array(array($f, 'format'), $args));
    }
}
