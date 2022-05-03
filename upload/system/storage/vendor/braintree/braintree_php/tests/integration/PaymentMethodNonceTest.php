<?php
namespace Test\Integration;

require_once dirname(__DIR__) . '/Setup.php';

use Test\Setup;
use Test\Helper;
use Braintree;

class PaymentMethodNonceTest extends Setup
{
    public function testCreate_fromPaymentMethodToken()
    {
        $customer = Braintree\Customer::createNoValidate();
        $card = Braintree\CreditCard::create([
            'customerId' => $customer->id,
            'cardholderName' => 'Cardholder',
            'number' => '5105105105105100',
            'expirationDate' => '05/12',
        ])->creditCard;

        $result = Braintree\PaymentMethodNonce::create($card->token);

        $this->assertTrue($result->success);
        $this->assertNotNull($result->paymentMethodNonce);
        $this->assertNotNull($result->paymentMethodNonce->nonce);
    }

    public function testCreate_fromNonExistentPaymentMethodToken()
    {
        $this->setExpectedException('Braintree\Exception\NotFound');
        Braintree\PaymentMethodNonce::create('not_a_token');
    }

    public function testFind_exposesVenmoDetails()
    {
        $foundNonce = Braintree\PaymentMethodNonce::find('fake-venmo-account-nonce');
        $details = $foundNonce->details;

        $this->assertEquals('99', $details['lastTwo']);
        $this->assertEquals('venmojoe', $details['username']);
        $this->assertEquals('Venmo-Joe-1', $details['venmoUserId']);
    }

    public function testFind_exposesThreeDSecureInfo()
    {
        $creditCard = [
            'creditCard' => [
                'number' => '4111111111111111',
                'expirationMonth' => '12',
                'expirationYear' => '2020'
            ]
        ];
        $nonce = Helper::generate3DSNonce($creditCard);
        $foundNonce = Braintree\PaymentMethodNonce::find($nonce);
        $info = $foundNonce->threeDSecureInfo;

        $this->assertEquals($nonce, $foundNonce->nonce);
        $this->assertEquals('CreditCard', $foundNonce->type);
        $this->assertEquals('Y', $info->enrolled);
        $this->assertEquals('authenticate_successful', $info->status);
        $this->assertTrue($info->liabilityShifted);
        $this->assertTrue($info->liabilityShiftPossible);
    }

    public function testFind_returnsBin()
    {
        $nonce = Braintree\PaymentMethodNonce::find(Braintree\Test\Nonces::$transactableVisa);
        $this->assertEquals("401288", $nonce->details["bin"]);
    }

    public function testFind_exposesBinData()
    {
        $nonce = Braintree\PaymentMethodNonce::find(Braintree\Test\Nonces::$transactableVisa);
        $this->assertEquals(Braintree\Test\Nonces::$transactableVisa, $nonce->nonce);
        $this->assertEquals('CreditCard', $nonce->type);
        $this->assertNotNull($nonce->binData);
        $binData = $nonce->binData;
        $this->assertEquals(Braintree\CreditCard::COMMERCIAL_UNKNOWN, $binData->commercial);
        $this->assertEquals(Braintree\CreditCard::DEBIT_UNKNOWN, $binData->debit);
        $this->assertEquals(Braintree\CreditCard::DURBIN_REGULATED_UNKNOWN, $binData->durbinRegulated);
        $this->assertEquals(Braintree\CreditCard::HEALTHCARE_UNKNOWN, $binData->healthcare);
        $this->assertEquals(Braintree\CreditCard::PAYROLL_UNKNOWN, $binData->payroll);
        $this->assertEquals(Braintree\CreditCard::PREPAID_NO, $binData->prepaid);
        $this->assertEquals("Unknown", $binData->issuingBank);
        $this->assertEquals("Unknown", $binData->productId);
    }

    public function testFind_returnsBinDataForCommercialNonce()
    {
        $nonce = Braintree\PaymentMethodNonce::find(Braintree\Test\Nonces::$transactableCommercial);
        $this->assertEquals(Braintree\Test\Nonces::$transactableCommercial, $nonce->nonce);
        $this->assertEquals('CreditCard', $nonce->type);
        $this->assertNotNull($nonce->binData);
        $this->assertEquals(Braintree\CreditCard::COMMERCIAL_YES, $nonce->binData->commercial);
    }

