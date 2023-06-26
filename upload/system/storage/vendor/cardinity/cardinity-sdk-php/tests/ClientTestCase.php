<?php
namespace Cardinity\Tests;

use Cardinity\Client;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Cardinity\Method\Payment;
use PHPUnit\Framework\TestCase;

class ClientTestCase extends TestCase
{
    public function setUp()
    {
        $log = Client::LOG_NONE;

        // Use monolog logger to log requests into the file
        // $log = new Logger('requests');
        // $log->pushHandler(new StreamHandler(__DIR__ . '/info.log', Logger::INFO));

        $this->client = Client::create($this->getConfig(), $log);

        $this->assertInstanceOf('Cardinity\Client', $this->client);
    }

    protected function getConfig()
    {
        return [
            'consumerKey' => CONSUMER_KEY,
            'consumerSecret' => CONSUMER_SECRET,
        ];
    }

    protected function getPaymentParams()
    {
        return [
            'amount' => 50.00,
            'currency' => 'EUR',
            'settle' => false,
            'description' => 'some description',
            'order_id' => '12345678',
            'country' => 'LT',
            'payment_method' => Payment\Create::CARD,
            'payment_instrument' => [
                'pan' => '4111111111111111',
                'exp_year' => 2021,
                'exp_month' => 12,
                'cvc' => '456',
                'holder' => 'Mike Dough'
            ],
        ];
    }
}
