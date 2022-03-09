<?php
namespace Test\Integration;

require_once dirname(__DIR__) . '/Setup.php';

use Test;
use Test\Setup;
use Braintree;

class CustomerTest extends Setup
{
    public function testAll_smokeTest()
    {
        $all = Braintree\Customer::all();
        $this->assertTrue($all->maximumCount() > 0);
    }

    public function testAllWithManyResults()
    {
        $collection = Braintree\Customer::all();
        $this->assertTrue($collection->maximumCount() > 1);
        $customer = $collection->firstItem();

        $this->assertTrue(strlen($customer->id) > 0);
        $this->assertTrue($customer instanceof Braintree\Customer);
    }

    public function testCreate()
    {
        $result = Braintree\Customer::create([
            'firstName' => 'Mike',
            'lastName' => 'Jones',
            'company' => 'Jones Co.',
            'email' => 'mike.jones@example.com',
            'phone' => '419.555.1234',
            'fax' => '419.555.1235',
            'website' => 'http://example.com'
        ]);
        $this->assertEquals(true, $result->success);
        $customer = $result->customer;
        $this->assertEquals('Mike', $customer->firstName);
        $this->assertEquals('Jones', $customer->lastName);
        $this->assertEquals('Jones Co.', $customer->company);
        $this->assertEquals('mike.jones@example.com', $customer->email);
        $this->assertEquals('419.555.1234', $customer->phone);
        $this->assertEquals('419.555.1235', $customer->fax);
        $this->assertEquals('http://example.com', $customer->website);
        $this->assertNotNull($customer->merchantId);
    }

    public function testCreateWithIdOfZero()
    {
        $result = Braintree\Customer::create([
            'id' => '0'
        ]);

        $this->assertEquals(true, $result->success);
        $this->assertEquals($result->customer->id, '0');
        $customer = Braintree\Customer::find('0');

        $this->assertEquals('0', $customer->id);

        Braintree\Customer::delete('0');
    }

    public function testGatewayCreate()
    {
        $gateway = new Braintree\Gateway([
            'environment' => 'development',
            'merchantId' => 'integration_merchant_id',
            'publicKey' => 'integration_public_key',
            'privateKey' => 'integration_private_key'
        ]);
        $result = $gateway->customer()->create([
            'firstName' => 'Mike',
            'lastName' => 'Jones',
        ]);
        $this->assertEquals(true, $result->success);
        $customer = $result->customer;
        $this->assertEquals('Mike', $customer->firstName);
        $this->assertEquals('Jones', $customer->lastName);
        $this->assertNotNull($customer->merchantId);
    }

    public function testCreateWithAccessToken()
    {
        $credentials = Test\Braintree\OAuthTestHelper::createCredentials([
            'clientId' => 'client_id$development$integration_client_id',
            'clientSecret' => 'client_secret$development$integration_client_secret',
            'merchantId' => 'integration_merchant_id',
        ]);

        $gateway = new Braintree\Gateway([
            'accessToken' => $credentials->accessToken,
        ]);

        $result = $gateway->customer()->create([
            'firstName' => 'Mike',
            'lastName' => 'Jones',
        ]);
        $this->assertEquals(true, $result->success);
        $customer = $result->customer;
        $this->assertEquals('Mike', $customer->firstName);
        $this->assertEquals('Jones', $customer->lastName);
        $this->assertNotNull($customer->merchantId);
    }

    public function testCreateWithAccountTypeDebit()
    {
        $result = Braintree\Customer::create([
            'firstName' => 'Mike',
            'lastName' => 'Jones',
            'company' => 'Jones Co.',
            'email' => 'mike.jones@example.com',
            'phone' => '419.555.1234',
            'fax' => '419.555.1235',
            'website' => 'http://example.com',
            'creditCard' => [
                'number' => Braintree\Test\CreditCardNumbers::$hiper,
                'expirationDate' => '05/12',
                'options' => [
                    'verifyCard' => true,
                    'verificationMerchantAccountId' => 'hiper_brl',
                    'verificationAccountType' => 'debit'
                ]
            ]
        ]);
        $this->assertEquals(true, $result->success);
        $this->assertEquals('debit', $result->customer->creditCards[0]->verification->creditCard['accountType']);
    }

    public function testCreateWithAccountTypeCredit()
    {
        $result = Braintree\Customer::create([
            'firstName' => 'Mike',
            'lastName' => 'Jones',
            'company' => 'Jones Co.',
            'email' => 'mike.jones@example.com',
            'phone' => '419.555.1234',
            'fax' => '419.555.1235',
            'website' => 'http://example.com',
            'creditCard' => [
                'number' => Braintree\Test\CreditCardNumbers::$hiper,
                'expirationDate' => '05/12',
                'options' => [
                    'verifyCard' => true,
                    'verificationMerchantAccountId' => 'hiper_brl',
                    'verificationAccountType' => 'credit'
                ]
            ]
        ]);
        $this->assertEquals(true, $result->success);
        $this->assertEquals('credit', $result->customer->creditCards[0]->verification->creditCard['accountType']);
    }

    public function testCreateErrorsWithVerificationAccountTypeIsInvalid()
    {
        $result = Braintree\Customer::create([
            'firstName' => 'Mike',
            'lastName' => 'Jones',
            'company' => 'Jones Co.',
            'email' => 'mike.jones@example.com',
            'phone' => '419.555.1234',
            'fax' => '419.555.1235',
            'website' => 'http://example.com',
            'creditCard' => [
                'number' => Braintree\Test\CreditCardNumbers::$hiper,
                'expirationDate' => '05/12',
                'options' => [
                    'verifyCard' => true,
                    'verificationMerchantAccountId' => 'hiper_brl',
                    'verificationAccountType' => 'wrong'
                ]
            ]
        ]);
        $this->assertFalse($result->success);
        $errors = $result->errors->forKey('customer')->forKey('creditCard')->forKey('options')->onAttribute('verificationAccountType');
        $this->assertEquals(Braintree\Error\Codes::CREDIT_CARD_OPTIONS_VERIFICATION_ACCOUNT_TYPE_IS_INVALID, $errors[0]->code);
    }

    public function testCreateErrorsWithVerificationAccountTypeNotSupported()
    {
        $result = Braintree\Customer::create([
            'firstName' => 'Mike',
            'lastName' => 'Jones',
            'company' => 'Jones Co.',
            'email' => 'mike.jones@example.com',
            'phone' => '419.555.1234',
            'fax' => '419.555.1235',
            'website' => 'http://example.com',
            'creditCard' => [
                "number" => "4111111111111111",
                'expirationDate' => '05/12',
                'options' => [
                    'verifyCard' => true,
                    'verificationAccountType' => 'credit'
                ]
            ]
        ]);
        $this->assertFalse($result->success);
        $errors = $result->errors->forKey('customer')->forKey('creditCard')->forKey('options')->onAttribute('verificationAccountType');
        $this->assertEquals(Braintree\Error\Codes::CREDIT_CARD_OPTIONS_VERIFICATION_ACCOUNT_TYPE_NOT_SUPPORTED, $errors[0]->code);
    }

    public function testCreateCustomerWithCardUsingNonce()
    {
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $nonce = $http->nonce_for_new_card([
            "creditCard" => [
                "number" => "4111111111111111",
                "expirationMonth" => "11",
                "expirationYear" => "2099"
            ],
            "share" => true
        ]);

        $result = Braintree\Customer::create([
            'creditCard' => [
                'paymentMethodNonce' => $nonce
            ]
        ]);

        $this->assertTrue($result->success);
        $this->assertSame("411111", $result->customer->creditCards[0]->bin);
        $this->assertSame("1111", $result->customer->creditCards[0]->last4);
    }

    public function testCreateCustomerWithApplePayCard()
    {
        $nonce = Braintree\Test\Nonces::$applePayVisa;
        $result = Braintree\Customer::create([
            'paymentMethodNonce' => $nonce
        ]);
        $this->assertTrue($result->success);
        $customer = $result->customer;
        $this->assertNotNull($customer->applePayCards[0]);
        $this->assertNotNull($customer->paymentMethods[0]);
    }

