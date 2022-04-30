<?php
namespace Test\Unit;

require_once dirname(__DIR__) . '/Setup.php';

use Test\Setup;
use Braintree;

class TransactionTest extends Setup
{
    public function testGet_givesErrorIfInvalidProperty()
    {
        $t = Braintree\Transaction::factory([
            'creditCard' => ['expirationMonth' => '05', 'expirationYear' => '2010', 'bin' => '510510', 'last4' => '5100'],
            'customer' => [],
            'billing' => [],
            'descriptor' => [],
            'shipping' => [],
            'subscription' => ['billingPeriodStartDate' => '1983-07-12'],
            'statusHistory' => []
        ]);
        $this->setExpectedException('PHPUnit_Framework_Error', 'Undefined property on Braintree\Transaction: foo');
        $t->foo;
    }

    public function testCloneTransaction_RaisesErrorOnInvalidProperty()
    {
        $this->setExpectedException('InvalidArgumentException');
        Braintree\Transaction::cloneTransaction('an id', ['amount' => '123.45', 'invalidProperty' => 'foo']);
    }

    public function testErrorsWhenFindWithBlankString()
    {
        $this->setExpectedException('InvalidArgumentException');
        Braintree\Transaction::find('');
    }

    public function testErrorsWhenFindWithWhitespaceString()
    {
        $this->setExpectedException('InvalidArgumentException');
        Braintree\Transaction::find('\t');
    }

    public function testInitializationWithoutArguments()
    {
        $transaction = Braintree\Transaction::factory([]);
        $this->assertTrue($transaction instanceof Braintree\Transaction);
    }

    public function testSaleWithSkipAdvancedFraudCheckingValueAsTrue()
    {
        $transactionGateway = $this->mockTransactionGatewayDoCreate();
        $transactionGateway
            ->expects($this->once())
            ->method('_doCreate')
            ->will($this->returnCallback(function($path, $params) {
                $this->assertTrue($params["transaction"]["options"]["skipAdvancedFraudChecking"]);
            }));
        $transactionGateway->sale([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'creditCard' => [
                'number' => Braintree\Test\CreditCardNumbers::$visa,
                'expirationDate' => '05/2009',
            ],
            'options' => [
                'skipAdvancedFraudChecking' => true
            ]
        ]);
    }

    public function testSaleWithSkipAdvancedFraudCheckingValueAsFalse()
    {
        $transactionGateway = $this->mockTransactionGatewayDoCreate();
        $transactionGateway
            ->expects($this->once())
            ->method('_doCreate')
            ->will($this->returnCallback(function($path, $params) {
                $this->assertFalse($params["transaction"]["options"]["skipAdvancedFraudChecking"]);
            }));
        $transactionGateway->sale([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'creditCard' => [
                'number' => Braintree\Test\CreditCardNumbers::$visa,
                'expirationDate' => '05/2009',
            ],
            'options' => [
                'skipAdvancedFraudChecking' => false
            ]
        ]);
    }

    public function testSaleWithoutSkipAdvancedFraudCheckingOption()
    {
        $transactionGateway = $this->mockTransactionGatewayDoCreate();
        $transactionGateway
            ->expects($this->once())
            ->method('_doCreate')
            ->will($this->returnCallback(function($path, $params) {
                $this->assertArrayNotHasKey("skipAdvancedFraudChecking", $params["transaction"]["options"]);
            }));
        $transactionGateway->sale([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'creditCard' => [
                'number' => Braintree\Test\CreditCardNumbers::$visa,
                'expirationDate' => '05/2009',
            ],
            'options' => [
                'submitForSettlement' => true
            ]
        ]);
    }

    private function mockTransactionGatewayDoCreate()
    {
        return $this->getMockBuilder('Braintree\TransactionGateway')
            ->setConstructorArgs(array(Braintree\Configuration::gateway()))
            ->setMethods(array('_doCreate'))
            ->getMock();
    }
}
