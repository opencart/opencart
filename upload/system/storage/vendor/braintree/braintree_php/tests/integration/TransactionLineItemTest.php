<?php
namespace Test\Integration;

require_once dirname(__DIR__) . '/Setup.php';

use Test;
use Test\Setup;
use Test\Braintree\CreditCardNumbers\CardTypeIndicators;
use Braintree;

class TransactionLineItemTest extends Setup
{
    public function testFindAll_returnsLineItems()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '35.05',
            'creditCard' => [
                'number' => Braintree\Test\CreditCardNumbers::$visa,
                'expirationDate' => '05/2009',
            ],
            'lineItems' => [[
                'quantity' => '1.0232',
                'name' => 'Name #1',
                'kind' => Braintree\TransactionLineItem::DEBIT,
                'unitAmount' => '45.1232',
                'totalAmount' => '45.15',
            ]]
        ]);

        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $lineItems = Braintree\TransactionLineItem::findAll($transaction->id);
        $this->assertEquals(1, sizeof($lineItems));

        $lineItem = $lineItems[0];
        $this->assertEquals('1.0232', $lineItem->quantity);
        $this->assertEquals('Name #1', $lineItem->name);
        $this->assertEquals(Braintree\TransactionLineItem::DEBIT, $lineItem->kind);
        $this->assertEquals('45.1232', $lineItem->unitAmount);
        $this->assertEquals('45.15', $lineItem->totalAmount);
    }
}
