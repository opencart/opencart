<?php
namespace Test\Integration;

require_once dirname(__DIR__) . '/Setup.php';

use DateTime;
use Test;
use Test\Setup;
use Braintree;

class DisputeSearchTest extends Setup
{
    public function testAdvancedSearch_noResults()
    {
        $collection = Braintree\Dispute::search([
            Braintree\DisputeSearch::id()->is('non_existent_dispute')
        ]);

        $disputes = $this->collectionToArray($collection);

        $this->assertEquals(0, count($disputes));
    }

    public function testAdvancedSearch_byId_returnsSingleDispute()
    {
        $collection = Braintree\Dispute::search([
            Braintree\DisputeSearch::id()->is('open_dispute')
        ]);

        $disputes = $this->collectionToArray($collection);

        $this->assertEquals(1, count($disputes));
    }

    public function testAdvancedSearch_byCustomerid_returnsDispute()
    {
        $customerId = Braintree\Customer::create()->customer->id;
        Braintree\Transaction::sale([
            'amount' => '10.00',
            'customerId' => $customerId,
            'creditCard' => [
                'number' => Braintree\Test\CreditCardNumbers::$disputes['Chargeback'],
                'expirationDate' => "12/20"
            ]
        ]);

        $collection = Braintree\Dispute::search([
            Braintree\DisputeSearch::customerId()->is($customerId)
        ]);

        $disputes = $this->collectionToArray($collection);

        $this->assertEquals(1, count($disputes));
    }

    public function testAdvancededSearch_byReason_returnsTwoDisputes()
    {
        $collection = Braintree\Dispute::search([
            Braintree\DisputeSearch::reason()->in([
                Braintree\Dispute::PRODUCT_UNSATISFACTORY,
                Braintree\Dispute::RETRIEVAL
            ])
        ]);

        $disputes = $this->collectionToArray($collection);

        $this->assertEquals(2, count($disputes));
    }

    public function testAdvancedSearch_byReceivedDateRange_returnsDispute()
    {
        $collection = Braintree\Dispute::search([
            Braintree\DisputeSearch::receivedDate()->between(
                "03/03/2014",
                "03/05/2014"
            )
        ]);

        $disputes = $this->collectionToArray($collection);

        $this->assertEquals(1, count($disputes));
        $this->assertEquals($disputes[0]->receivedDate, DateTime::createFromFormat('Ymd His', '20140304 000000'));
    }

    public function testAdvancedSearch_byDisbursementDateRange_returnsDispute()
    {
        $collection = Braintree\Dispute::search([
            Braintree\DisputeSearch::disbursementDate()->between(
                "03/03/2014",
                "03/05/2014"
            )
        ]);

        $disputes = $this->collectionToArray($collection);

        $this->assertEquals(1, count($disputes));
        $this->assertEquals($disputes[0]->statusHistory[0]->effectiveDate, DateTime::createFromFormat('Ymd His', '20140304 000000'));
    }

    public function testAdvancedSearch_byEffectiveDateRange_returnsDispute()
    {
        $collection = Braintree\Dispute::search([
            Braintree\DisputeSearch::disbursementDate()->between(
                "03/03/2014",
                "03/05/2014"
            )
        ]);

        $disputes = $this->collectionToArray($collection);

        $this->assertEquals(1, count($disputes));
        $this->assertEquals($disputes[0]->statusHistory[0]->disbursementDate, DateTime::createFromFormat('Ymd His', '20140305 000000'));
    }

    private function collectionToArray($collection) {
        $array = [];
        foreach($collection as $element) {
            array_push($array, $element);
        }
        return $array;
    }
}
