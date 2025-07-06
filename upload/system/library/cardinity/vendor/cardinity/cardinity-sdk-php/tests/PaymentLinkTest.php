<?php

namespace Cardinity\Tests;

use Cardinity\Exception;
use Cardinity\Method\PaymentLink;
use Cardinity\Method\ResultObject;

class PaymentLinkTest extends ClientTestCase
{
    private $paymentLinkParams;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->paymentLinkParams = [
            'amount' => 50.00,
            'currency' => 'EUR',
            'description' => 'Payment Link For Unit Test',

            'country' => 'LT',
            'multiple_use' => null,
        ];
        parent::setUp();
    }


    /**
     * @return void
     */
    public function testResultObjectSerialization()
    {
        $paymentLink  = new PaymentLink\PaymentLink();
        $paymentLink->setId('foo');
        $paymentLink->setAmount('55.00');
        $paymentLink->setDescription('bar');
        $paymentLink->setCurrency('USD');


        $this->assertSame(
            '{"id":"foo","amount":"55.00","currency":"USD","description":"bar"}',
            $paymentLink->serialize()
        );
    }

    /**
     * @return void
     */
    public function testResultObjectUnserialization()
    {
        $json = '{"id":"foo","amount":"55.00","currency":"USD","description":"bar"}';

        $paymentLink  = new PaymentLink\PaymentLink();
        $paymentLink->unserialize($json);

        $this->assertSame('foo', $paymentLink->getId());
        $this->assertSame('bar', $paymentLink->getDescription());
        $this->assertSame('USD', $paymentLink->getCurrency());
        $this->assertSame(55.00, $paymentLink->getAmount());

    }

    /**
     * @return void
     */
    public function testAmountValidationConstraint()
    {
        $params = $this->paymentLinkParams;

        $params['amount'] = -20.00;
        $method = new PaymentLink\Create($params);

        $this->expectException(\Cardinity\Exception\InvalidAttributeValue::class);
        $this->client->call($method);

        $params['amount'] = '20.00';
        $method = new PaymentLink\Create($params);

        $this->expectException(\Cardinity\Exception\InvalidAttributeValue::class);
        $this->client->call($method);
    }

     /**
     * @return void
     */
    public function testMissingRequiredAttribute()
    {
        $params = $this->paymentLinkParams;
        unset($params['currency']);
        $method = new PaymentLink\Create($params);
        $this->expectException(\Cardinity\Exception\InvalidAttributeValue::class);
        $this->client->call($method);
    }


    /**
     * @return ResultObject
     */
    public function testCreate()
    {
        $method = new PaymentLink\Create($this->paymentLinkParams);
        $result = $this->client->call($method);

        $this->assertInstanceOf('Cardinity\Method\PaymentLink\PaymentLink', $result);
        $this->assertSame(true, $result->getEnabled());
        $this->assertSame(false, $result->getMultipleUse());
        $this->assertSame("https://checkout.cardinity.com/link/".$result->getId(), $result->getUrl());

        return $result;
    }



    /**
     * @depends testCreate
     * @param PaymentLink\PaymentLink
     * @return void
     */
    public function testGet(PaymentLink\PaymentLink $paymentLink)
    {
        $method = new PaymentLink\Get($paymentLink->getId());
        $result = $this->client->call($method);

        $this->assertInstanceOf('Cardinity\Method\PaymentLink\PaymentLink', $result);
        $this->assertSame(true, $result->getEnabled());

        return $result;
    }


    /**
     * @depends testGet
     * @param PaymentLink\PaymentLink
     * @return ResultObject
     */
    public function testUpdate(PaymentLink\PaymentLink $paymentLink)
    {
        $params = array(
            'enabled' => false
        );

        $method = new PaymentLink\Update($paymentLink->getId(), $params);
        $result = $this->client->call($method);

        $this->assertInstanceOf('Cardinity\Method\PaymentLink\PaymentLink', $result);
        $this->assertSame(false, $result->getEnabled());

        return $result;
    }


    /**
     * @return void
     */
    public function testExpirationDateAttribute()
    {
        $params = $this->paymentLinkParams;

        //test invalid date
        $params['expiration_date'] = 'abcdefg';
        $method = new PaymentLink\Create($params);

        try {
            $this->client->call($method);
        } catch (\Exception $e) {
            $this->assertInstanceOf('Cardinity\Exception\InvalidAttributeValue', $e);
            $this->assertStringContainsString('Date Time string should follow ISO 8601. e.g: 2023-01-06T15:26:03.702Z', $e->getMessage());
        }


        //test valid date non utc date
        $params['expiration_date'] = '2023-02-27T10:30:00+06:00';
        $method = new PaymentLink\Create($params);

        try {
            $this->client->call($method);
        } catch (\Exception $e) {
            $this->assertInstanceOf('Cardinity\Exception\InvalidAttributeValue', $e);
            $this->assertStringContainsString('Date Time should be in UTC timezone', $e->getMessage());
        }

        //valid date
        $params['expiration_date'] = date("Y-").date('m-',strtotime('first day of +1 month'))."01T15:26:03.702Z";
        $method = new PaymentLink\Create($params);
        $result = $this->client->call($method);
        $this->assertInstanceOf('Cardinity\Method\PaymentLink\PaymentLink', $result);

        //valid date that is too far
        $params['expiration_date'] = date("Y-m-",strtotime('first day of +1 year'))."01T15:26:03.702Z";
        $method = new PaymentLink\Create($params);
        $this->expectException(\Cardinity\Exception\ValidationFailed::class);
        $result = $this->client->call($method);
    }

}
