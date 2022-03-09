<?php
namespace Test\Unit;

require_once dirname(__DIR__) . '/Setup.php';

use Test\Setup;
use Braintree;

class TransactionLineItemTest extends Setup
{
    public function testCREDIT_equalsDeprecatedCREDIT()
    {
        $this->assertEquals(
            Braintree\TransactionLineItem::CREDIT,
            Braintree\Transaction\LineItem::CREDIT
        );
    }

    public function testDEBIT_equalsDeprecatedDEBIT()
    {
        $this->assertEquals(
            Braintree\TransactionLineItem::DEBIT,
            Braintree\Transaction\LineItem::DEBIT
        );
    }
}