    public function testFind_returnsBinDataForDebitNonce()
    {
        $nonce = Braintree\PaymentMethodNonce::find(Braintree\Test\Nonces::$transactableDebit);
        $this->assertEquals(Braintree\Test\Nonces::$transactableDebit, $nonce->nonce);
        $this->assertEquals('CreditCard', $nonce->type);
        $this->assertNotNull($nonce->binData);
        $this->assertEquals(Braintree\CreditCard::DEBIT_YES, $nonce->binData->debit);
    }

    public function testFind_returnsBinDataForDurbinRegulatedNonce()
    {
        $nonce = Braintree\PaymentMethodNonce::find(Braintree\Test\Nonces::$transactableDurbinRegulated);
        $this->assertEquals(Braintree\Test\Nonces::$transactableDurbinRegulated, $nonce->nonce);
        $this->assertEquals('CreditCard', $nonce->type);
        $this->assertNotNull($nonce->binData);
        $this->assertEquals(Braintree\CreditCard::DURBIN_REGULATED_YES, $nonce->binData->durbinRegulated);
    }

    public function testFind_returnsBinDataForHealthcareNonce()
    {
        $nonce = Braintree\PaymentMethodNonce::find(Braintree\Test\Nonces::$transactableHealthcare);
        $this->assertEquals(Braintree\Test\Nonces::$transactableHealthcare, $nonce->nonce);
        $this->assertEquals('CreditCard', $nonce->type);
        $this->assertNotNull($nonce->binData);
        $this->assertEquals(Braintree\CreditCard::HEALTHCARE_YES, $nonce->binData->healthcare);
    }

    public function testFind_returnsBinDataForPayrollNonce()
    {
        $nonce = Braintree\PaymentMethodNonce::find(Braintree\Test\Nonces::$transactablePayroll);
        $this->assertEquals(Braintree\Test\Nonces::$transactablePayroll, $nonce->nonce);
        $this->assertEquals('CreditCard', $nonce->type);
        $this->assertNotNull($nonce->binData);
        $this->assertEquals(Braintree\CreditCard::PAYROLL_YES, $nonce->binData->payroll);
    }

    public function testFind_returnsBinDataForPrepaidNonce()
    {
        $nonce = Braintree\PaymentMethodNonce::find(Braintree\Test\Nonces::$transactablePrepaid);
        $this->assertEquals(Braintree\Test\Nonces::$transactablePrepaid, $nonce->nonce);
        $this->assertEquals('CreditCard', $nonce->type);
        $this->assertNotNull($nonce->binData);
        $this->assertEquals(Braintree\CreditCard::PREPAID_YES, $nonce->binData->prepaid);
    }

    public function testFind_returnsBinDataForCountryOfIssuanceNonce()
    {
        $nonce = Braintree\PaymentMethodNonce::find(Braintree\Test\Nonces::$transactableCountryOfIssuanceUSA);
        $this->assertEquals(Braintree\Test\Nonces::$transactableCountryOfIssuanceUSA, $nonce->nonce);
        $this->assertEquals('CreditCard', $nonce->type);
        $this->assertNotNull($nonce->binData);
        $this->assertEquals("USA", $nonce->binData->countryOfIssuance);
    }

    public function testFind_returnsBinDataForIssuingBankNonce()
    {
        $nonce = Braintree\PaymentMethodNonce::find(Braintree\Test\Nonces::$transactableIssuingBankNetworkOnly);
        $this->assertEquals(Braintree\Test\Nonces::$transactableIssuingBankNetworkOnly, $nonce->nonce);
        $this->assertEquals('CreditCard', $nonce->type);
        $this->assertNotNull($nonce->binData);
        $this->assertEquals("NETWORK ONLY", $nonce->binData->issuingBank);
    }

    public function testFind_exposesNullThreeDSecureInfoIfNoneExists()
    {
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $nonce = $http->nonce_for_new_card([
            "creditCard" => [
                "number" => "4111111111111111",
                "expirationMonth" => "11",
                "expirationYear" => "2099"
            ]
        ]);

        $foundNonce = Braintree\PaymentMethodNonce::find($nonce);
        $info = $foundNonce->threeDSecureInfo;

        $this->assertEquals($nonce, $foundNonce->nonce);
        $this->assertNull($info);
    }

    public function testFind_nonExistantNonce()
    {
        $this->setExpectedException('Braintree\Exception\NotFound');
        Braintree\PaymentMethodNonce::find('not_a_nonce');
    }
}