    public function testCreateCustomerWithAndroidPayProxyCard()
    {
        $nonce = Braintree\Test\Nonces::$androidPayDiscover;
        $result = Braintree\Customer::create([
            'paymentMethodNonce' => $nonce
        ]);
        $this->assertTrue($result->success);
        $customer = $result->customer;
        $this->assertNotNull($customer->androidPayCards[0]);
        $this->assertNotNull($customer->paymentMethods[0]);
        $androidPayCard = $customer->androidPayCards[0];
        $this->assertTrue($androidPayCard instanceof Braintree\AndroidPayCard);
        $this->assertNotNull($androidPayCard->token);
        $this->assertNotNull($androidPayCard->expirationYear);
    }

    public function testCreateCustomerWithAndroidPayNetworkToken()
    {
        $nonce = Braintree\Test\Nonces::$androidPayMasterCard;
        $result = Braintree\Customer::create([
            'paymentMethodNonce' => $nonce
        ]);
        $this->assertTrue($result->success);
        $customer = $result->customer;
        $this->assertNotNull($customer->androidPayCards[0]);
        $this->assertNotNull($customer->paymentMethods[0]);
        $androidPayCard = $customer->androidPayCards[0];
        $this->assertTrue($androidPayCard instanceof Braintree\AndroidPayCard);
        $this->assertNotNull($androidPayCard->token);
        $this->assertNotNull($androidPayCard->expirationYear);
    }

    public function testCreateCustomerWithAmexExpressCheckoutCard()
    {
        $nonce = Braintree\Test\Nonces::$amexExpressCheckout;
        $result = Braintree\Customer::create([
            'paymentMethodNonce' => $nonce
        ]);
        $this->assertTrue($result->success);
        $customer = $result->customer;
        $this->assertNotNull($customer->amexExpressCheckoutCards[0]);
        $this->assertNotNull($customer->paymentMethods[0]);
        $amexExpressCheckoutCard = $customer->amexExpressCheckoutCards[0];
        $this->assertTrue($amexExpressCheckoutCard instanceof Braintree\AmexExpressCheckoutCard);
        $this->assertNotNull($amexExpressCheckoutCard->token);
        $this->assertNotNull($amexExpressCheckoutCard->expirationYear);
    }

    public function testCreateCustomerWithVenmoAccount()
    {
        $nonce = Braintree\Test\Nonces::$venmoAccount;
        $result = Braintree\Customer::create(array(
            'paymentMethodNonce' => $nonce
        ));
        $this->assertTrue($result->success);
        $customer = $result->customer;
        $this->assertNotNull($customer->venmoAccounts[0]);
        $this->assertNotNull($customer->paymentMethods[0]);
        $venmoAccount = $customer->venmoAccounts[0];
        $this->assertTrue($venmoAccount instanceof Braintree\VenmoAccount);
        $this->assertNotNull($venmoAccount->token);
        $this->assertNotNull($venmoAccount->username);
        $this->assertNotNull($venmoAccount->venmoUserId);
    }

    public function testCannotCreateCustomerWithCoinbase()
    {
        $nonce = Braintree\Test\Nonces::$coinbase;
        $result = Braintree\Customer::create([
            'paymentMethodNonce' => $nonce
        ]);
        $this->assertFalse($result->success);
        $this->assertEquals(Braintree\Error\Codes::PAYMENT_METHOD_NO_LONGER_SUPPORTED, $result->errors->forKey('coinbaseAccount')->onAttribute('base')[0]->code);
    }

    public function testCreateCustomerWithUsBankAccount()
    {
        $nonce = Test\Helper::generateValidUsBankAccountNonce();
        $result = Braintree\Customer::create(array(
            'paymentMethodNonce' => $nonce,
            'creditCard' => [
                'options' => [
                    'verificationMerchantAccountId' => Test\Helper::usBankMerchantAccount()
                ]
            ]
        ));
        $this->assertTrue($result->success);
        $customer = $result->customer;
        $this->assertNotNull($customer->usBankAccounts[0]);
        $this->assertNotNull($customer->paymentMethods[0]);
        $usBankAccount = $customer->usBankAccounts[0];
        $this->assertTrue($usBankAccount instanceof Braintree\UsBankAccount);
        $this->assertNotNull($usBankAccount->token);
        $this->assertEquals('Dan Schulman', $usBankAccount->accountHolderName);
        $this->assertEquals('021000021', $usBankAccount->routingNumber);
        $this->assertEquals('1234', $usBankAccount->last4);
        $this->assertEquals('checking', $usBankAccount->accountType);
        $this->assertRegexp('/CHASE/', $usBankAccount->bankName);
    }

    public function testCreate_withUnicode()
    {
        $result = Braintree\Customer::create([
            'firstName' => "Здравствуйте",
            'lastName' => 'Jones',
            'company' => 'Jones Co.',
            'email' => 'mike.jones@example.com',
            'phone' => '419.555.1234',
            'fax' => '419.555.1235',
            'website' => 'http://example.com'
        ]);
        $this->assertEquals(true, $result->success);
        $customer = $result->customer;
        $this->assertEquals("Здравствуйте", $customer->firstName);
        $this->assertEquals('Jones', $customer->lastName);
        $this->assertEquals('Jones Co.', $customer->company);
        $this->assertEquals('mike.jones@example.com', $customer->email);
        $this->assertEquals('419.555.1234', $customer->phone);
        $this->assertEquals('419.555.1235', $customer->fax);
        $this->assertEquals('http://example.com', $customer->website);
        $this->assertNotNull($customer->merchantId);
    }

