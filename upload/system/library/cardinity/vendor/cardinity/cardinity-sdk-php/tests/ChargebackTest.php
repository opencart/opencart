<?php

namespace Cardinity\Tests;

use Cardinity\Exception;
use Cardinity\Method\Chargeback;
use Cardinity\Method\Payment;
use Cardinity\Method\ResultObject;

class ChargebackTest extends ClientTestCase
{
    /**
     * @return void
     */
    public $chargebackId;
    public $paymentId;

    public function setUp(): void
    {
        $this->chargebackId = "";
        $this->paymentId = "";

        parent::setUp();
    }


    /**
     * @return void
     */
    public function testChargebackResultObjectSerialization()
    {
        $chargeback  = new Chargeback\Chargeback();
        $chargeback->setId('foo');
        $chargeback->setAmount('55.00');
        $chargeback->setCurrency('USD');
        $chargeback->setType('chargeback');
        $chargeback->setCreated('baar');
        $chargeback->setLive(true);
        $chargeback->setParentId('lorem');
        $chargeback->setStatus('approved');
        $chargeback->setReason('reason_code: reason_message');
        $chargeback->setDescription('bar');


        $this->assertSame(
            '{"id":"foo","amount":"55.00","currency":"USD","type":"chargeback","created":"baar","live":true,"parent_id":"lorem","status":"approved","reason":"reason_code: reason_message","description":"bar"}',
            $chargeback->serialize()
        );
    }

    /**
     * @return void
     */
    public function testChargebackResultObjectUnserialization()
    {
        $json = '{"id":"foo","amount":"55.00","currency":"USD","type":"chargeback","created":"baar","live":true,"parent_id":"lorem","status":"approved","reason":"reason_code: reason_message","description":"bar"}';

        $chargeback  = new Chargeback\Chargeback();
        $chargeback->unserialize($json);

        $this->assertSame('foo', $chargeback->getId());
        $this->assertSame('bar', $chargeback->getDescription());
        $this->assertSame('USD', $chargeback->getCurrency());
        $this->assertSame(true, $chargeback->getLive());
        $this->assertSame('reason_code: reason_message', $chargeback->getReason());
        $this->assertSame(55.00, $chargeback->getAmount());

    }


    /**
     * Get ALl chargeback
     * @return void
     */
    public function testGetAllChargebacks()
    {

        $method = new Chargeback\GetAll();
        $chargebacks = $this->client->call($method);
        $this->assertIsArray($chargebacks);

        $this->assertInstanceOf('Cardinity\Method\Chargeback\Chargeback', $chargebacks[0]);

        return $chargebacks[0];
    }

    /**
     * Get chargeback by parent payment id
     *
     * @depends testGetAllChargebacks
     * @param Chargeback\Chargeback
     * @return void
     */
    public function testGetAllChargebackForPayment(Chargeback\Chargeback $chargeback)
    {
        $method = new Chargeback\GetAll(10, $chargeback->getParentId());
        $result = $this->client->call($method);
        $this->assertIsArray($result);

        $this->assertInstanceOf('Cardinity\Method\Chargeback\Chargeback', $result[0]);

    }

    /**
     * Get Specific Chargeback by parent payment id and chargeback id
     *
     * @depends testGetAllChargebacks
     * @param Chargeback\Chargeback
     * @return void
     */ function testGetSpecificChargeback(Chargeback\Chargeback $chargeback)
    {
        $test_cb_id = $chargeback->getId();
        $test_parent_id = $chargeback->getParentId();

        $method = new Chargeback\Get($test_parent_id, $test_cb_id);
        $result = $this->client->call($method);

        $this->assertInstanceOf('Cardinity\Method\Chargeback\Chargeback', $result);
        $this->assertSame($test_cb_id, $result->getId());
        $this->assertSame($test_parent_id, $result->getParentId());
        $this->assertSame('chargeback', $result->getType());
    }

}
