<?php
namespace Test\Integration;

require_once dirname(__DIR__) . '/Setup.php';

use Test\Setup;
use Braintree;

class AddressTest extends Setup
{
    public function testCreate()
    {
        $customer = Braintree\Customer::createNoValidate();
        $result = Braintree\Address::create([
            'customerId' => $customer->id,
            'firstName' => 'Dan',
            'lastName' => 'Smith',
            'company' => 'Braintree',
            'streetAddress' => '1 E Main St',
            'extendedAddress' => 'Apt 1F',
            'locality' => 'Chicago',
            'region' => 'IL',
            'postalCode' => '60622',
            'countryName' => 'Vatican City',
            'countryCodeAlpha2' => 'VA',
            'countryCodeAlpha3' => 'VAT',
            'countryCodeNumeric' => '336'
        ]);
        $this->assertTrue($result->success);
        $address = $result->address;
        $this->assertEquals('Dan', $address->firstName);
        $this->assertEquals('Smith', $address->lastName);
        $this->assertEquals('Braintree', $address->company);
        $this->assertEquals('1 E Main St', $address->streetAddress);
        $this->assertEquals('Apt 1F', $address->extendedAddress);
        $this->assertEquals('Chicago', $address->locality);
        $this->assertEquals('IL', $address->region);
        $this->assertEquals('60622', $address->postalCode);
        $this->assertEquals('Vatican City', $address->countryName);
        $this->assertEquals('VA', $address->countryCodeAlpha2);
        $this->assertEquals('VAT', $address->countryCodeAlpha3);
        $this->assertEquals('336', $address->countryCodeNumeric);
    }

    public function testGatewayCreate()
    {
        $customer = Braintree\Customer::createNoValidate();

        $gateway = new Braintree\Gateway([
            'environment' => 'development',
            'merchantId' => 'integration_merchant_id',
            'publicKey' => 'integration_public_key',
            'privateKey' => 'integration_private_key'
        ]);
        $result = $gateway->address()->create([
            'customerId' => $customer->id,
            'streetAddress' => '1 E Main St',
            'locality' => 'Chicago',
            'region' => 'IL',
            'postalCode' => '60622',
        ]);

        $this->assertTrue($result->success);
        $address = $result->address;
        $this->assertEquals('1 E Main St', $address->streetAddress);
        $this->assertEquals('Chicago', $address->locality);
        $this->assertEquals('IL', $address->region);
        $this->assertEquals('60622', $address->postalCode);
    }

    public function testCreate_withValidationErrors()
    {
        $customer = Braintree\Customer::createNoValidate();
        $result = Braintree\Address::create([
            'customerId' => $customer->id,
            'countryName' => 'Invalid States of America'
        ]);
        $this->assertFalse($result->success);
        $countryErrors = $result->errors->forKey('address')->onAttribute('countryName');
        $this->assertEquals(Braintree\Error\Codes::ADDRESS_COUNTRY_NAME_IS_NOT_ACCEPTED, $countryErrors[0]->code);
    }

    public function testCreate_withValidationErrors_onCountryCodes()
    {
        $customer = Braintree\Customer::createNoValidate();
        $result = Braintree\Address::create([
            'customerId' => $customer->id,
            'countryCodeAlpha2' => 'ZZ'
        ]);
        $this->assertFalse($result->success);
        $countryErrors = $result->errors->forKey('address')->onAttribute('countryCodeAlpha2');
        $this->assertEquals(Braintree\Error\Codes::ADDRESS_COUNTRY_CODE_ALPHA2_IS_NOT_ACCEPTED, $countryErrors[0]->code);
    }

    public function testCreate_withNotFoundErrors()
    {
        $this->setExpectedException('Braintree\Exception\NotFound','Customer nonExistentCustomerId not found.');
        $result = Braintree\Address::create([
            'customerId' => 'nonExistentCustomerId',
        ]);
    }

