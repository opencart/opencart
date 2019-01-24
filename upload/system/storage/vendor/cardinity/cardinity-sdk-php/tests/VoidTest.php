<?php
namespace Cardinity\Tests;

use Cardinity\Method\Void;
use Cardinity\Method\Payment;

class VoidTest extends ClientTestCase
{
    public function testResultObjectSerialization()
    {
        $refund = new Void\Void();
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

        $payment = new Void\Void();
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
        $method = new Void\Create(
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
        $method = new Void\Create(
            $payment->getId(),
            'my description'
        );
        $result = $this->client->call($method);

        $this->assertInstanceOf('Cardinity\Method\Void\Void', $result); 

        return $result;
    }

    /**
     * @depends testCreate
     */
    public function testGet(Void\Void $void)
    {
        $method = new Void\Get(
            $void->getParentId(),
            $void->getId()
        );
        $result = $this->client->call($method);

        $this->assertInstanceOf('Cardinity\Method\Void\Void', $result); 
        $this->assertSame($void->getParentId(), $result->getParentId());
        $this->assertSame('void', $result->getType());
        $this->assertSame('my description', $result->getDescription());
    }

    /**
     * @depends testCreate
     */
    public function testGetAll(Void\Void $void)
    {
        $method = new Void\GetAll(
            $void->getParentId()
        );
        $result = $this->client->call($method);

        $this->assertInstanceOf('Cardinity\Method\Void\Void', $result[0]); 
        $this->assertSame($void->getId(), $result[0]->getId());
        $this->assertSame($void->getParentId(), $result[0]->getParentId());
    }
}
