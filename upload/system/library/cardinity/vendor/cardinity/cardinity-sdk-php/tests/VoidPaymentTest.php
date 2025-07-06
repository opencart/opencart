<?php

namespace Cardinity\Tests;

use Cardinity\Method\VoidPayment;
use Cardinity\Method\Payment;

class VoidPaymentTest extends ClientTestCase
{
    /**
     * @return void
     */
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

    /**
     * @return void
     */
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
     * @return Cardinity\Method\ResultObject $payment
     */
    public function testCreatePayment()
    {
        $method = new Payment\Create($this->getPaymentParams());
        $payment = $this->client->call($method);

        return $payment;
    }

    /**
     * @depends testCreatePayment
     * @param Payment\Payment $payment
     * @return void
     */
    public function testCreateFail(Payment\Payment $payment)
    {
        $method = new VoidPayment\Create(
            $payment->getId(),
            'fail'
        );
        $this->expectException(\Cardinity\Exception\Declined::class);
        $this->client->call($method);
    }

    /**
     * @depends testCreatePayment
     * @param Payment\Payment $payment
     * @return Cardinity\Method\ResultObject $result
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
     * @param VoidPayment\VoidPayment $void
     * @return void
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
     * @param VoidPayment\VoidPayment $void
     * @return void
     */
    public function testGetAll(VoidPayment\VoidPayment $void)
    {
        $method = new VoidPayment\GetAll(
            $void->getParentId()
        );
        $result = $this->client->call($method);

        $this->assertInstanceOf('Cardinity\Method\VoidPayment\VoidPayment', $result[0]);

        $itemFound = false;
        foreach($result as $aVoid){
            $this->assertSame($void->getParentId(), $aVoid->getParentId());

            if($void->getId() == $aVoid->getId()){
                $itemFound = true;
            }
        }

        $this->assertTrue($itemFound);
    }
}
