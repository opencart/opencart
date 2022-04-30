<?php
namespace Test\Integration;

require_once dirname(__DIR__) . '/Setup.php';

use Test;
use Test\Setup;
use Braintree;

class TransparentRedirectTest extends Setup
{
    public function testRedirectUrl()
    {
        Test\Helper::suppressDeprecationWarnings();
        $trData = Braintree\TransparentRedirect::createCustomerData(
            ["redirectUrl" => "http://www.example.com?foo=bar"]
        );
        $config = Braintree\Configuration::$global;
        $queryString = Test\Helper::submitTrRequest(
            $config->baseUrl() . $config->merchantPath() . '/test/maintenance',
            [],
            $trData
        );
        $this->setExpectedException('Braintree\Exception\DownForMaintenance');
        Braintree\Customer::createFromTransparentRedirect($queryString);
    }

    public function testParseAndValidateQueryString_throwsDownForMaintenanceErrorIfDownForMaintenance()
    {
        Test\Helper::suppressDeprecationWarnings();
        $trData = Braintree\TransparentRedirect::createCustomerData(
            ["redirectUrl" => "http://www.example.com"]
        );
        $config = Braintree\Configuration::$global;
        $queryString = Test\Helper::submitTrRequest(
            $config->baseUrl() . $config->merchantPath() . '/test/maintenance',
            [],
            $trData
        );
        $this->setExpectedException('Braintree\Exception\DownForMaintenance');
        Braintree\Customer::createFromTransparentRedirect($queryString);
    }

    public function testParseAndValidateQueryString_throwsAuthenticationErrorIfBadCredentials()
    {
        Test\Helper::suppressDeprecationWarnings();
        $privateKey = Braintree\Configuration::privateKey();
        Braintree\Configuration::privateKey('incorrect');
        try {
            $trData = Braintree\TransparentRedirect::createCustomerData(
                ["redirectUrl" => "http://www.example.com"]
            );
            $queryString = Test\Helper::submitTrRequest(
                Braintree\Customer::createCustomerUrl(),
                [],
                $trData
            );
            $this->setExpectedException('Braintree\Exception\Authentication');
            Braintree\Customer::createFromTransparentRedirect($queryString);
        } catch (Braintree\Exception $e) {
        }
        $privateKey = Braintree\Configuration::privateKey($privateKey);
        if (isset($e)) throw $e;
    }

    public function testCreateTransactionFromTransparentRedirect()
    {
        $params = [
            'transaction' => [
                'customer' => [
                    'first_name' => 'First'
                ],
                'credit_card' => [
                    'number' => '5105105105105100',
                    'expiration_date' => '05/12'
                ]
            ]
        ];
        $trParams = [
            'transaction' => [
                'type' => Braintree\Transaction::SALE,
                'amount' => '100.00'
            ]
        ];

        $trData = Braintree\TransparentRedirect::transactionData(
            array_merge($trParams, ["redirectUrl" => "http://www.example.com"])
        );

        $queryString = Test\Helper::submitTrRequest(
            Braintree\TransparentRedirect::url(),
            $params,
            $trData
        );

        $result = Braintree\TransparentRedirect::confirm($queryString);
        $this->assertTrue($result->success);
        $this->assertEquals('100.00', $result->transaction->amount);
        $this->assertEquals(Braintree\Transaction::SALE, $result->transaction->type);
        $this->assertEquals(Braintree\Transaction::AUTHORIZED, $result->transaction->status);
        $creditCard = $result->transaction->creditCardDetails;
        $this->assertEquals('510510', $creditCard->bin);
        $this->assertEquals('5100', $creditCard->last4);
        $this->assertEquals('US', $creditCard->customerLocation);
        $this->assertEquals('MasterCard', $creditCard->cardType);
        $this->assertEquals('05/2012', $creditCard->expirationDate);
        $this->assertEquals('510510******5100', $creditCard->maskedNumber);
        $customer = $result->transaction->customerDetails;
        $this->assertequals('First', $customer->firstName);
    }

    public function testGatewayCreateTransactionFromTransparentRedirect()
    {
        $params = [
            'transaction' => [
                'customer' => [
                    'first_name' => 'First'
                ],
                'credit_card' => [
                    'number' => '5105105105105100',
                    'expiration_date' => '05/12'
                ]
            ]
        ];
        $trParams = [
            'transaction' => [
                'type' => Braintree\Transaction::SALE,
                'amount' => '100.00'
            ]
        ];

        $gateway = new Braintree\Gateway([
            'environment' => 'development',
            'merchantId' => 'integration_merchant_id',
            'publicKey' => 'integration_public_key',
            'privateKey' => 'integration_private_key'
        ]);
        $trData = $gateway->transparentRedirect()->transactionData(
            array_merge($trParams, ["redirectUrl" => "http://www.example.com"])
        );

        $queryString = Test\Helper::submitTrRequest(
            $gateway->transparentRedirect()->url(),
            $params,
            $trData
        );

        $result = $gateway->transparentRedirect()->confirm($queryString);
        $this->assertTrue($result->success);
        $this->assertEquals('100.00', $result->transaction->amount);
        $this->assertEquals(Braintree\Transaction::SALE, $result->transaction->type);
        $this->assertEquals(Braintree\Transaction::AUTHORIZED, $result->transaction->status);
        $creditCard = $result->transaction->creditCardDetails;
        $this->assertEquals('US', $creditCard->customerLocation);
        $this->assertEquals('05/2012', $creditCard->expirationDate);
        $this->assertEquals('510510******5100', $creditCard->maskedNumber);
        $customer = $result->transaction->customerDetails;
        $this->assertequals('First', $customer->firstName);
    }

