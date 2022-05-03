<?php
namespace Cardinity\Tests;

use Cardinity\Client;
use Cardinity\Method\Payment;

class ErrorTest extends ClientTestCase
{
    public function testErrorResultObjectForErrorResponse()
    {
        $method = $this
            ->getMockBuilder('Cardinity\Method\Payment\Get')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $method->method('getAction')->willReturn('payments');
        $method->method('getAttributes')->willReturn([]);
        $method->method('getMethod')->willReturn(Payment\Get::POST);

        try {
            $this->client->call($method);
        } catch (\Cardinity\Exception\ValidationFailed $e) {
            $result = $e->getResult();
            $this->assertInstanceOf('Cardinity\Method\Error', $result);
            $this->assertSame('https://developers.cardinity.com/api/v1/#400', $result->getType());
            $this->assertSame('Validation Failed', $result->getTitle());
            $this->assertContains('validation errors', $result->getDetail());
            $this->assertTrue(is_array($result->getErrors()));
            $this->assertNotEmpty($result->getErrors());
        }
    }

    /**
     * @expectedException Cardinity\Exception\Unauthorized
     */
    public function testUnauthorizedResponse()
    {
        $client = Client::create([
            'consumerKey' => 'no',
            'consumerSecret' => 'yes',
        ]);

        $method = new Payment\Get('xxxyyy');

        $client->call($method);
    }

    /**
     * @expectedException Cardinity\Exception\ValidationFailed
     */
    public function testBadRequest()
    {
        $method = $this
            ->getMockBuilder('Cardinity\Method\Payment\Get')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $method->method('getAction')->willReturn('payments');
        $method->method('getAttributes')->willReturn([]);
        $method->method('getMethod')->willReturn(Payment\Get::POST);

        $this->client->call($method);
    }

    /**
     * @expectedException Cardinity\Exception\NotFound
     */
    public function testNotFound()
    {
        $method = $this
            ->getMockBuilder('Cardinity\Method\Payment\Get')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $method->method('getAction')->willReturn('my_dreamy_action');
        $method->method('getAttributes')->willReturn([]);
        $method->method('getMethod')->willReturn(Payment\Get::POST);

        $this->client->call($method);
    }

    /**
     * @expectedException Cardinity\Exception\MethodNotAllowed
     */
    public function testMethodNotAllowed()
    {
        $method = $this
            ->getMockBuilder('Cardinity\Method\Payment\Get')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $method->method('getAction')->willReturn('payments');
        $method->method('getAttributes')->willReturn([]);
        $method->method('getMethod')->willReturn('DELETE');

        $this->client->call($method);
    }
}
