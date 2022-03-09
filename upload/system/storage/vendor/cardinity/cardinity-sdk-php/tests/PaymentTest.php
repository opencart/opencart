<?php
namespace Cardinity\Tests;

use Cardinity\Exception;
use Cardinity\Method\Payment;

class PaymentTest extends ClientTestCase
{
    public function testResultObjectSerialization()
    {
        $payment = new Payment\Payment();
        $payment->setId('foo');
        $payment->setType('bar');
        $payment->setCurrency(null);
        $payment->setAmount('55.00');
        $payment->setPaymentMethod(Payment\Create::CARD);

        $card = new Payment\PaymentInstrumentCard();
        $card->setCardBrand('Visa');
        $card->setPan('4447');
        $card->setExpYear(2017);
        $card->setExpMonth(5);
        $card->setHolder('John Smith');
        $payment->setPaymentInstrument($card);

        $info = new Payment\AuthorizationInformation();
        $info->setUrl('http://...');
        $info->setData('some_data');
        $payment->setAuthorizationInformation($info);

        $this->assertSame(
            '{"id":"foo","amount":"55.00","type":"bar","payment_method":"card","payment_instrument":{"card_brand":"Visa","pan":"4447","exp_year":2017,"exp_month":5,"holder":"John Smith"},"authorization_information":{"url":"http:\/\/...","data":"some_data"}}',
            $payment->serialize()
        );
    }

    public function testResultObjectUnserialization()
    {
        $json = '{"id":"foo","amount":"55.00","type":"bar","payment_method":"card","payment_instrument":{"card_brand":"Visa","pan":"4447","exp_year":2017,"exp_month":5,"holder":"John Smith"},"authorization_information":{"url":"http:\/\/...","data":"some_data"}}';

        $payment = new Payment\Payment();
        $payment->unserialize($json);

        $this->assertSame('foo', $payment->getId());
        $this->assertSame('bar', $payment->getType());
        $this->assertSame(null, $payment->getCurrency());
        $this->assertSame(55.00, $payment->getAmount());
        $this->assertInstanceOf('Cardinity\Method\Payment\AuthorizationInformation', $payment->getAuthorizationInformation());
        $this->assertSame('http://...', $payment->getAuthorizationInformation()->getUrl());
        $this->assertInstanceOf('Cardinity\Method\Payment\PaymentInstrumentCard', $payment->getPaymentInstrument());
        $this->assertSame('John Smith', $payment->getPaymentInstrument()->getHolder());
    }

    /**
     * @expectedException Cardinity\Exception\InvalidAttributeValue
     * @dataProvider invalidaAmountValuesData
     */
    public function testAmountValidationConstraint($amount)
    {
        $params = $this->getPaymentParams();
        $params['amount'] = $amount;
        $method = new Payment\Create($params);
        $result = $this->client->call($method);
    }

    public function invalidaAmountValuesData()
    {
        return [
            ['150.01'],
            [150],
        ];
    }

    /**
     * @expectedException Cardinity\Exception\InvalidAttributeValue
     */
    public function testMissingRequiredAttribute()
    {
        $params = $this->getPaymentParams();
        unset($params['currency']);
        $method = new Payment\Create($params);
        $result = $this->client->call($method);
    }

    /**
     * In order to simulate a failed payment:
     * status declined: Amount larger than 150.00 will trigger a declined payment.
     */
    public function testCreateDeclined()
    {
        $params = $this->getPaymentParams();
        $params['amount'] = 150.01;

        try {
            $method = new Payment\Create($params);
            $result = $this->client->call($method);
        } catch (Exception\Declined $e) {
            $result = $e->getResult();

            $this->assertInstanceOf('Cardinity\Method\Payment\Payment', $result);
            $this->assertSame('declined', $result->getStatus());
            $this->assertSame('CRD-TEST: Do Not Honor', $result->getError());
            $this->assertContains('status: CRD-TEST: Do Not Honor;', $e->getErrorsAsString());

            return;
        }

        $this->fail('An expected exception has not been raised.');
    }