    public function testCreate_withCountry()
    {
        $result = Braintree\Customer::create([
            'firstName' => 'Bat',
            'lastName' => 'Manderson',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12',
                'billingAddress' => [
                   'countryName' => 'Gabon',
                   'countryCodeAlpha2' => 'GA',
                   'countryCodeAlpha3' => 'GAB',
                   'countryCodeNumeric' => '266'
                ]
            ]
        ]);
        $this->assertEquals(true, $result->success);
        $customer = $result->customer;
        $this->assertEquals('Gabon', $customer->creditCards[0]->billingAddress->countryName);
        $this->assertEquals('GA', $customer->creditCards[0]->billingAddress->countryCodeAlpha2);
        $this->assertEquals('GAB', $customer->creditCards[0]->billingAddress->countryCodeAlpha3);
        $this->assertEquals('266', $customer->creditCards[0]->billingAddress->countryCodeNumeric);
        $this->assertEquals(1, preg_match('/\A\w{32}\z/', $customer->creditCards[0]->uniqueNumberIdentifier));
    }

    public function testCreate_withVenmoSdkSession()
    {
        $result = Braintree\Customer::create([
            'firstName' => 'Bat',
            'lastName' => 'Manderson',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12',
                'options' => [
                    'venmoSdkSession' => Braintree\Test\VenmoSdk::getTestSession()
                ]
            ]
        ]);
        $this->assertEquals(true, $result->success);
        $customer = $result->customer;
        $this->assertEquals(false, $customer->creditCards[0]->venmoSdk);
    }

    public function testCreate_withVenmoSdkPaymentMethodCode()
    {
        $result = Braintree\Customer::create([
            'firstName' => 'Bat',
            'lastName' => 'Manderson',
            'creditCard' => [
                'venmoSdkPaymentMethodCode' => Braintree\Test\VenmoSdk::$visaPaymentMethodCode
            ],
        ]);
        $this->assertEquals(true, $result->success);
        $customer = $result->customer;
        $this->assertEquals("411111", $customer->creditCards[0]->bin);
    }

    public function testCreate_blankCustomer()
    {
        $result = Braintree\Customer::create();
        $this->assertEquals(true, $result->success);
        $this->assertNotNull($result->customer->id);

        $result = Braintree\Customer::create([]);
        $this->assertEquals(true, $result->success);
        $this->assertNotNull($result->customer->id);
    }

    public function testCreate_withSpecialChars()
    {
        $result = Braintree\Customer::create(['firstName' => '<>&"\'']);
        $this->assertEquals(true, $result->success);
        $this->assertEquals('<>&"\'', $result->customer->firstName);
    }

    public function testCreate_withCustomFields()
    {
        $result = Braintree\Customer::create([
            'firstName' => 'Mike',
            'customFields' => [
                'store_me' => 'some custom value'
            ]
        ]);
        $this->assertEquals(true, $result->success);
        $customFields = $result->customer->customFields;
        $this->assertEquals('some custom value', $customFields['store_me']);
    }

    public function testCreate_withFraudParams()
    {
        $result = Braintree\Customer::create([
            'firstName' => 'Mike',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12',
                'cvv' => '123',
                'cardholderName' => 'Mike Jones',
                'deviceSessionId' => 'abc123',
                'fraudMerchantId' => '456'
            ]
        ]);
        $this->assertEquals(true, $result->success);
    }

    public function testCreate_withRiskData()
    {
        $result = Braintree\Customer::create([
            'firstName' => 'Mike',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12',
                'cvv' => '123',
                'cardholderName' => 'Mike Jones',
            ],
            'riskData' => [
                'customer_browser' => 'IE5',
                'customer_ip' => '192.168.0.1'
            ]
        ]);
        $this->assertEquals(true, $result->success);
    }

    public function testCreate_withCreditCard()
    {
        $result = Braintree\Customer::create([
            'firstName' => 'Mike',
            'lastName' => 'Jones',
            'company' => 'Jones Co.',
            'email' => 'mike.jones@example.com',
            'phone' => '419.555.1234',
            'fax' => '419.555.1235',
            'website' => 'http://example.com',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12',
                'cvv' => '123',
                'cardholderName' => 'Mike Jones'
            ]
        ]);
        $this->assertEquals(true, $result->success);
        $customer = $result->customer;
        $this->assertEquals('Mike', $customer->firstName);
        $this->assertEquals('Jones', $customer->lastName);
        $this->assertEquals('Jones Co.', $customer->company);
        $this->assertEquals('mike.jones@example.com', $customer->email);
        $this->assertEquals('419.555.1234', $customer->phone);
        $this->assertEquals('419.555.1235', $customer->fax);
        $this->assertEquals('http://example.com', $customer->website);
        $creditCard = $customer->creditCards[0];
        $this->assertEquals('510510', $creditCard->bin);
        $this->assertEquals('5100', $creditCard->last4);
        $this->assertEquals('Mike Jones', $creditCard->cardholderName);
        $this->assertEquals('05/2012', $creditCard->expirationDate);
        $this->assertEquals('05', $creditCard->expirationMonth);
        $this->assertEquals('2012', $creditCard->expirationYear);
    }

    public function testCreate_withDuplicateCardCheck()
    {
        $customer = Braintree\Customer::createNoValidate();

        $attributes = [
            'firstName' => 'Mike',
            'lastName' => 'Jones',
            'company' => 'Jones Co.',
            'email' => 'mike.jones@example.com',
            'phone' => '419.555.1234',
            'fax' => '419.555.1235',
            'website' => 'http://example.com',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12',
                'cvv' => '123',
                'cardholderName' => 'Mike Jones',
                'options' => [
                    'failOnDuplicatePaymentMethod' => true
                ]
            ]
        ];
        Braintree\Customer::create($attributes);
        $result = Braintree\Customer::create($attributes);

        $this->assertFalse($result->success);
        $errors = $result->errors->forKey('customer')->forKey('creditCard')->onAttribute('number');
        $this->assertEquals(Braintree\Error\Codes::CREDIT_CARD_DUPLICATE_CARD_EXISTS, $errors[0]->code);
        $this->assertEquals(1, preg_match('/Duplicate card exists in the vault\./', $result->message));
    }

    public function testCreate_withCreditCardAndSpecificVerificationMerchantAccount()
    {
        $result = Braintree\Customer::create([
            'firstName' => 'Mike',
            'lastName' => 'Jones',
            'company' => 'Jones Co.',
            'email' => 'mike.jones@example.com',
            'phone' => '419.555.1234',
            'fax' => '419.555.1235',
            'website' => 'http://example.com',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12',
                'cvv' => '123',
                'cardholderName' => 'Mike Jones',
                'options' => [
                    'verificationMerchantAccountId' => Test\Helper::nonDefaultMerchantAccountId(),
                    'verifyCard' => true
                ]
            ]
        ]);
        Test\Helper::assertPrintable($result);
        $this->assertFalse($result->success);
        $this->assertEquals(Braintree\Result\CreditCardVerification::PROCESSOR_DECLINED, $result->creditCardVerification->status);
        $this->assertEquals('2000', $result->creditCardVerification->processorResponseCode);
        $this->assertEquals('Do Not Honor', $result->creditCardVerification->processorResponseText);
        $this->assertEquals('M', $result->creditCardVerification->cvvResponseCode);
        $this->assertEquals(null, $result->creditCardVerification->avsErrorResponseCode);
        $this->assertEquals('I', $result->creditCardVerification->avsPostalCodeResponseCode);
        $this->assertEquals('I', $result->creditCardVerification->avsStreetAddressResponseCode);
    }

    public function testCreate_withCreditCardAndVerificationAmount()
    {
        $result = Braintree\Customer::create([
            'firstName' => 'Mike',
            'lastName' => 'Jones',
            'company' => 'Jones Co.',
            'email' => 'mike.jones@example.com',
            'phone' => '419.555.1234',
            'fax' => '419.555.1235',
            'website' => 'http://example.com',
            'creditCard' => [
                'number' => '5555555555554444',
                'expirationDate' => '05/12',
                'cvv' => '123',
                'cardholderName' => 'Mike Jones',
                'options' => [
                    'verifyCard' => true,
                    'verificationAmount' => '2.00'
                ]
            ]
        ]);

        $this->assertTrue($result->success);
    }

    public function testCreate_withCreditCardAndBillingAddress()
    {
        $result = Braintree\Customer::create([
            'firstName' => 'Mike',
            'lastName' => 'Jones',
            'company' => 'Jones Co.',
            'email' => 'mike.jones@example.com',
            'phone' => '419.555.1234',
            'fax' => '419.555.1235',
            'website' => 'http://example.com',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12',
                'cvv' => '123',
                'cardholderName' => 'Mike Jones',
                'billingAddress' => [
                    'firstName' => 'Drew',
                    'lastName' => 'Smith',
                    'company' => 'Smith Co.',
                    'streetAddress' => '1 E Main St',
                    'extendedAddress' => 'Suite 101',
                    'locality' => 'Chicago',
                    'region' => 'IL',
                    'postalCode' => '60622',
                    'countryName' => 'United States of America'
                ]
            ]
        ]);
        Test\Helper::assertPrintable($result);
        $this->assertEquals(true, $result->success);
        $customer = $result->customer;
        $this->assertEquals('Mike', $customer->firstName);
        $this->assertEquals('Jones', $customer->lastName);
        $this->assertEquals('Jones Co.', $customer->company);
        $this->assertEquals('mike.jones@example.com', $customer->email);
        $this->assertEquals('419.555.1234', $customer->phone);
        $this->assertEquals('419.555.1235', $customer->fax);
        $this->assertEquals('http://example.com', $customer->website);
        $creditCard = $customer->creditCards[0];
        $this->assertEquals('510510', $creditCard->bin);
        $this->assertEquals('5100', $creditCard->last4);
        $this->assertEquals('Mike Jones', $creditCard->cardholderName);
        $this->assertEquals('05/2012', $creditCard->expirationDate);
        $this->assertEquals('05', $creditCard->expirationMonth);
        $this->assertEquals('2012', $creditCard->expirationYear);
        $address = $customer->addresses[0];
        $this->assertEquals($address, $creditCard->billingAddress);
        $this->assertEquals('Drew', $address->firstName);
        $this->assertEquals('Smith', $address->lastName);
        $this->assertEquals('Smith Co.', $address->company);
        $this->assertEquals('1 E Main St', $address->streetAddress);
        $this->assertEquals('Suite 101', $address->extendedAddress);
        $this->assertEquals('Chicago', $address->locality);
        $this->assertEquals('IL', $address->region);
        $this->assertEquals('60622', $address->postalCode);
        $this->assertEquals('United States of America', $address->countryName);
    }

    public function testCreate_withValidationErrors()
    {
        $result = Braintree\Customer::create([
            'email' => 'invalid',
            'creditCard' => [
                'number' => 'invalid',
                'billingAddress' => [
                    'streetAddress' => str_repeat('x', 256)
                ]
            ]
        ]);
        Test\Helper::assertPrintable($result);
        $this->assertEquals(false, $result->success);
        $errors = $result->errors->forKey('customer')->onAttribute('email');
        $this->assertEquals(Braintree\Error\Codes::CUSTOMER_EMAIL_IS_INVALID, $errors[0]->code);
        $errors = $result->errors->forKey('customer')->forKey('creditCard')->onAttribute('number');
        $this->assertEquals(Braintree\Error\Codes::CREDIT_CARD_NUMBER_INVALID_LENGTH, $errors[0]->code);
        $errors = $result->errors->forKey('customer')->forKey('creditCard')->forKey('billingAddress')->onAttribute('streetAddress');
        $this->assertEquals(Braintree\Error\Codes::ADDRESS_STREET_ADDRESS_IS_TOO_LONG, $errors[0]->code);
    }

    public function testCreate_countryValidations_inconsistency()
    {
        $result = Braintree\Customer::create([
            'creditCard' => [
                'billingAddress' => [
                    'countryName' => 'Georgia',
                    'countryCodeAlpha2' => 'TF'
                ]
            ]
        ]);
        $this->assertEquals(false, $result->success);
        $errors = $result->errors->forKey('customer')->forKey('creditCard')->forKey('billingAddress')->onAttribute('base');
        $this->assertEquals(Braintree\Error\Codes::ADDRESS_INCONSISTENT_COUNTRY, $errors[0]->code);
    }

    public function testCreateNoValidate_returnsCustomer()
    {
        $customer = Braintree\Customer::createNoValidate([
            'firstName' => 'Paul',
            'lastName' => 'Martin'
        ]);
        $this->assertEquals('Paul', $customer->firstName);
        $this->assertEquals('Martin', $customer->lastName);
    }

    public function testCreateNoValidate_throwsIfInvalid()
    {
        $this->setExpectedException('Braintree\Exception\ValidationsFailed');
        $customer = Braintree\Customer::createNoValidate(['email' => 'invalid']);
    }

    public function testCreate_worksWithFuturePayPalNonce()
    {
        $nonce = Braintree\Test\Nonces::$paypalFuturePayment;

        $result = Braintree\Customer::create([
            'paymentMethodNonce' => $nonce
        ]);

        $this->assertTrue($result->success);
    }

    public function testCreate_worksWithOrderPaymentPayPalNonce()
    {
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $nonce = $http->nonceForPayPalAccount([
            'paypal_account' => [
                'intent' => 'order',
                'payment_token' => 'paypal-payment-token',
                'payer_id' => 'paypal-payer-id',
            ]
        ]);

        $result = Braintree\Customer::create([
            'paymentMethodNonce' => $nonce
        ]);

        $this->assertTrue($result->success);
    }

    public function testCreate_worksWithOrderPaymentPayPalNonceWithSnakeCase()
    {
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $nonce = $http->nonceForPayPalAccount([
            'paypal_account' => [
                'intent' => 'order',
                'payment_token' => 'paypal-payment-token',
                'payer_id' => 'paypal-payer-id',
            ]
        ]);

        $result = Braintree\Customer::create([
            'paymentMethodNonce' => $nonce,
            'options' => [
                'paypal' => [
                    'payee_email' => 'payee@example.com',
                    'order_id' => 'merchant-order-id',
                    'custom_field' => 'custom merchant field',
                    'description' => 'merchant description',
                    'amount' => '1.23',
                ],
            ],
        ]);

        $this->assertTrue($result->success);
    }

    public function testCreate_worksWithOrderPaymentPayPalNonceWithCamelCase()
    {
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $nonce = $http->nonceForPayPalAccount([
            'paypal_account' => [
                'intent' => 'order',
                'payment_token' => 'paypal-payment-token',
                'payer_id' => 'paypal-payer-id',
            ]
        ]);

        $result = Braintree\Customer::create([
            'paymentMethodNonce' => $nonce,
            'options' => [
                'paypal' => [
                    'payeeEmail' => 'payee@example.com',
                    'orderId' => 'merchant-order-id',
                    'customField' => 'custom merchant field',
                    'description' => 'merchant description',
                    'amount' => '1.23',
                    'shipping' => [
                        'firstName' => 'Andrew',
                        'lastName' => 'Mason',
                        'company' => 'Braintree',
                        'streetAddress' => '456 W Main St',
                        'extendedAddress' => 'Apt 2F',
                        'locality' => 'Bartlett',
                        'region' => 'IL',
                        'postalCode' => '60103',
                        'countryName' => 'United States of America',
                        'countryCodeAlpha2' => 'US',
                        'countryCodeAlpha3' => 'USA',
                        'countryCodeNumeric' => '840'
                    ],
                ],
            ],
        ]);

        $this->assertTrue($result->success);
    }

    public function testCreate_doesNotWorkWithOnetimePayPalNonce()
    {
        $nonce = Braintree\Test\Nonces::$paypalOneTimePayment;

        $result = Braintree\Customer::create([
            'paymentMethodNonce' => $nonce
        ]);

        $this->assertFalse($result->success);
        $errors = $result->errors->forKey('customer')->forKey('paypalAccount')->errors;
        $this->assertEquals(Braintree\Error\Codes::PAYPAL_ACCOUNT_CANNOT_VAULT_ONE_TIME_USE_PAYPAL_ACCOUNT, $errors[0]->code);
    }

    public function testDelete_deletesTheCustomer()
    {
        $result = Braintree\Customer::create([]);
        $this->assertEquals(true, $result->success);
        Braintree\Customer::find($result->customer->id);
        Braintree\Customer::delete($result->customer->id);
        $this->setExpectedException('Braintree\Exception\NotFound');
        Braintree\Customer::find($result->customer->id);
    }

    public function testFind()
    {
        $result = Braintree\Customer::create([
            'firstName' => 'Mike',
            'lastName' => 'Jones',
            'company' => 'Jones Co.',
            'email' => 'mike.jones@example.com',
            'phone' => '419.555.1234',
            'fax' => '419.555.1235',
            'website' => 'http://example.com'
        ]);
        $this->assertEquals(true, $result->success);
        $customer = Braintree\Customer::find($result->customer->id);
        $this->assertEquals('Mike', $customer->firstName);
        $this->assertEquals('Jones', $customer->lastName);
        $this->assertEquals('Jones Co.', $customer->company);
        $this->assertEquals('mike.jones@example.com', $customer->email);
        $this->assertEquals('419.555.1234', $customer->phone);
        $this->assertEquals('419.555.1235', $customer->fax);
        $this->assertEquals('http://example.com', $customer->website);
    }

    public function test_findCustomerWithAllFilterableAssociationsFilteredOut()
    {
        $result = Braintree\Customer::create([
            'firstName' => 'Mike',
            'lastName' => 'Jones',
            'company' => 'Jones Co.',
            'email' => 'mike.jones@example.com',
            'phone' => '419.555.1234',
            'fax' => '419.555.1235',
            'website' => 'http://example.com',
            'customFields' => [
                'storeMe' => 'custom value'
            ]
        ]);
        $creditCard = Braintree\CreditCard::create([
            'customerId' => $result->customer->id,
            'number' => '5105105105105100',
            'expirationDate' => '05/12',
            'billingAddress' => [
                'firstName' => 'Drew',
                'lastName' => 'Smith',
                'company' => 'Smith Co.',
                'streetAddress' => '1 E Main St',
                'locality' => 'Chicago',
                'region' => 'IL',
                'postalCode' => '60622',
                'countryName' => 'United States of America'
            ]
        ]);
        $id = strval(rand());
        $subscriptions = Braintree\Subscription::create([
            'id' => $id,
            'paymentMethodToken' => $creditCard->creditCard->token,
            'planId' => 'integration_trialless_plan',
            'price' => '1.00'
        ]);

        $customer = Braintree\Customer::find($result->customer->id, "customernoassociations");
        $this->assertEquals(0, count($customer->creditCards));
        $this->assertEquals(0, count($customer->paymentMethods));
        $this->assertEquals(0, count($customer->addresses));
        $this->assertEquals(0, count($customer->customFields));
    }

    public function test_findCustomerWithNestedFilterableAssociationsFilteredOut()
    {
        $result = Braintree\Customer::create([
            'firstName' => 'Mike',
            'lastName' => 'Jones',
            'company' => 'Jones Co.',
            'email' => 'mike.jones@example.com',
            'phone' => '419.555.1234',
            'fax' => '419.555.1235',
            'website' => 'http://example.com',
            'customFields' => [
                'storeMe' => 'custom value'
            ]
        ]);
        $creditCard = Braintree\CreditCard::create([
            'customerId' => $result->customer->id,
            'number' => '5105105105105100',
            'expirationDate' => '05/12',
            'billingAddress' => [
                'firstName' => 'Drew',
                'lastName' => 'Smith',
                'company' => 'Smith Co.',
                'streetAddress' => '1 E Main St',
                'locality' => 'Chicago',
                'region' => 'IL',
                'postalCode' => '60622',
                'countryName' => 'United States of America'
            ]
        ]);
        $id = strval(rand());
        $subscriptions = Braintree\Subscription::create([
            'id' => $id,
            'paymentMethodToken' => $creditCard->creditCard->token,
            'planId' => 'integration_trialless_plan',
            'price' => '1.00'
        ]);

        $customer = Braintree\Customer::find($result->customer->id, "customertoplevelassociations");

        $this->assertEquals(1, count($customer->creditCards));
        $this->assertEquals(0, count($customer->creditCards[0]->subscriptions));
        $this->assertEquals(1, count($customer->paymentMethods));
        $this->assertEquals(0, count($customer->paymentMethods[0]->subscriptions));
        $this->assertEquals(1, count($customer->addresses));
        $this->assertEquals(1, count($customer->customFields));
    }

    public function test_findUsBankAccountGivenPaymentMethodToken()
    {
        $nonce = Test\Helper::generateValidUsBankAccountNonce();
        $result = Braintree\Customer::create(array(
            'paymentMethodNonce' => $nonce,
            'creditCard' => [
                'options' => [
                    'verificationMerchantAccountId' => Test\Helper::usBankMerchantAccount()
                ]
            ]
        ));
        $this->assertTrue($result->success);

        $customer = Braintree\Customer::find($result->customer->id);
        $this->assertNotNull($customer->usBankAccounts[0]);
        $this->assertNotNull($customer->paymentMethods[0]);
        $usBankAccount = $customer->usBankAccounts[0];
        $this->assertTrue($usBankAccount instanceof Braintree\UsBankAccount);
        $this->assertNotNull($usBankAccount->token);
        $this->assertEquals('Dan Schulman', $usBankAccount->accountHolderName);
        $this->assertEquals('021000021', $usBankAccount->routingNumber);
        $this->assertEquals('1234', $usBankAccount->last4);
        $this->assertEquals('checking', $usBankAccount->accountType);
        $this->assertRegexp('/CHASE/', $usBankAccount->bankName);
    }

    public function testFind_throwsExceptionIfNotFound()
    {
        $this->setExpectedException('Braintree\Exception\NotFound');
        Braintree\Customer::find("does-not-exist");
    }

    public function testUpdate()
    {
        $result = Braintree\Customer::create([
            'firstName' => 'Old First',
            'lastName' => 'Old Last',
            'company' => 'Old Company',
            'email' => 'old.email@example.com',
            'phone' => 'old phone',
            'fax' => 'old fax',
            'website' => 'http://old.example.com'
        ]);
        $this->assertEquals(true, $result->success);
        $customer = $result->customer;
        $updateResult = Braintree\Customer::update($customer->id, [
            'firstName' => 'New First',
            'lastName' => 'New Last',
            'company' => 'New Company',
            'email' => 'new.email@example.com',
            'phone' => 'new phone',
            'fax' => 'new fax',
            'website' => 'http://new.example.com'
        ]);
        $this->assertEquals(true, $updateResult->success);
        $this->assertEquals('New First', $updateResult->customer->firstName);
        $this->assertEquals('New Last', $updateResult->customer->lastName);
        $this->assertEquals('New Company', $updateResult->customer->company);
        $this->assertEquals('new.email@example.com', $updateResult->customer->email);
        $this->assertEquals('new phone', $updateResult->customer->phone);
        $this->assertEquals('new fax', $updateResult->customer->fax);
        $this->assertEquals('http://new.example.com', $updateResult->customer->website);
    }

    public function testUpdate_withCountry()
    {
        $customer = Braintree\Customer::create([
            'firstName' => 'Bat',
            'lastName' => 'Manderson',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12',
                'billingAddress' => [
                    'countryName' => 'United States of America',
                    'countryCodeAlpha2' => 'US',
                    'countryCodeAlpha3' => 'USA',
                    'countryCodeNumeric' => '840'
                ]
            ]
        ])->customer;

        $result = Braintree\Customer::update($customer->id, [
            'firstName' => 'Bat',
            'lastName' => 'Manderson',
            'creditCard' => [
				'options' => [
					'updateExistingToken' => $customer->creditCards[0]->token
				],
                'billingAddress' => [
                    'countryName' => 'Gabon',
                    'countryCodeAlpha2' => 'GA',
                    'countryCodeAlpha3' => 'GAB',
                    'countryCodeNumeric' => '266',
                    'options' => [
                        'updateExisting' => true
                    ]
            	]
            ]
        ]);

        $this->assertEquals(true, $result->success);
        $updatedCustomer = $result->customer;
        $this->assertEquals('Gabon', $updatedCustomer->creditCards[0]->billingAddress->countryName);
        $this->assertEquals('GA', $updatedCustomer->creditCards[0]->billingAddress->countryCodeAlpha2);
        $this->assertEquals('GAB', $updatedCustomer->creditCards[0]->billingAddress->countryCodeAlpha3);
        $this->assertEquals('266', $updatedCustomer->creditCards[0]->billingAddress->countryCodeNumeric);
    }

    public function testUpdate_withUpdatingExistingCreditCard()
    {
        $create_result = Braintree\Customer::create([
            'firstName' => 'Old First',
            'lastName' => 'Old Last',
            'website' => 'http://old.example.com',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12',
                'cardholderName' => 'Old Cardholder'
            ]
        ]);
        $this->assertEquals(true, $create_result->success);
        $customer = $create_result->customer;
        $creditCard = $customer->creditCards[0];
        $result = Braintree\Customer::update($customer->id, [
            'firstName' => 'New First',
            'lastName' => 'New Last',
            'creditCard' => [
                'number' => '4111111111111111',
                'expirationDate' => '11/14',
                'cardholderName' => 'New Cardholder',
                'options' => [
                    'updateExistingToken' => $creditCard->token
                ]
            ]
        ]);
        $this->assertEquals(true, $result->success);
        $this->assertEquals('New First', $result->customer->firstName);
        $this->assertEquals('New Last', $result->customer->lastName);
        $this->assertEquals(1, sizeof($result->customer->creditCards));
        $creditCard = $result->customer->creditCards[0];
        $this->assertEquals('411111', $creditCard->bin);
        $this->assertEquals('11/2014', $creditCard->expirationDate);
        $this->assertEquals('New Cardholder', $creditCard->cardholderName);
    }

    public function testUpdate_failOnDuplicatePaymentMethod()
    {
        $create_result = Braintree\Customer::create([
            'creditCard' => [
                'number' => '4111111111111111',
                'expirationDate' => '11/14',
            ]
        ]);
        $this->assertEquals(true, $create_result->success);
        $result = Braintree\Customer::update($create_result->customer->id, [
            'creditCard' => [
                'number' => '4111111111111111',
                'expirationDate' => '11/14',
                'options' => [
                    'failOnDuplicatePaymentMethod' => true
                ]
            ]
        ]);
        $this->assertFalse($result->success);
        $errors = $result->errors->forKey('customer')->forKey('creditCard')->onAttribute('number');
        $this->assertEquals(Braintree\Error\Codes::CREDIT_CARD_DUPLICATE_CARD_EXISTS, $errors[0]->code);
        $this->assertEquals(1, preg_match('/Duplicate card exists in the vault\./', $result->message));
    }

    public function testUpdate_forBillingAddressAndExistingCreditCardAndCustomerDetailsTogether()
    {
        $create_result = Braintree\Customer::create([
            'firstName' => 'Old First',
            'lastName' => 'Old Last',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12',
                'cvv' => '123',
                'cardholderName' => 'Old Cardholder',
                'billingAddress' => [
                    'firstName' => 'Drew',
                    'lastName' => 'Smith'
                ]
            ]
        ]);
        $this->assertEquals(true, $create_result->success);
        $customer = $create_result->customer;
        $creditCard = $customer->creditCards[0];
        $result = Braintree\Customer::update($customer->id, [
            'firstName' => 'New Customer First',
            'lastName' => 'New Customer Last',
            'creditCard' => [
                'number' => '4111111111111111',
                'expirationDate' => '11/14',
                'options' => [
                    'updateExistingToken' => $creditCard->token
                ],
                'billingAddress' => [
                    'firstName' => 'New Billing First',
                    'lastName' => 'New Billing Last',
                    'options' => [
                        'updateExisting' => true
                    ]
                ]
            ]
        ]);
        $this->assertEquals(true, $result->success);
        $this->assertEquals('New Customer First', $result->customer->firstName);
        $this->assertEquals('New Customer Last', $result->customer->lastName);
        $this->assertEquals(1, sizeof($result->customer->creditCards));
        $this->assertEquals(1, sizeof($result->customer->addresses));

        $creditCard = $result->customer->creditCards[0];
        $this->assertEquals('411111', $creditCard->bin);
        $this->assertEquals('11/2014', $creditCard->expirationDate);

        $billingAddress = $creditCard->billingAddress;
        $this->assertEquals('New Billing First', $billingAddress->firstName);
        $this->assertEquals('New Billing Last', $billingAddress->lastName);
    }

    public function testUpdate_withNewCreditCardAndExistingBillingAddress()
    {
        $customer = Braintree\Customer::create()->customer;
        $address = Braintree\Address::create([
            'customerId' => $customer->id,
            'firstName' => 'Dan'
        ])->address;

        $result = Braintree\Customer::update($customer->id, [
            'creditCard' => [
                'number' => '4111111111111111',
                'expirationDate' => '11/14',
                'billingAddressId' => $address->id
            ]
        ]);

        $billingAddress = $result->customer->creditCards[0]->billingAddress;
        $this->assertEquals($address->id, $billingAddress->id);
        $this->assertEquals('Dan', $billingAddress->firstName);
    }

    public function testUpdate_withNewCreditCardAndVerificationAmount()
    {
        $customer = Braintree\Customer::create()->customer;
        $result = Braintree\Customer::update($customer->id, [
            'creditCard' => [
                'number' => '4111111111111111',
                'expirationDate' => '11/14',
                'options' => [
                    'verifyCard' => true,
                    'verificationAmount' => '2.00'
                ]
            ]
        ]);

        $this->assertTrue($result->success);
    }

    public function testUpdate_worksWithFuturePayPalNonce()
    {
        $customerResult = Braintree\Customer::create([
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12',
                'options' => [
                    'makeDefault' => true
                ]
            ]
        ]);
        $paypalAccountToken = 'PAYPALToken-' . strval(rand());
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $nonce = $http->nonceForPayPalAccount([
            'paypal_account' => [
                'consent_code' => 'PAYPAL_CONSENT_CODE',
                'token' => $paypalAccountToken,
                'options' => [
                    'makeDefault' => true
                ]
            ]
        ]);

        $result = Braintree\Customer::update($customerResult->customer->id, [
            'paymentMethodNonce' => $nonce
        ]);

        $this->assertTrue($result->success);
        $this->assertEquals($result->customer->defaultPaymentMethod()->token, $paypalAccountToken);

    }

    public function testUpdate_worksWithOrderPaymentPayPalNonce()
    {
        $customerResult = Braintree\Customer::create([
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12',
                'options' => [
                    'makeDefault' => true
                ]
            ]
        ]);
        $paypalAccountToken = 'PAYPALToken-' . strval(rand());
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $nonce = $http->nonceForPayPalAccount([
            'paypal_account' => [
                'intent' => 'order',
                'payment_token' => 'paypal-payment-token',
                'payer_id' => 'paypal-payer-id',
                'token' => $paypalAccountToken,
                'options' => [
                    'makeDefault' => true
                ]
            ]
        ]);

        $result = Braintree\Customer::update($customerResult->customer->id, [
            'paymentMethodNonce' => $nonce
        ]);

        $this->assertTrue($result->success);
        $this->assertEquals($result->customer->defaultPaymentMethod()->token, $paypalAccountToken);
    }

    public function testUpdate_worksWithOrderPaymentPayPalNonceWithSnakeCase()
    {
        $customerResult = Braintree\Customer::create([
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12',
                'options' => [
                    'makeDefault' => true
                ]
            ]
        ]);
        $paypalAccountToken = 'PAYPALToken-' . strval(rand());
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $nonce = $http->nonceForPayPalAccount([
            'paypal_account' => [
                'intent' => 'order',
                'payment_token' => 'paypal-payment-token',
                'payer_id' => 'paypal-payer-id',
                'token' => $paypalAccountToken,
                'options' => [
                    'makeDefault' => true
                ]
            ]
        ]);

        $result = Braintree\Customer::update($customerResult->customer->id, [
            'paymentMethodNonce' => $nonce,
            'options' => [
                'paypal' => [
                    'payee_email' => 'payee@example.com',
                    'order_id' => 'merchant-order-id',
                    'custom_field' => 'custom merchant field',
                    'description' => 'merchant description',
                    'amount' => '1.23',
                ],
            ],
        ]);

        $this->assertTrue($result->success);
        $this->assertEquals($result->customer->defaultPaymentMethod()->token, $paypalAccountToken);
    }

    public function testUpdate_worksWithOrderPaymentPayPalNonceWithCamelCase()
    {
        $customerResult = Braintree\Customer::create([
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12',
                'options' => [
                    'makeDefault' => true
                ]
            ]
        ]);
        $paypalAccountToken = 'PAYPALToken-' . strval(rand());
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $nonce = $http->nonceForPayPalAccount([
            'paypal_account' => [
                'intent' => 'order',
                'payment_token' => 'paypal-payment-token',
                'payer_id' => 'paypal-payer-id',
                'token' => $paypalAccountToken,
                'options' => [
                    'makeDefault' => true
                ]
            ]
        ]);

        $result = Braintree\Customer::update($customerResult->customer->id, [
            'paymentMethodNonce' => $nonce,
            'options' => [
                'paypal' => [
                    'payeeEmail' => 'payee@example.com',
                    'orderId' => 'merchant-order-id',
                    'customField' => 'custom merchant field',
                    'description' => 'merchant description',
                    'amount' => '1.23',
                    'shipping' => [
                        'firstName' => 'Andrew',
                        'lastName' => 'Mason',
                        'company' => 'Braintree',
                        'streetAddress' => '456 W Main St',
                        'extendedAddress' => 'Apt 2F',
                        'locality' => 'Bartlett',
                        'region' => 'IL',
                        'postalCode' => '60103',
                        'countryName' => 'United States of America',
                        'countryCodeAlpha2' => 'US',
                        'countryCodeAlpha3' => 'USA',
                        'countryCodeNumeric' => '840'
                    ],
                ],
            ],
        ]);

        $this->assertTrue($result->success);
        $this->assertEquals($result->customer->defaultPaymentMethod()->token, $paypalAccountToken);
    }

    public function testUpdateDefaultPaymentMethod()
    {
        $result = Braintree\Customer::create([
            'firstName' => 'Old First',
            'lastName' => 'Old Last',
        ]);

        $this->assertEquals(true, $result->success);
        $customer = $result->customer;

        $token1 = 'TOKEN-' . strval(rand());

        $result = Braintree\PaymentMethod::create([
            'customerId' => $customer->id,
            'paymentMethodNonce' => Braintree\Test\Nonces::$transactableVisa,
            'token' => $token1
        ]);

        $updateResult = Braintree\Customer::update($customer->id, [
            'defaultPaymentMethodToken' => $token1
        ]);

        $this->assertEquals(true, $updateResult->success);
        $this->assertEquals($updateResult->customer->defaultPaymentMethod()->token, $token1);

        $token2 = 'TOKEN-' . strval(rand());

        $result = Braintree\PaymentMethod::create([
            'customerId' => $customer->id,
            'paymentMethodNonce' => Braintree\Test\Nonces::$transactableMasterCard,
            'token' => $token2
        ]);

        $updateResult = Braintree\Customer::update($customer->id, [
            'defaultPaymentMethodToken' => $token2
        ]);

        $this->assertEquals(true, $updateResult->success);
        $this->assertEquals($updateResult->customer->defaultPaymentMethod()->token, $token2);
    }


    public function testUpdateDefaultPaymentMethodFromOptions()
    {
        $result = Braintree\Customer::create([
            'firstName' => 'Old First',
            'lastName' => 'Old Last',
        ]);

        $this->assertEquals(true, $result->success);
        $customer = $result->customer;

        $token1 = 'TOKEN-' . strval(rand());

        $result = Braintree\PaymentMethod::create([
            'customerId' => $customer->id,
            'paymentMethodNonce' => Braintree\Test\Nonces::$transactableVisa,
            'token' => $token1
        ]);

        $updateResult = Braintree\Customer::update($customer->id, [
            'creditCard' => [
                'options' => [
                    'updateExistingToken' => $token1,
                    'makeDefault' => true
                ]
            ]
        ]);

        $this->assertEquals(true, $updateResult->success);
        $this->assertEquals($updateResult->customer->defaultPaymentMethod()->token, $token1);

        $token2 = 'TOKEN-' . strval(rand());

        $result = Braintree\PaymentMethod::create([
            'customerId' => $customer->id,
            'paymentMethodNonce' => Braintree\Test\Nonces::$transactableMasterCard,
            'token' => $token2
        ]);

        $updateResult = Braintree\Customer::update($customer->id, [
            'creditCard' => [
                'options' => [
                    'updateExistingToken' => $token2,
                    'makeDefault' => true
                ]
            ]
        ]);

        $this->assertEquals(true, $updateResult->success);
        $this->assertEquals($updateResult->customer->defaultPaymentMethod()->token, $token2);
    }
    public function testUpdate_doesNotWorkWithOnetimePayPalNonce()
    {
        $customerResult = Braintree\Customer::create([
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12',
                'options' => [
                    'makeDefault' => true
                ]
            ]
        ]);
        $paypalAccountToken = 'PAYPALToken-' . strval(rand());
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $nonce = $http->nonceForPayPalAccount([
            'paypal_account' => [
                'access_token' => 'PAYPAL_ACCESS_TOKEN',
                'token' => $paypalAccountToken,
                'options' => [
                    'makeDefault' => true
                ]
            ]
        ]);

        $result = Braintree\Customer::update($customerResult->customer->id, [
            'paymentMethodNonce' => $nonce
        ]);

        $this->assertFalse($result->success);
        $errors = $result->errors->forKey('customer')->forKey('paypalAccount')->errors;
        $this->assertEquals(Braintree\Error\Codes::PAYPAL_ACCOUNT_CANNOT_VAULT_ONE_TIME_USE_PAYPAL_ACCOUNT, $errors[0]->code);

    }

    public function testUpdateNoValidate()
    {
        $result = Braintree\Customer::create([
            'firstName' => 'Old First',
            'lastName' => 'Old Last',
            'company' => 'Old Company',
            'email' => 'old.email@example.com',
            'phone' => 'old phone',
            'fax' => 'old fax',
            'website' => 'http://old.example.com'
        ]);
        $this->assertEquals(true, $result->success);
        $customer = $result->customer;
        $updated = Braintree\Customer::updateNoValidate($customer->id, [
            'firstName' => 'New First',
            'lastName' => 'New Last',
            'company' => 'New Company',
            'email' => 'new.email@example.com',
            'phone' => 'new phone',
            'fax' => 'new fax',
            'website' => 'http://new.example.com'
        ]);
        $this->assertEquals('New First', $updated->firstName);
        $this->assertEquals('New Last', $updated->lastName);
        $this->assertEquals('New Company', $updated->company);
        $this->assertEquals('new.email@example.com', $updated->email);
        $this->assertEquals('new phone', $updated->phone);
        $this->assertEquals('new fax', $updated->fax);
        $this->assertEquals('http://new.example.com', $updated->website);
    }

    public function testCreateFromTransparentRedirect()
    {
        Test\Helper::suppressDeprecationWarnings();
        $queryString = $this->createCustomerViaTr(
            [
                'customer' => [
                    'first_name' => 'Joe',
                    'last_name' => 'Martin',
                    'credit_card' => [
                        'number' => '5105105105105100',
                        'expiration_date' => '05/12'
                    ]
                ]
            ],
            [
            ]
        );
        $result = Braintree\Customer::createFromTransparentRedirect($queryString);
        $this->assertTrue($result->success);
        $this->assertEquals('Joe', $result->customer->firstName);
        $this->assertEquals('Martin', $result->customer->lastName);
        $creditCard = $result->customer->creditCards[0];
        $this->assertEquals('510510', $creditCard->bin);
        $this->assertEquals('5100', $creditCard->last4);
        $this->assertEquals('05/2012', $creditCard->expirationDate);
    }

    public function testCreateFromTransparentRedirect_withParamsInTrData()
    {
        Test\Helper::suppressDeprecationWarnings();
        $queryString = $this->createCustomerViaTr(
            [
            ],
            [
                'customer' => [
                    'firstName' => 'Joe',
                    'lastName' => 'Martin',
                    'creditCard' => [
                        'number' => '5105105105105100',
                        'expirationDate' => '05/12'
                    ]
                ]
            ]
        );
        $result = Braintree\Customer::createFromTransparentRedirect($queryString);
        $this->assertTrue($result->success);
        $this->assertEquals('Joe', $result->customer->firstName);
        $this->assertEquals('Martin', $result->customer->lastName);
        $creditCard = $result->customer->creditCards[0];
        $this->assertEquals('510510', $creditCard->bin);
        $this->assertEquals('5100', $creditCard->last4);
        $this->assertEquals('05/2012', $creditCard->expirationDate);
    }

    public function testCreateFromTransparentRedirect_withValidationErrors()
    {
        Test\Helper::suppressDeprecationWarnings();
        $queryString = $this->createCustomerViaTr(
            [
                'customer' => [
                    'first_name' => str_repeat('x', 256),
                    'credit_card' => [
                        'number' => 'invalid',
                        'expiration_date' => ''
                    ]
                ]
            ],
            [
            ]
        );
        $result = Braintree\Customer::createFromTransparentRedirect($queryString);
        $this->assertFalse($result->success);
        $errors = $result->errors->forKey('customer')->onAttribute('firstName');
        $this->assertEquals(Braintree\Error\Codes::CUSTOMER_FIRST_NAME_IS_TOO_LONG, $errors[0]->code);
        $errors = $result->errors->forKey('customer')->forKey('creditCard')->onAttribute('number');
        $this->assertEquals(Braintree\Error\Codes::CREDIT_CARD_NUMBER_INVALID_LENGTH, $errors[0]->code);
        $errors = $result->errors->forKey('customer')->forKey('creditCard')->onAttribute('expirationDate');
        $this->assertEquals(Braintree\Error\Codes::CREDIT_CARD_EXPIRATION_DATE_IS_REQUIRED, $errors[0]->code);
    }

    public function testCreateWithInvalidUTF8Bytes()
    {
        $result = Braintree\Customer::create([
            'firstName' => "Jos\xe8 Maria"
        ]);
        $this->assertEquals(true, $result->success);
        $customer = $result->customer;
        $this->assertEquals("Jos\xc3\xa8 Maria", $customer->firstName);
    }

    public function testCreateWithValidUTF8Bytes()
    {
        $result = Braintree\Customer::create([
            'firstName' => "Jos\303\251"
        ]);
        $this->assertEquals(true, $result->success);
        $customer = $result->customer;
        $this->assertEquals("Jos\303\251", $customer->firstName);
    }

    public function testUpdateFromTransparentRedirect()
    {
        Test\Helper::suppressDeprecationWarnings();
        $customer = Braintree\Customer::createNoValidate();
        $queryString = $this->updateCustomerViaTr(
            [
                'customer' => [
                    'first_name' => 'Joe',
                    'last_name' => 'Martin',
                    'email' => 'joe.martin@example.com'
                ]
            ],
            [
                'customerId' => $customer->id
            ]
        );
        $result = Braintree\Customer::updateFromTransparentRedirect($queryString);
        $this->assertTrue($result->success);
        $this->assertEquals('Joe', $result->customer->firstName);
        $this->assertEquals('Martin', $result->customer->lastName);
        $this->assertEquals('joe.martin@example.com', $result->customer->email);
    }

    public function testUpdateFromTransparentRedirect_withParamsInTrData()
    {
        Test\Helper::suppressDeprecationWarnings();
        $customer = Braintree\Customer::createNoValidate();
        $queryString = $this->updateCustomerViaTr(
            [
            ],
            [
                'customerId' => $customer->id,
                'customer' => [
                    'firstName' => 'Joe',
                    'lastName' => 'Martin',
                    'email' => 'joe.martin@example.com'
                ]
            ]
        );
        $result = Braintree\Customer::updateFromTransparentRedirect($queryString);
        $this->assertTrue($result->success);
        $this->assertEquals('Joe', $result->customer->firstName);
        $this->assertEquals('Martin', $result->customer->lastName);
        $this->assertEquals('joe.martin@example.com', $result->customer->email);
    }

    public function testUpdateFromTransparentRedirect_withValidationErrors()
    {
        Test\Helper::suppressDeprecationWarnings();
        $customer = Braintree\Customer::createNoValidate();
        $queryString = $this->updateCustomerViaTr(
            [
                'customer' => [
                    'first_name' => str_repeat('x', 256),
                ]
            ],
            [
                'customerId' => $customer->id
            ]
        );
        $result = Braintree\Customer::updateFromTransparentRedirect($queryString);
        $this->assertFalse($result->success);
        $errors = $result->errors->forKey('customer')->onAttribute('firstName');
        $this->assertEquals(Braintree\Error\Codes::CUSTOMER_FIRST_NAME_IS_TOO_LONG, $errors[0]->code);
    }

    public function testUpdateFromTransparentRedirect_withUpdateExisting()
    {
        Test\Helper::suppressDeprecationWarnings();
        $customer = Braintree\Customer::create([
            'firstName' => 'Mike',
            'lastName' => 'Jones',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12',
                'cardholderName' => 'Mike Jones',
                'billingAddress' => [
                    'firstName' => 'Drew',
                    'lastName' => 'Smith'
                ]
            ]
        ])->customer;

        $queryString = $this->updateCustomerViaTr(
            [],
            [
                'customerId' => $customer->id,
                'customer' => [
                    'firstName' => 'New First',
                    'lastName' => 'New Last',
                    'creditCard' => [
                        'number' => '4111111111111111',
                        'expirationDate' => '05/13',
                        'cardholderName' => 'New Cardholder',
                        'options' => [
                            'updateExistingToken' => $customer->creditCards[0]->token
                        ],
                        'billingAddress' => [
                            'firstName' => 'New First Billing',
                            'lastName' => 'New Last Billing',
                            'options' => [
                                'updateExisting' => true
                            ]
                        ]
                    ]
                ]
            ]
        );
        $result = Braintree\Customer::updateFromTransparentRedirect($queryString);
        $this->assertTrue($result->success);

        $this->assertEquals(true, $result->success);
        $customer = $result->customer;
        $this->assertEquals('New First', $customer->firstName);
        $this->assertEquals('New Last', $customer->lastName);

        $this->assertEquals(1, sizeof($result->customer->creditCards));
        $creditCard = $customer->creditCards[0];
        $this->assertEquals('411111', $creditCard->bin);
        $this->assertEquals('1111', $creditCard->last4);
        $this->assertEquals('New Cardholder', $creditCard->cardholderName);
        $this->assertEquals('05/2013', $creditCard->expirationDate);

        $this->assertEquals(1, sizeof($result->customer->addresses));
        $address = $customer->addresses[0];
        $this->assertEquals($address, $creditCard->billingAddress);
        $this->assertEquals('New First Billing', $address->firstName);
        $this->assertEquals('New Last Billing', $address->lastName);
    }

    public function testSale_createsASaleUsingGivenToken()
    {
        $customer = Braintree\Customer::createNoValidate([
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);
        $creditCard = $customer->creditCards[0];
        $result = Braintree\Customer::sale($customer->id, [
            'amount' => '100.00'
        ]);
        $this->assertTrue($result->success);
        $this->assertEquals('100.00', $result->transaction->amount);
        $this->assertEquals($customer->id, $result->transaction->customerDetails->id);
        $this->assertEquals($creditCard->token, $result->transaction->creditCardDetails->token);
    }

    public function testSaleNoValidate_createsASaleUsingGivenToken()
    {
        $customer = Braintree\Customer::createNoValidate([
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);
        $creditCard = $customer->creditCards[0];
        $transaction = Braintree\Customer::saleNoValidate($customer->id, [
            'amount' => '100.00'
        ]);
        $this->assertEquals('100.00', $transaction->amount);
        $this->assertEquals($customer->id, $transaction->customerDetails->id);
        $this->assertEquals($creditCard->token, $transaction->creditCardDetails->token);
    }

    public function testSaleNoValidate_throwsIfInvalid()
    {
        $customer = Braintree\Customer::createNoValidate([
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);
        $creditCard = $customer->creditCards[0];
        $this->setExpectedException('Braintree\Exception\ValidationsFailed');
        Braintree\Customer::saleNoValidate($customer->id, [
            'amount' => 'invalid'
        ]);
    }

    public function testCredit_createsACreditUsingGivenCustomerId()
    {
        $customer = Braintree\Customer::createNoValidate([
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);
        $creditCard = $customer->creditCards[0];
        $result = Braintree\Customer::credit($customer->id, [
            'amount' => '100.00'
        ]);
        $this->assertTrue($result->success);
        $this->assertEquals('100.00', $result->transaction->amount);
        $this->assertEquals(Braintree\Transaction::CREDIT, $result->transaction->type);
        $this->assertEquals($customer->id, $result->transaction->customerDetails->id);
        $this->assertEquals($creditCard->token, $result->transaction->creditCardDetails->token);
    }

    public function testCreditNoValidate_createsACreditUsingGivenId()
    {
        $customer = Braintree\Customer::createNoValidate([
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);
        $creditCard = $customer->creditCards[0];
        $transaction = Braintree\Customer::creditNoValidate($customer->id, [
            'amount' => '100.00'
        ]);
        $this->assertEquals('100.00', $transaction->amount);
        $this->assertEquals(Braintree\Transaction::CREDIT, $transaction->type);
        $this->assertEquals($customer->id, $transaction->customerDetails->id);
        $this->assertEquals($creditCard->token, $transaction->creditCardDetails->token);
    }

    public function testCreditNoValidate_throwsIfInvalid()
    {
        $customer = Braintree\Customer::createNoValidate([
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);
        $creditCard = $customer->creditCards[0];
        $this->setExpectedException('Braintree\Exception\ValidationsFailed');
        Braintree\Customer::creditNoValidate($customer->id, [
            'amount' => 'invalid'
        ]);
    }

    public function createCustomerViaTr($regularParams, $trParams)
    {
        Test\Helper::suppressDeprecationWarnings();
        $trData = Braintree\TransparentRedirect::createCustomerData(
            array_merge($trParams, ["redirectUrl" => "http://www.example.com"])
        );
        return Test\Helper::submitTrRequest(
            Braintree\Customer::createCustomerUrl(),
            $regularParams,
            $trData
        );
    }

    public function updateCustomerViaTr($regularParams, $trParams)
    {
        Test\Helper::suppressDeprecationWarnings();
        $trData = Braintree\TransparentRedirect::updateCustomerData(
            array_merge($trParams, ["redirectUrl" => "http://www.example.com"])
        );
        return Test\Helper::submitTrRequest(
            Braintree\Customer::updateCustomerUrl(),
            $regularParams,
            $trData
        );
    }
}