    public function testCreateNoValidate()
    {
        $customer = Braintree\Customer::createNoValidate();
        $address = Braintree\Address::createNoValidate([
            'customerId' => $customer->id,
            'firstName' => 'Dan',
            'lastName' => 'Smith',
            'company' => 'Braintree',
            'streetAddress' => '1 E Main St',
            'extendedAddress' => 'Apt 1F',
            'locality' => 'Chicago',
            'region' => 'IL',
            'postalCode' => '60622',
            'countryName' => 'United States of America'
        ]);
        $this->assertEquals('Dan', $address->firstName);
        $this->assertEquals('Smith', $address->lastName);
        $this->assertEquals('Braintree', $address->company);
        $this->assertEquals('1 E Main St', $address->streetAddress);
        $this->assertEquals('Apt 1F', $address->extendedAddress);
        $this->assertEquals('Chicago', $address->locality);
        $this->assertEquals('IL', $address->region);
        $this->assertEquals('60622', $address->postalCode);
        $this->assertEquals('United States of America', $address->countryName);
    }

    public function testCreateNoValidate_withValidationErrors()
    {
        $customer = Braintree\Customer::createNoValidate();
        $this->setExpectedException('Braintree\Exception\ValidationsFailed');
        Braintree\Address::createNoValidate([
            'customerId' => $customer->id,
            'countryName' => 'Invalid States of America'
        ]);
    }

    public function testDelete()
    {
        $customer = Braintree\Customer::createNoValidate();
        $address = Braintree\Address::createNoValidate([
            'customerId' => $customer->id,
            'streetAddress' => '1 E Main St'
        ]);
        Braintree\Address::find($customer->id, $address->id);
        Braintree\Address::delete($customer->id, $address->id);
        $this->setExpectedException('Braintree\Exception\NotFound');
        Braintree\Address::find($customer->id, $address->id);
    }

    public function testFind()
    {
        $customer = Braintree\Customer::createNoValidate();
        $result = Braintree\Address::create([
            'customerId' => $customer->id,
            'firstName' => 'Dan',
            'lastName' => 'Smith',
            'company' => 'Braintree',
            'streetAddress' => '1 E Main St',
            'extendedAddress' => 'Apt 1F',
            'locality' => 'Chicago',
            'region' => 'IL',
            'postalCode' => '60622',
            'countryName' => 'United States of America'
        ]);
        $this->assertTrue($result->success);
        $address = Braintree\Address::find($customer->id, $result->address->id);
        $this->assertEquals('Dan', $address->firstName);
        $this->assertEquals('Smith', $address->lastName);
        $this->assertEquals('Braintree', $address->company);
        $this->assertEquals('1 E Main St', $address->streetAddress);
        $this->assertEquals('Apt 1F', $address->extendedAddress);
        $this->assertEquals('Chicago', $address->locality);
        $this->assertEquals('IL', $address->region);
        $this->assertEquals('60622', $address->postalCode);
        $this->assertEquals('United States of America', $address->countryName);
    }

    public function testFind_whenNotFound()
    {
        $customer = Braintree\Customer::createNoValidate();
        $this->setExpectedException('Braintree\Exception\NotFound');
        Braintree\Address::find($customer->id, 'does-not-exist');
    }

