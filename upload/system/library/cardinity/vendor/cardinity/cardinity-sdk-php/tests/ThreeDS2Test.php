<?php

namespace Cardinity\Tests;

use Cardinity\Exception;
use Cardinity\Method\Payment;
use Cardinity\Method\ResultObject;


class ThreeDS2Test extends ClientTestCase
{
    public function optionalBrowserInfoProvider()
    {
        return [
            [['ip_address' => '0.0.0.0']],
            [['javascript_enabled' => false]],
            [['javascript_enabled' => true]],
            [['java_enabled' => false]],
            [['java_enabled' => true]],
            [['ip_address' => '0.0.0.0', 'javascript_enabled' => true]],
            [['ip_address' => '0.0.0.0', 'java_enabled' => true]],
            [['ip_address' => '0.0.0.0', 'java_enabled' => false, 'javascript_enabled' => true]],
            [['java_enabled' => false, 'javascript_enabled' => true]],
        ];
    }


    public function optionalAddressProvider()
    {
        return [
            [['address_line2' => 'address line 2']],
            [['address_line3' => 'address line 3']],
            [['state' => 'vilnius']],
            [['address_line2' => 'address line 2', 'state' => 'vilnius']],
        ];
    }

    public function testClientCallSuccess()
    {

        $threeDS2Data = $this->getThreeDS2Data();

        $method = new Payment\Create([
            'amount' => 59.01,
            'currency' => 'EUR',
            'settle' => true,
            'description' => '3ds2-Testing-for-pass',
            'order_id' => 'orderid123',
            'country' => 'LT',
            'payment_method' => Payment\Create::CARD,
            'payment_instrument' => [
                'pan' => '5454545454545454',
                'exp_year' => date('Y') + 4,
                'exp_month' => 12,
                'cvc' => '456',
                'holder' => 'Shb Mike Dough'
            ],
            'threeds2_data' => $threeDS2Data
        ]);

        try {
            $payment = $this->client->call($method);
            $this->assertEquals('pending', $payment->getStatus());
        } catch (Exception\Declined $exception) {
            $payment = $exception->getResult();
            $errors = $exception->getErrors();
        } catch (Exception\ValidationFailed $exception) {
            $payment = $exception->getResult();
            $errors = $exception->getErrors();
        } catch (Exception\InvalidAttributeValue $exception) {
            $errors = $exception->getViolations();
        }
        if (isset($errors)) {
            $this->assertContains('[threeds2_data][notification_url]',$errors);
        }
    }
}
