<?php
namespace Test\Unit;

require_once dirname(__DIR__) . '/Setup.php';

use Test\Setup;
use Braintree;

class PaymentMethodTest extends Setup
{
    public function testCreate_throwsIfInvalidKey()
    {
        $this->setExpectedException('InvalidArgumentException', 'invalid keys: invalidKey');
        Braintree\PaymentMethod::create(['invalidKey' => 'foo']);
    }

    public function testCreateSignature()
    {
        $expected = [
            'billingAddressId',
            'cardholderName',
            'cvv',
            'deviceData',
            'expirationDate',
            'expirationMonth',
            'expirationYear',
            'number',
            'paymentMethodNonce',
            'token',
            ['options' => [
                'failOnDuplicatePaymentMethod',
                'makeDefault',
                'verificationMerchantAccountId',
                'verifyCard',
                'verificationAccountType',
                'verificationAmount',
                'usBankAccountVerificationMethod',
                ['paypal' => [
                    'payee_email',
                    'payeeEmail',
                    'order_id',
                    'orderId',
                    'custom_field',
                    'customField',
                    'description',
                    'amount',
                    ['shipping' =>
                        [
                            'firstName', 'lastName', 'company', 'countryName',
                            'countryCodeAlpha2', 'countryCodeAlpha3', 'countryCodeNumeric',
                            'extendedAddress', 'locality', 'postalCode', 'region',
                            'streetAddress'],
                    ],
                ]],
            ]],
            ['billingAddress' => Braintree\AddressGateway::createSignature()],
            'customerId',
            'paypalRefreshToken',
            'paypalVaultWithoutUpgrade'
        ];
        $this->assertEquals($expected, Braintree\PaymentMethodGateway::createSignature());
    }

    public function testErrorsOnFindWithBlankArgument()
    {
        $this->setExpectedException('InvalidArgumentException');
        Braintree\PaymentMethod::find('');
    }

    public function testErrorsOnFindWithWhitespaceArgument()
    {
        $this->setExpectedException('InvalidArgumentException');
        Braintree\PaymentMethod::find('  ');
    }

    public function testErrorsOnFindWithWhitespaceCharacterArgument()
    {
        $this->setExpectedException('InvalidArgumentException');
        Braintree\PaymentMethod::find('\t');
    }

    public function testDeleteWithRevokeAllGrantsAsTrue()
    {
        $paymentMethodGateway = $this->mockPaymentMethodGatewayDoDelete();
        $expectedURL = "/payment_methods/any/some_token?revoke_all_grants=1";
        $paymentMethodGateway->expects($this->once())->method('_doDelete')->with($this->equalTo($expectedURL));
        $paymentMethodGateway->delete("some_token", ['revokeAllGrants' => true]);
    }

    public function testDeleteWithRevokeAllGrantsAsFalse()
    {
        $paymentMethodGateway = $this->mockPaymentMethodGatewayDoDelete();
        $expectedURL = "/payment_methods/any/some_token?revoke_all_grants=0";
        $paymentMethodGateway->expects($this->once())->method('_doDelete')->with($this->equalTo($expectedURL));
        $paymentMethodGateway->delete("some_token", ['revokeAllGrants' => false]);
    }

    public function testDeleteWithoutRevokeAllGrantsOption()
    {
        $paymentMethodGateway = $this->mockPaymentMethodGatewayDoDelete();
        $expectedURL = "/payment_methods/any/some_token";
        $paymentMethodGateway->expects($this->once())->method('_doDelete')->with($this->equalTo($expectedURL));
        $paymentMethodGateway->delete("some_token");
    }

    public function testDeleteWithInvalidOption()
    {
        $paymentMethodGateway = $this->mockPaymentMethodGatewayDoDelete();
        $this->setExpectedException('InvalidArgumentException');
        $paymentMethodGateway->expects($this->never())->method('_doDelete');
        $paymentMethodGateway->delete("some_token", ['invalidKey' => false]);
    }

    private function mockPaymentMethodGatewayDoDelete()
    {
        return $this->getMockBuilder('Braintree\PaymentMethodGateway')
            ->setConstructorArgs(array(Braintree\Configuration::gateway()))
            ->setMethods(array('_doDelete'))
            ->getMock();
    }
}
