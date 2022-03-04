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
        $this->paymentParams = $this->getPaymentParams();
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
            ['150.01'],
            [150],
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
        $this->paymentParams['description'] = '3d-fail';

        $method = new Payment\Create($this->paymentParams);
        $result = $this->client->call($method);

        $this->assertInstanceOf('Cardinity\Method\Payment\Payment', $result);
        $this->assertSame('pending', $result->getStatus());
        $this->assertSame(true, $result->isPending());
        $this->assertSame('3d-fail', $result->getAuthorizationInformation()->getData());

        return $result;
    }

    /**
     * @depends testCreate3dFail
     * @param Payment\Payment
     */
    public function testFinalizePaymentFail(Payment\Payment $payment)
    {
        $paymentId = $payment->getId();
        $authorizationInformation = $payment->getAuthorizationInformation()->getData();

        try {
            $method = new Payment\Finalize($paymentId, $authorizationInformation);
            $this->client->call($method);
        } catch (\Cardinity\Exception\Declined $e) {
            $result = $e->getResult();

            $this->assertInstanceOf('Cardinity\Method\Payment\Payment', $result);
            $this->assertSame('declined', $result->getStatus());
            $this->assertSame(true, $result->isDeclined());
            $this->assertStringContainsString('status: 33333: 3D Secure Authorization Failed.;', $e->getErrorsAsString());

            return;
        }

        $this->fail('An expected exception has not been raised.');
    }

    /**
     * @return ResultObject
     */
    public function testCreate3dPass()
    {
        $params = $this->getPaymentParams();
        $params['description'] = '3d-pass';

        $method = new Payment\Create($params);
        $result = $this->client->call($method);

        $this->assertInstanceOf('Cardinity\Method\Payment\Payment', $result);
        $this->assertSame('pending', $result->getStatus());
        $this->assertSame(true, $result->isPending());
        $this->assertSame('3d-pass', $result->getAuthorizationInformation()->getData());

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
        $authorizationInformation = $payment->getAuthorizationInformation()->getData();

        $method = new Payment\Finalize($paymentId, $authorizationInformation);
        $result = $this->client->call($method);

        $this->assertInstanceOf('Cardinity\Method\Payment\Payment', $result);
        $this->assertSame('approved', $result->getStatus());
        $this->assertSame(true, $result->isApproved());
    }
}