    public function testUpdate()
    {
        $customer = Braintree\Customer::createNoValidate();
        $address = Braintree\Address::createNoValidate([
            'customerId' => $customer->id,
            'firstName' => 'Old First',
            'lastName' => 'Old Last',
            'company' => 'Old Company',
            'streetAddress' => '1 E Old St',
            'extendedAddress' => 'Apt Old',
            'locality' => 'Old Chicago',
            'region' => 'Old Region',
            'postalCode' => 'Old Postal',
            'countryName' => 'United States of America',
            'countryCodeAlpha2' => 'US',
            'countryCodeAlpha3' => 'USA',
            'countryCodeNumeric' => '840'
        ]);
        $result = Braintree\Address::update($customer->id, $address->id, [
            'firstName' => 'New First',
            'lastName' => 'New Last',
            'company' => 'New Company',
            'streetAddress' => '1 E New St',
            'extendedAddress' => 'Apt New',
            'locality' => 'New Chicago',
            'region' => 'New Region',
            'postalCode' => 'New Postal',
            'countryName' => 'Mexico',
            'countryCodeAlpha2' => 'MX',
            'countryCodeAlpha3' => 'MEX',
            'countryCodeNumeric' => '484'
        ]);
        $this->assertTrue($result->success);
        $address = $result->address;
        $this->assertEquals('New First', $address->firstName);
        $this->assertEquals('New Last', $address->lastName);
        $this->assertEquals('New Company', $address->company);
        $this->assertEquals('1 E New St', $address->streetAddress);
        $this->assertEquals('Apt New', $address->extendedAddress);
        $this->assertEquals('New Chicago', $address->locality);
        $this->assertEquals('New Region', $address->region);
        $this->assertEquals('New Postal', $address->postalCode);
        $this->assertEquals('Mexico', $address->countryName);
        $this->assertEquals('MX', $address->countryCodeAlpha2);
        $this->assertEquals('MEX', $address->countryCodeAlpha3);
        $this->assertEquals('484', $address->countryCodeNumeric);
    }

    public function testUpdate_withValidationErrors()
    {
        $customer = Braintree\Customer::createNoValidate();
        $address = Braintree\Address::createNoValidate([
            'customerId' => $customer->id,
            'streetAddress' => '1 E Main St'
        ]);
        $result = Braintree\Address::update(
            $customer->id,
            $address->id,
            [
                'countryName' => 'Invalid States of America'
            ]
        );
        $this->assertFalse($result->success);
        $countryErrors = $result->errors->forKey('address')->onAttribute('countryName');
        $this->assertEquals(Braintree\Error\Codes::ADDRESS_COUNTRY_NAME_IS_NOT_ACCEPTED, $countryErrors[0]->code);
    }

    public function testUpdate_withValidationErrors_onCountry()
    {
        $customer = Braintree\Customer::createNoValidate();
        $address = Braintree\Address::createNoValidate([
            'customerId' => $customer->id,
            'streetAddress' => '1 E Main St'
        ]);
        $result = Braintree\Address::update(
            $customer->id,
            $address->id,
            [
                'countryCodeAlpha2' => 'MU',
                'countryCodeAlpha3' => 'MYT'
            ]
        );
        $this->assertFalse($result->success);
        $countryErrors = $result->errors->forKey('address')->onAttribute('base');
        $this->assertEquals(Braintree\Error\Codes::ADDRESS_INCONSISTENT_COUNTRY, $countryErrors[0]->code);
    }


    public function testUpdateNoValidate()
    {
        $customer = Braintree\Customer::createNoValidate();
        $createdAddress = Braintree\Address::createNoValidate([
            'customerId' => $customer->id,
            'firstName' => 'Old First',
            'lastName' => 'Old Last',
            'company' => 'Old Company',
            'streetAddress' => '1 E Old St',
            'extendedAddress' => 'Apt Old',
            'locality' => 'Old Chicago',
            'region' => 'Old Region',
            'postalCode' => 'Old Postal',
            'countryName' => 'United States of America'
        ]);
        $address = Braintree\Address::updateNoValidate($customer->id, $createdAddress->id, [
            'firstName' => 'New First',
            'lastName' => 'New Last',
            'company' => 'New Company',
            'streetAddress' => '1 E New St',
            'extendedAddress' => 'Apt New',
            'locality' => 'New Chicago',
            'region' => 'New Region',
            'postalCode' => 'New Postal',
            'countryName' => 'Mexico'
        ]);
        $this->assertEquals('New First', $address->firstName);
        $this->assertEquals('New Last', $address->lastName);
        $this->assertEquals('New Company', $address->company);
        $this->assertEquals('1 E New St', $address->streetAddress);
        $this->assertEquals('Apt New', $address->extendedAddress);
        $this->assertEquals('New Chicago', $address->locality);
        $this->assertEquals('New Region', $address->region);
        $this->assertEquals('New Postal', $address->postalCode);
        $this->assertEquals('Mexico', $address->countryName);
    }
}
