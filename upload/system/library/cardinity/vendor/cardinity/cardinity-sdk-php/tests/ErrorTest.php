<?php

namespace Cardinity\Tests;

use Cardinity\Client;
use Cardinity\Method\Payment;
use Cardinity\Exception\InvalidAttributeValue;

class ErrorTest extends ClientTestCase
{
    /**
     * @return object
     */
    private function getPaymentMethodMock()
    {
        return $this
            ->getMockBuilder('Cardinity\Method\Payment\Get')
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }

    /**
     * @return void
     */
    public function testErrorResultObjectForErrorResponse()
    {
        $method = $this->getPaymentMethodMock();
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
            $this->assertStringContainsString('validation errors', $result->getDetail());
            $this->assertTrue(is_array($result->getErrors()));
            $this->assertNotEmpty($result->getErrors());
        }
    }

    /**
     * @return void
     */
    public function testUnauthorizedResponse()
    {
        $client = Client::create([
            'consumerKey' => 'no',
            'consumerSecret' => 'yes',
        ]);
        $method = new Payment\Get('xxxyyy');
        $this->expectException(\Cardinity\Exception\Unauthorized::class);

        $client->call($method);
    }

    /**
     * @return void
     */
    public function testBadRequest()
    {
        $method = $this->getPaymentMethodMock();
        $method->method('getAction')->willReturn('payments');
        $method->method('getAttributes')->willReturn([]);
        $method->method('getMethod')->willReturn(Payment\Get::POST);

        $this->expectException(\Cardinity\Exception\ValidationFailed::class);
        $this->client->call($method);
    }

    /**
     * @return void
     */
    public function testNotFound()
    {
        $method = $this->getPaymentMethodMock();
        $method->method('getAction')->willReturn('my_dreamy_action');
        $method->method('getAttributes')->willReturn([]);
        $method->method('getMethod')->willReturn(Payment\Get::POST);

        $this->expectException(\Cardinity\Exception\NotFound::class);
        $this->client->call($method);
    }

    /**
     * @return void
     */
    public function testMethodNotAllowed()
    {
        $method = $this->getPaymentMethodMock();;
        $method->method('getAction')->willReturn('payments');
        $method->method('getAttributes')->willReturn([]);
        $method->method('getMethod')->willReturn('DELETE');

        $this->expectException(\Cardinity\Exception\MethodNotAllowed::class);
        $this->client->call($method);
    }
}
