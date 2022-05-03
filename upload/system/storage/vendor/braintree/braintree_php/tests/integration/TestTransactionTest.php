<?php
namespace Test\Integration;

require_once dirname(__DIR__) . '/Setup.php';

use DateTime;
use Test;
use Test\Setup;
use Braintree;

class TestTransactionTest extends Setup
{
    public function setUp()
    {
        Braintree\Configuration::environment('development');
    }

    /**
     * @after
     */
    public function tearDownResetBraintreeEnvironment()
    {
        Braintree\Configuration::environment('development');
    }

    /**
     * @expectedException Exception\TestOperationPerformedInProduction
     */
    public function testThrowingExceptionWhenProduction()
    {
        Braintree\Configuration::environment('production');

        $this->setExpectedException('Braintree\Exception\TestOperationPerformedInProduction');

        $transaction = Braintree\Test\Transaction::settle('foo');
    }

    public function testSettle()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ],
            'options' => ['submitForSettlement' => true]
        ]);

        $transaction = Braintree\Test\Transaction::settle($transaction->id);

        $this->assertEquals('settled', $transaction->status);
    }

    public function testSettlementConfirmed()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ],
            'options' => ['submitForSettlement' => true]
        ]);

        $transaction = Braintree\Test\Transaction::settlementConfirm($transaction->id);

        $this->assertEquals('settlement_confirmed', $transaction->status);
    }

    public function testSettlementDeclined()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ],
            'options' => ['submitForSettlement' => true]
        ]);

        $transaction = Braintree\Test\Transaction::settlementDecline($transaction->id);

        $this->assertEquals('settlement_declined', $transaction->status);
    }

    public function testSettlementPending()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ],
            'options' => ['submitForSettlement' => true]
        ]);

        $transaction = Braintree\Test\Transaction::settlementPending($transaction->id);

        $this->assertEquals('settlement_pending', $transaction->status);
    }
}