    public function testCreateTransactionWithServiceFeesFromTransparentRedirect()
    {
        $params = [
            'transaction' => [
                'customer' => [
                    'first_name' => 'First'
                ],
                'credit_card' => [
                    'number' => '5105105105105100',
                    'expiration_date' => '05/12'
                ],
                'service_fee_amount' => '1.00',
                'merchant_account_id' => Test\Helper::nonDefaultSubMerchantAccountId(),
            ]
        ];
        $trParams = [
            'transaction' => [
                'type' => Braintree\Transaction::SALE,
                'amount' => '100.00'
            ]
        ];

        $trData = Braintree\TransparentRedirect::transactionData(
            array_merge($trParams, ["redirectUrl" => "http://www.example.com"])
        );

        $queryString = Test\Helper::submitTrRequest(
            Braintree\TransparentRedirect::url(),
            $params,
            $trData
        );

        $result = Braintree\TransparentRedirect::confirm($queryString);
        $this->assertTrue($result->success);
        $this->assertEquals('1.00', $result->transaction->serviceFeeAmount);
    }

    public function testCreateCustomerFromTransparentRedirect()
    {
        $params = [
            'customer' => [
                'first_name' => 'Second'
            ]
        ];
        $trParams = [
            'customer' => [
                'lastName' => 'Penultimate'
            ]
        ];

        $trData = Braintree\TransparentRedirect::createCustomerData(
            array_merge($trParams, ["redirectUrl" => "http://www.example.com"])
        );

        $queryString = Test\Helper::submitTrRequest(
            Braintree\TransparentRedirect::url(),
            $params,
            $trData
        );

        $result = Braintree\TransparentRedirect::confirm($queryString);
        $this->assertTrue($result->success);

        $customer = $result->customer;
        $this->assertequals('Second', $customer->firstName);
        $this->assertequals('Penultimate', $customer->lastName);
    }

    public function testUpdateCustomerFromTransparentRedirect()
    {
        $customer = Braintree\Customer::create([
            'firstName' => 'Mike',
            'lastName' => 'Jonez'
        ])->customer;
        $params = [
            'customer' => [
                'first_name' => 'Second'
            ]
        ];
        $trParams = [
            'customerId' => $customer->id,
            'customer' => [
                'lastName' => 'Penultimate'
            ]
        ];

        $trData = Braintree\TransparentRedirect::updateCustomerData(
            array_merge($trParams, ["redirectUrl" => "http://www.example.com"])
        );

        $queryString = Test\Helper::submitTrRequest(
            Braintree\TransparentRedirect::url(),
            $params,
            $trData
        );

        $result = Braintree\TransparentRedirect::confirm($queryString);
        $this->assertTrue($result->success);

        $customer = $result->customer;
        $this->assertequals('Second', $customer->firstName);
        $this->assertequals('Penultimate', $customer->lastName);
    }

    public function testCreateCreditCardFromTransparentRedirect()
    {
        $customer = Braintree\Customer::create([
            'firstName' => 'Mike',
            'lastName' => 'Jonez'
        ])->customer;

        $params = [
            'credit_card' => [
                'number' => Braintree\Test\CreditCardNumbers::$visa
            ]
        ];
        $trParams = [
            'creditCard' => [
                'customerId' => $customer->id,
                'expirationMonth' => '01',
                'expirationYear' => '10'
            ]
        ];

        $trData = Braintree\TransparentRedirect::createCreditCardData(
            array_merge($trParams, ["redirectUrl" => "http://www.example.com"])
        );

        $queryString = Test\Helper::submitTrRequest(
            Braintree\TransparentRedirect::url(),
            $params,
            $trData
        );

        $result = Braintree\TransparentRedirect::confirm($queryString);
        $this->assertTrue($result->success);

        $creditCard = $result->creditCard;
        $this->assertequals('401288', $creditCard->bin);
        $this->assertequals('1881', $creditCard->last4);
        $this->assertequals('01/2010', $creditCard->expirationDate);
    }

    public function testUpdateCreditCardFromTransparentRedirect()
    {
        $customer = Braintree\Customer::create([
            'firstName' => 'Mike',
            'lastName' => 'Jonez'
        ])->customer;
        $creditCard = Braintree\CreditCard::create([
            'customerId' => $customer->id,
            'number' => Braintree\Test\CreditCardNumbers::$masterCard,
            'expirationMonth' => '10',
            'expirationYear' => '10'
        ])->creditCard;

        $params = [
            'credit_card' => [
                'number' => Braintree\Test\CreditCardNumbers::$visa,
            ]
        ];
        $trParams = [
            'paymentMethodToken' => $creditCard->token,
            'creditCard' => [
                'expirationMonth' => '11',
                'expirationYear' => '11'
            ]
        ];

        $trData = Braintree\TransparentRedirect::updateCreditCardData(
            array_merge($trParams, ["redirectUrl" => "http://www.example.com"])
        );

        $queryString = Test\Helper::submitTrRequest(
            Braintree\TransparentRedirect::url(),
            $params,
            $trData
        );

        Braintree\TransparentRedirect::confirm($queryString);

        $creditCard = Braintree\CreditCard::find($creditCard->token);
        $this->assertequals('401288', $creditCard->bin);
        $this->assertequals('1881', $creditCard->last4);
        $this->assertequals('11/2011', $creditCard->expirationDate);
    }

    public function testUrl()
    {
        $url = Braintree\TransparentRedirect::url();
        $developmentPort = getenv("GATEWAY_PORT") ? getenv("GATEWAY_PORT") : 3000;
        $this->assertEquals("http://localhost:" . $developmentPort . "/merchants/integration_merchant_id/transparent_redirect_requests", $url);
    }
}
