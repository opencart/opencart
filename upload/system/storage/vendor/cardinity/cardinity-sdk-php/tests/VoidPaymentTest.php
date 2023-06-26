<?php

namespace Cardinity\Tests;

use Cardinity\Method\VoidPayment;
use Cardinity\Method\Payment;

class VoidPaymentTest extends ClientTestCase
{
    public function testResultObjectSerialization()
    {
        $refund = new VoidPayment\VoidPayment();
        $refund->setId('foo');
        $refund->setType('bar');
        $refund->setCreated(null);

        $this->assertSame(
            '{"id":"foo","type":"bar"}',
            $refund->serialize()
        );
    }

    public function testResultObjectUnserialization()
    {
        $json = '{"id":"foo","type":"bar"}';

        $payment = new VoidPayment\VoidPayment();
        $payment->unserialize($json);

        $this->assertSame('foo', $payment->getId());
        $this->assertSame('bar', $payment->getType());
        $this->assertSame(null, $payment->getDescription());
    }

    /**
     * @return Payment\Payment
     */
    public function testCreatePayment()
    {
        $method = new Payment\Create($this->getPaymentParams());
        $payment = $this->client->call($method);

        return $payment;
    }

    /**
     * @depends testCreatePayment
     * @expectedException Cardinity\Exception\Declined
     */
    public function testCreateFail(Payment\Payment $payment)
    {
        $method = new VoidPayment\Create(
            $payment->getId(),
            'fail'
        );
        $this->client->call($method);
    }

    /**
     * @depends testCreatePayment
     */
    public function testCreate(Payment\Payment $payment)
    {
        $method = new VoidPayment\Create(
            $payment->getId(),
            'my description'
        );
        $result = $this->client->call($method);

        $this->assertInstanceOf('Cardinity\Method\VoidPayment\VoidPayment', $result);
        $this->assertSame(true, $result->isApproved());

        return $result;
    }

    /**
     * @depends testCreate
     */
    public function testGet(VoidPayment\VoidPayment $void)
    {
        $method = new VoidPayment\Get(
            $void->getParentId(),
            $void->getId()
        );
        $result = $this->client->call($method);

        $this->assertInstanceOf('Cardinity\Method\VoidPayment\VoidPayment', $result);
        $this->assertSame($void->getParentId(), $result->getParentId());
        $this->assertSame('void', $result->getType());
        $this->assertSame('my description', $result->getDescription());
    }

    /**
     * @depends testCreate
     */
    public function testGetAll(VoidPayment\VoidPayment $void)
    {
        $method = new VoidPayment\GetAll(
            $void->getParentId()
        );
        $result = $this->client->call($method);

        $this->assertInstanceOf('Cardinity\Method\VoidPayment\VoidPayment', $result[0]);
        $this->assertSame($void->getId(), $result[0]->getId());
        $this->assertSame($void->getParentId(), $result[0]->getParentId());
    }
}
