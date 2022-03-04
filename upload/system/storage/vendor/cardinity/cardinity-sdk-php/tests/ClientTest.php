<?php

namespace Cardinity\Tests;

use Cardinity\Exception;
use Cardinity\Method\Payment;
use Cardinity\Method\ResultObject;
use Cardinity\Method\Payment\Create;

class ClientTest extends ClientTestCase
{ 
    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->payment3ds2Params = $this->get3ds2PaymentParams();
        parent::setUp();
    }

    /**
     * @dataProvider localhostUrlValidationDataProvider
     * @param string $address
     * @param string $expectedMessage
     * @return void
     */
    public function testLocalhostUrlExeptionRised($address, $expectedMessage)
    {
        $this->payment3ds2Params['threeds2_data']['notification_url'] = $address;
        $method = new Payment\Create($this->payment3ds2Params);

        $this->expectException(Exception\InvalidAttributeValue::class);
        $this->expectErrorMessage($expectedMessage);

        $payment = $this->client->call($method);
    }

    /**
     * @return array 
     */
    public function localhostUrlValidationDataProvider()
    {
        return [
            [
                'http://localhost',
                $this->getRestrictedHostnameErrorMsg('http://localhost'),
            ],[
                'https://localhost',
                $this->getRestrictedHostnameErrorMsg('https://localhost'),
            ],[
                'http://127.0.0.1',
                $this->getRestrictedHostnameErrorMsg('http://127.0.0.1'),
            ],[
                'https://127.0.0.1',
                $this->getRestrictedHostnameErrorMsg('https://127.0.0.1'),
            ]
        ];
    }


    /**
     * @dataProvider protocolUrlValidationDataProvider
     * @param string $address
     * @param string $expectedMessage
     * @return void
     */
    public function testProtocolUrlExeptionRised($address, $expectedMessage)
    {
        $this->payment3ds2Params['threeds2_data']['notification_url'] = $address;
        $method = new Payment\Create($this->payment3ds2Params);

        $this->expectException(Exception\InvalidAttributeValue::class);
        $this->expectErrorMessage($expectedMessage);

        $payment = $this->client->call($method);
    }

    /**
     * @return array 
     */
    public function protocolUrlValidationDataProvider()
    {
        return [
            [
                'ftp://example.com',
                'The protocol of "ftp://example.com" should be "http" or "https".'
            ],[
                'htt://example.com',
                'The protocol of "htt://example.com" should be "http" or "https".'
            ],[
                'f://example.com',
                'The protocol of "f://example.com" should be "http" or "https".'
            ]
        ];
    }


    /**
     * @dataProvider localhostUrlNotStringDataProvider
     * @return void
     */
    public function testLocalhostUrlNotStringExeptionRised($address, $expectedMessage)
    {
        $this->payment3ds2Params['threeds2_data']['notification_url'] = $address;
        $method = new Payment\Create($this->payment3ds2Params);

        $this->expectException(Exception\InvalidAttributeValue::class);
        $this->expectErrorMessage($expectedMessage);

        $payment = $this->client->call($method);
    }

    /**
     * @return array 
     */
    public function localhostUrlNotStringDataProvider()
    {
        return [
            [
                123,
                'This value should be of type string.'
            ],[
                null,
                'This value should not be blank.'
            ],
        ];
    }

    /**
     * @dataProvider browserInfoIpAddressDataProvider
     * @param array $address
     * @param string $expectedMessage
     * @return void
     */
    public function testBrowserInfoIpAddressExeptionRised($address, $expectedMessage)
    {
        $paymentParams = $this->get3ds2PaymentParams($address);
        $method = new Payment\Create($paymentParams);

        $this->expectException(Exception\InvalidAttributeValue::class);
        $this->expectErrorMessage($expectedMessage);

        $payment = $this->client->call($method);
    }

    /**
     * @return array 
     */
    public function browserInfoIpAddressDataProvider()
    {
        return [
            [
                ['ip_address' => 123],
                'This value should be of type string.'
            ],[
                ['ip_address' => ''],
                'This value should not be blank.'
            ],[
                ['ip_address' => 'http://localhost'],
                $this->getRestrictedHostnameErrorMsg('http://localhost'),
            ],[
                ['ip_address' => 'https://localhost'],
                $this->getRestrictedHostnameErrorMsg('https://localhost'),
            ],[
                ['ip_address' => 'http://127.0.0.1'],
                $this->getRestrictedHostnameErrorMsg('http://127.0.0.1'),
            ],[
                ['ip_address' => 'https://127.0.0.1'],
                $this->getRestrictedHostnameErrorMsg('https://127.0.0.1'),
            ],
        ];
    }

    /**
     * @param string $address url or ip
     * @return string
     */
    private function getRestrictedHostnameErrorMsg(string $address)
    {
        return 'The url "' . $address . 
            '" contains restricted values. Do not use "localhost" or "127.0.0.1".';
    }
}
