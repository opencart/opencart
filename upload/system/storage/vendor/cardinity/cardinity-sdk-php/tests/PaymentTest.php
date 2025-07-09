<?php

namespace Cardinity\Tests;

use Cardinity\Exception;
use Cardinity\Method\Payment;
use Cardinity\Method\ResultObject;

class PaymentTest extends ClientTestCase
{
    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->paymentParams = $this->get3ds2PaymentParams();
        parent::setUp();
    }

    /**
     * @return void
     */
    public function testResultObjectSerialization()
    {
        $payment = $this->getPayment();

        $card = $this->getCard();
        $payment->setPaymentInstrument($card);

        $info = new Payment\AuthorizationInformation();
        $info->setUrl('http://...');
        $info->setData('some_data');
        $payment->setAuthorizationInformation($info);

        $this->assertSame(
            '{"id":"foo","amount":"55.00","type":"bar","payment_method":"card","payment_instrument":{"card_brand":"Visa","pan":"4447","exp_year":'.(date('Y')+4).',"exp_month":11,"holder":"James Bond"},"authorization_information":{"url":"http:\/\/...","data":"some_data"}}',
            $payment->serialize()
        );
    }

    /**
     * @return void
     */
    public function testResultObjectUnserialization()
    {
        $json = '{"id":"foo","amount":"55.00","type":"bar","payment_method":"card","payment_instrument":{"card_brand":"Visa","pan":"4447","exp_year":'.(date('Y')+4).',"exp_month":11,"holder":"James Bond"},"authorization_information":{"url":"http:\/\/...","data":"some_data"}}';

        $payment = new Payment\Payment();
        $payment->unserialize($json);

        $this->assertSame('foo', $payment->getId());
        $this->assertSame('bar', $payment->getType());
        $this->assertSame(null, $payment->getCurrency());
        $this->assertSame(55.00, $payment->getAmount());
        $this->assertInstanceOf('Cardinity\Method\Payment\AuthorizationInformation', $payment->getAuthorizationInformation());
        $this->assertSame('http://...', $payment->getAuthorizationInformation()->getUrl());
        $this->assertInstanceOf('Cardinity\Method\Payment\PaymentInstrumentCard', $payment->getPaymentInstrument());
        $this->assertSame('James Bond', $payment->getPaymentInstrument()->getHolder());
    }

    /**
     * @dataProvider invalidAmountValuesData
     * @param mixed $amount
     * @return void
     */
    public function testAmountValidationConstraint($amount)
    {
        $this->paymentParams['amount'] = $amount;
        $method = new Payment\Create($this->paymentParams);

        $this->expectException(\Cardinity\Exception\InvalidAttributeValue::class);
        $this->client->call($method);
    }

    /**
     * @return array
     */
    public function invalidAmountValuesData()
    {
        return [
            ['11.01234'],
            ['a23.24'],
        ];
    }

    /**
     * @return void
     */
    public function testMissingRequiredAttribute()
    {
        $params = $this->getPaymentParams();
        unset($params['currency']);
        $method = new Payment\Create($params);
        $this->expectException(\Cardinity\Exception\InvalidAttributeValue::class);
        $this->client->call($method);
    }

    /**
     * Invalid data. Check error message.
     * @return void
     */
    public function testCreateFailPanValidation()
    {
        $this->paymentParams['payment_instrument']['pan'] = '4242424242424241';
        $method = new Payment\Create($this->paymentParams);
        $this->expectException(\Cardinity\Exception\InvalidAttributeValue::class);
        $this->client->call($method);
    }

    /**
     * Invalid data. Generic handling.
     * @return void
     */
    public function testCreateFailMonthValidation()
    {
        $this->paymentParams['payment_instrument']['exp_month'] = 13;
        $method = new Payment\Create($this->paymentParams);
        $this->expectException(\Cardinity\Exception\ValidationFailed::class);
        $this->client->call($method);
    }

    /**
     * @return ResultObject
     */
    public function testCreate()
    {
        $method = new Payment\Create($this->paymentParams);
        $result = $this->client->call($method);

        $this->assertInstanceOf('Cardinity\Method\Payment\Payment', $result);
        $this->assertSame('approved', $result->getStatus());
        $this->assertSame(true, $result->isApproved());

        return $result;
    }

    /**
     * @depends testCreate
     * @param Payment\Payment
     * @return ResultObject
     */
    public function testCreateRecurring(Payment\Payment $payment)
    {
        $params = $this->getPaymentParams();
        $params['payment_method'] = Payment\Create::RECURRING;
        $params['payment_instrument'] = [
            'payment_id' => $payment->getId()
        ];

        $method = new Payment\Create($params);
        $result = $this->client->call($method);

        $this->assertInstanceOf('Cardinity\Method\Payment\Payment', $result);
        $this->assertSame('approved', $result->getStatus());
        $this->assertSame(true, $result->isApproved());

        return $result;
    }

    /**
     * @depends testCreate
     * @param Payment\Payment
     * @return void
     */
    public function testGet(Payment\Payment $payment)
    {
        $method = new Payment\Get($payment->getId());
        $result = $this->client->call($method);

        $this->assertInstanceOf('Cardinity\Method\Payment\Payment', $result);
        $this->assertSame('approved', $result->getStatus());
        $this->assertSame(true, $result->isApproved());
    }

    /**
     * @return void
     */
    public function testGetAll()
    {
        $method = new Payment\GetAll(5);
        $result = $this->client->call($method);

        $this->assertCount(5, $result);
        $this->assertInstanceOf('Cardinity\Method\Payment\Payment', $result[0]);
    }

    /**
     * @return ResultObject
     */
    public function testCreate3dFail()
    {
        $this->paymentParams['payment_instrument']['pan'] = '4200000000000000';

        $method = new Payment\Create($this->paymentParams);
        $result = $this->client->call($method);

        $this->assertInstanceOf('Cardinity\Method\Payment\Payment', $result);
        $this->assertSame('pending', $result->getStatus());
        $this->assertSame(true, $result->isPending());
        $this->assertInstanceOf('Cardinity\Method\Payment\ThreeDS2AuthorizationInformation', $result->getThreeds2Data());

        return $result;
    }


    /**
     * @return ResultObject
     */
    public function testCreate3dPass()
    {
        $params = $this->paymentParams;
        $params['payment_instrument']['pan'] = '5454545454545454';

        $method = new Payment\Create($params);
        $result = $this->client->call($method);

        $this->assertInstanceOf('Cardinity\Method\Payment\Payment', $result);
        $this->assertSame('pending', $result->getStatus());

        return $result;
    }

    /**
     * @depends testCreate3dPass
     * @param Payment\Payment
     * @return void
     */
    public function testFinalizePaymentPass(Payment\Payment $payment)
    {
        $paymentId = $payment->getId();
        $creq = $payment->getThreeds2data()->getCreq();

        $method = new Payment\Finalize($paymentId, $creq, true);
        $result = $this->client->call($method);

        $this->assertInstanceOf('Cardinity\Method\Payment\Payment', $result);
        $this->assertSame('approved', $result->getStatus());
        $this->assertSame(true, $result->isApproved());
    }

    public function testDeclinedWithMerchantAdviceCode()
    {
        $newPaymentParams = $this->paymentParams;

        $newPaymentParams['payment_instrument']['pan'] = '5454545454540018';
        $newPaymentParams['amount'] = 150.23;

        $method = new Payment\Create($newPaymentParams);

        try {
            $result = $this->client->call($method);
        } catch (\Cardinity\Exception\Declined $e){
            $result = $e->getResult();
            $this->assertInstanceOf('Cardinity\Method\Payment\Payment', $result);
            $this->assertSame('declined', $result->getStatus());
            $this->assertSame("03: Do not try again", $result->getMerchantAdviceCode());
        }

        return $result;
    }

    public function test3dsStatusReason()
    {
        $newPaymentParams = $this->paymentParams;

        $newPaymentParams['payment_instrument']['pan'] = '5454545454540109';
        $newPaymentParams['amount'] = 150.23;

        $method = new Payment\Create($newPaymentParams);

        try {
            $result = $this->client->call($method);
        } catch (\Cardinity\Exception\Declined $e){
            $result = $e->getResult();
            $this->assertInstanceOf('Cardinity\Method\Payment\Payment', $result);
            $this->assertSame('declined', $result->getStatus());
            $this->assertSame('01: Card authentication failed', $result->getThreedsStatusReason());
        }

        return $result;
    }


    /**
     * @return ResultObject
     */
    public function testCreateZeroAmountPayment()
    {
        $params = $this->paymentParams;
        $params['amount'] = 0.00;
        $params['settle'] = false;
        $params['payment_instrument']['pan'] = '5555555555554444';

        $method = new Payment\Create($params);
        $result = $this->client->call($method);

        $this->assertInstanceOf('Cardinity\Method\Payment\Payment', $result);
        $this->assertSame(true, $result->isApproved());

        return $result;
    }
}
