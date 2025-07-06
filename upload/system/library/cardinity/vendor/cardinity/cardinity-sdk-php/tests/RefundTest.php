<?php
namespace Cardinity\Tests;

use Cardinity\Method\Refund;
use Cardinity\Method\Payment;

class RefundTest extends ClientTestCase
{
    /**
     * @return void
     */
    public function testResultObjectSerialization()
    {
        $refund = new Refund\Refund();
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

        $payment = new Refund\Refund();
        $payment->unserialize($json);

        $this->assertSame('foo', $payment->getId());
        $this->assertSame('bar', $payment->getType());
        $this->assertSame(null, $payment->getCurrency());
        $this->assertSame(55.00, $payment->getAmount());
    }

    /**
     * @return Cardinity\Method\ResultObject $result
     */
    public function testCreatePayment()
    {
        $params = $this->getPaymentParams();
        $params['settle'] = true;

        $method = new Payment\Create($params);
        $payment = $this->client->call($method);

        return $payment;
    }

    /**
     * @depends testCreatePayment
     * @param Payment\Payment
     * @return void
     */
    public function testCreateFail(Payment\Payment $payment)
    {
        $method = new Refund\Create(
            $payment->getId(),
            10.00,
            'fail'
        );
        $this->expectException(\Cardinity\Exception\Declined::class);
        $this->client->call($method);
    }

    /**
     * @depends testCreatePayment
     * @param Payment\Payment
     * @return Cardinity\Method\ResultObject $result
     */
    public function testCreate(Payment\Payment $payment)
    {
        $method = new Refund\Create(
            $payment->getId(),
            10.00,
            'my description'
        );
        $result = $this->client->call($method);

        $this->assertInstanceOf('Cardinity\Method\Refund\Refund', $result);
        $this->assertSame('10.00', $result->getAmount());
        $this->assertSame(true, $result->isApproved());

        return $result;
    }

    /**
     * @depends testCreate
     * @param Refund\Refund
     * @return void
     */
    public function testGet(Refund\Refund $refund)
    {
        $method = new Refund\Get(
            $refund->getParentId(),
            $refund->getId()
        );
        $result = $this->client->call($method);

        $this->assertInstanceOf('Cardinity\Method\Refund\Refund', $result);
        $this->assertSame($refund->getParentId(), $result->getParentId());
        $this->assertSame('refund', $result->getType());
        $this->assertSame('10.00', $result->getAmount());
        $this->assertSame('my description', $result->getDescription());
    }

    /**
     * @depends testCreate
     * @param Refund\Refund
     * @return void
     */
    public function testGetAll(Refund\Refund $refund)
    {
        $method = new Refund\GetAll(
            $refund->getParentId()
        );
        $result = $this->client->call($method);

        $this->assertIsArray($result);
        $this->assertInstanceOf('Cardinity\Method\Refund\Refund', $result[0]);

        $itemFound = false;
        foreach($result as $aRefund){
            $this->assertSame($refund->getParentId(), $aRefund->getParentId());
            if($aRefund->getId() == $refund->getId()){
                $itemFound = true;
            }
        }

        $this->assertTrue($itemFound);
    }
}