    /**
     * Invalid data. Check error message.
     */
    public function testCreateFailPanValidation()
    {
        $params = $this->getPaymentParams();
        $params['payment_instrument']['pan'] = '4242424242424241';

        try {
            $method = new Payment\Create($params);
            $result = $this->client->callNoValidate($method);
        } catch (Exception\ValidationFailed $e) {
            $result = $e->getResult();

            $this->assertInstanceOf('Cardinity\Method\Error', $result);
            $this->assertSame('invalid credit card number.', $e->getErrors()[0]['message']);
            return;
        }

        $this->fail('An expected exception has not been raised.');
    }

    /**
     * Invalid data. Generic handling.
     * @expectedException Cardinity\Exception\ValidationFailed
     */
    public function testCreateFailMonthValidation()
    {
        $params = $this->getPaymentParams();
        $params['payment_instrument']['exp_month'] = 13;

        $method = new Payment\Create($params);
        $result = $this->client->call($method);
    }

    public function testCreate()
    {
        $params = $this->getPaymentParams();
        $method = new Payment\Create($params);
        $result = $this->client->call($method);

        $this->assertInstanceOf('Cardinity\Method\Payment\Payment', $result);
        $this->assertSame('approved', $result->getStatus());

        return $result;
    }

    /**
     * @depends testCreate
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

        return $result;
    }

    /**
     * @depends testCreate
     */
    public function testGet(Payment\Payment $payment)
    {
        $method = new Payment\Get($payment->getId());
        $result = $this->client->call($method);

        $this->assertInstanceOf('Cardinity\Method\Payment\Payment', $result);
        $this->assertSame('approved', $result->getStatus());
    }

    public function testGetAll()
    {
        $method = new Payment\GetAll(5);
        $result = $this->client->call($method);

        $this->assertCount(5, $result);
        $this->assertInstanceOf('Cardinity\Method\Payment\Payment', $result[0]);
    }

    public function testCreate3dFail()
    {
        $params = $this->getPaymentParams();
        $params['description'] = '3d-fail';

        $method = new Payment\Create($params);
        $result = $this->client->call($method);

        $this->assertInstanceOf('Cardinity\Method\Payment\Payment', $result);
        $this->assertSame('pending', $result->getStatus());
        $this->assertSame('3d-fail', $result->getAuthorizationInformation()->getData());

        return $result;
    }

    /**
     * @depends testCreate3dFail
     */
    public function testFinalizePaymentFail(Payment\Payment $payment)
    {
        $paymentId = $payment->getId();
        $authorizationInformation = $payment->getAuthorizationInformation()->getData();

        try {
            $method = new Payment\Finalize($paymentId, $authorizationInformation);
            $result = $this->client->call($method);
        } catch (Exception\Declined $e) {
            $result = $e->getResult();

            $this->assertInstanceOf('Cardinity\Method\Payment\Payment', $result);
            $this->assertSame('declined', $result->getStatus());
            $this->assertContains('status: 33333: 3D Secure Authorization Failed.;', $e->getErrorsAsString());

            return;
        }

        $this->fail('An expected exception has not been raised.');
    }

    public function testCreate3dPass()
    {
        $params = $this->getPaymentParams();
        $params['description'] = '3d-pass';

        $method = new Payment\Create($params);
        $result = $this->client->call($method);

        $this->assertInstanceOf('Cardinity\Method\Payment\Payment', $result);
        $this->assertSame('pending', $result->getStatus());
        $this->assertSame('3d-pass', $result->getAuthorizationInformation()->getData());

        return $result;
    }

    /**
     * @depends testCreate3dPass
     */
    public function testFinalizePaymentPass(Payment\Payment $payment)
    {
        $paymentId = $payment->getId();
        $authorizationInformation = $payment->getAuthorizationInformation()->getData();

        $method = new Payment\Finalize($paymentId, $authorizationInformation);
        $result = $this->client->call($method);

        $this->assertInstanceOf('Cardinity\Method\Payment\Payment', $result);
        $this->assertSame('approved', $result->getStatus());
    }
}
