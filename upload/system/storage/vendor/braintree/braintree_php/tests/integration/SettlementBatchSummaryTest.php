<?php
namespace Test\Integration;

require_once dirname(__DIR__) . '/Setup.php';

use DateTime;
use Test;
use Test\Setup;
use Braintree;

class SettlementBatchSummaryTest extends Setup
{
    public function isMasterCard($record)
    {
        return $record['cardType'] == Braintree\CreditCard::MASTER_CARD;
    }

    public function testGenerate_returnsAnEmptyCollectionWhenThereIsNoData()
    {
        $result = Braintree\SettlementBatchSummary::generate('2000-01-01');

        $this->assertTrue($result->success);
        $this->assertEquals(0, count($result->settlementBatchSummary->records));
    }

    public function testGatewayGenerate_returnsAnEmptyCollectionWhenThereIsNoData()
    {
        $gateway = new Braintree\Gateway([
            'environment' => 'development',
            'merchantId' => 'integration_merchant_id',
            'publicKey' => 'integration_public_key',
            'privateKey' => 'integration_private_key'
        ]);
        $result = $gateway->settlementBatchSummary()->generate('2000-01-01');

        $this->assertTrue($result->success);
        $this->assertEquals(0, count($result->settlementBatchSummary->records));
    }

    public function testGenerate_returnsAnErrorIfTheDateCanNotBeParsed()
    {
        $result = Braintree\SettlementBatchSummary::generate('OMG NOT A DATE');

        $this->assertFalse($result->success);
        $errors = $result->errors->forKey('settlementBatchSummary')->onAttribute('settlementDate');
        $this->assertEquals(Braintree\Error\Codes::SETTLEMENT_BATCH_SUMMARY_SETTLEMENT_DATE_IS_INVALID, $errors[0]->code);
    }

    public function testGenerate_returnsTransactionsSettledOnAGivenDay()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ],
            'options' => ['submitForSettlement' => true]
        ]);
        Braintree\Test\Transaction::settle($transaction->id);

        $today = new Datetime;
        $result = Braintree\SettlementBatchSummary::generate(Test\Helper::nowInEastern());

        $this->assertTrue($result->success);
        $masterCardRecords = array_filter($result->settlementBatchSummary->records, 'self::isMasterCard');
        $masterCardKeys = array_keys($masterCardRecords);
        $masterCardIndex = $masterCardKeys[0];
        $this->assertTrue(count($masterCardRecords) > 0);
        $this->assertEquals(Braintree\CreditCard::MASTER_CARD, $masterCardRecords[$masterCardIndex]['cardType']);
    }

    public function testGenerate_canBeGroupedByACustomField()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '100.00',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ],
            'customFields' => [
                'store_me' => 'custom value'
            ],
            'options' => ['submitForSettlement' => true]
        ]);

        Braintree\Test\Transaction::settle($transaction->id);

        $today = new Datetime;
        $result = Braintree\SettlementBatchSummary::generate(Test\Helper::nowInEastern(), 'store_me');

        $this->assertTrue($result->success);
        $this->assertTrue(count($result->settlementBatchSummary->records) > 0);
        $this->assertArrayHasKey('store_me', $result->settlementBatchSummary->records[0]);
    }
}
