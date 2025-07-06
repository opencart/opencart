<?php

namespace Cardinity\Tests;

use Cardinity\Method\Settlement;
use Cardinity\Method\Payment;

class SettlementTest extends ClientTestCase
{
    /**
     * @return void
     */
    public function testResultObjectSerialization()
    {
        $refund = new Settlement\Settlement();
        $refund->setId('foo');
        $refund->setType('bar');
        $refund->setCurrency(null);
        $refund->setAmount('55.00');

        $this->assertSame(
            '{"id":"foo","amount":"55.00","type":"bar"}',
            $refund->serialize()
        );
    }

    /**
     * @return void
     */
    public function testResultObjectUnserialization()
    {
        $json = '{"id":"foo","amount":"55.00","type":"bar"}';

        $payment = new Settlement\Settlement();
        $payment->unserialize($json);

        $this->assertSame('foo', $payment->getId());
        $this->assertSame('bar', $payment->getType());
        $this->assertSame(null, $payment->getCurrency());
        $this->assertSame(55.00, $payment->getAmount());
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
        $method = new Settlement\Create(
            $payment->getId(),
            10.00,
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
        $method = new Settlement\Create(
            $payment->getId(),
            10.00,
            'my description'
        );
        $result = $this->client->call($method);

        $this->assertInstanceOf('Cardinity\Method\Settlement\Settlement', $result);
        $this->assertSame('10.00', $result->getAmount());
        $this->assertSame(true, $result->isApproved());

        return $result;
    }

    /**
     * @depends testCreate
     * @param Settlement\Settlement $settlement
     * @return void
     */
    public function testGet(Settlement\Settlement $settlement)
    {
        $method = new Settlement\Get(
            $settlement->getParentId(),
            $settlement->getId()
        );
        $result = $this->client->call($method);

        $this->assertInstanceOf('Cardinity\Method\Settlement\Settlement', $result);
        $this->assertSame($settlement->getParentId(), $result->getParentId());
        $this->assertSame('10.00', $result->getAmount());
        $this->assertSame('settlement', $result->getType());
        $this->assertSame('my description', $result->getDescription());
    }

    /**
     * @depends testCreate
     * @param Settlement\Settlement $settlement
     * @return void
     */
    public function testGetAll(Settlement\Settlement $settlement)
    {
        $method = new Settlement\GetAll(
            $settlement->getParentId()
        );
        $result = $this->client->call($method);

        $this->assertInstanceOf('Cardinity\Method\Settlement\Settlement', $result[0]);

        $itemFound = false;
        foreach($result as $aSettlement){
            $this->assertSame($settlement->getParentId(), $aSettlement->getParentId());
            if($aSettlement->getId() == $settlement->getId()){
                $itemFound = true;
            }
        }

        $this->assertTrue($itemFound);
    }
}
