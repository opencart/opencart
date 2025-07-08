<?php
namespace Cardinity\Tests;

use Cardinity\Client;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Cardinity\Method\Payment;
use PHPUnit\Framework\TestCase;

class ClientTestCase extends TestCase
{
    public $client;
    public $payment3ds2Params;
    public $paymentParams;
    public $paymentId;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $log = Client::LOG_NONE;

        $this->client = Client::create($this->getConfig(), $log);

        $this->assertInstanceOf('Cardinity\Client', $this->client);
    }

    /**
     * @return array
     */
    protected function getConfig()
    {
        return [
            'consumerKey' => $_ENV['CONSUMER_KEY'],
            'consumerSecret' => $_ENV['CONSUMER_SECRET'],
        ];
    }

    /**
     * @return array
     */
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
                'exp_year' => date('Y') + 4,
                'exp_month' => 12,
                'cvc' => '456',
                'holder' => 'Mike Dough'
            ],
        ];
    }

    /**
     * @param array optional
     * @return array
     */
    public function get3ds2PaymentParams($browserData = [])
    {
        return array_merge(
            $this->getPaymentParams(),
            [
                'threeds2_data' => $this->getThreeDS2Data($browserData)
            ]
        );
    }

    /**
     * @return Payment\Payment
     */
    public function getPayment()
    {
        $payment = new Payment\Payment();
        $payment->setId('foo');
        $payment->setType('bar');
        $payment->setCurrency(null);
        $payment->setAmount('55.00');
        $payment->setPaymentMethod(Payment\Create::CARD);
        return $payment;
    }

    /**
     * @param array optional
     * @return array
     */
    public function getBrowserInfo($args = [])
    {
        $browserInfo = [
            'accept_header' => 'Some header',
            'browser_language' => 'LT',
            'screen_width' => 390,
            'screen_height' => 400,
            'challenge_window_size' => '390x400',
            'user_agent' => 'super user agent',
            'color_depth' => 24,
            'time_zone' => -60,
        ];
        if ($args) {
            foreach($args as $key => $val) {
                $browserInfo[$key] = $val;
            }
        }
        return $browserInfo;
    }

    /**
     * @param array optional
     * @return array
     */
    public function getAddress($args = [])
    {
        $address = [
            'address_line1' => 'first address line',
            'city' => 'balbieriskis',
            'country' => 'LT',
            'postal_code' => '0234'
        ];
        if ($args && isset($args['address_line2'])) {
            $address['address_line2'] = $args['address_line2'];
        }
        if ($args && isset($args['address_line3'])) {
            $address['address_line3'] = $args['address_line3'];
        }
        if ($args && isset($args['state'])) {
            $address['state'] = $args['state'];
        }
        return $address;
    }

    /**
     * @param array optional
     * @return array
     */
    public function getThreeDS2Data($browserData = [])
    {
        return [
            'notification_url' => 'https://notification.url/',
            'browser_info' => $this->getBrowserInfo($browserData),
        ];
    }

    /**
     * @return Payment\PaymentInstrumentCard
     */
    public function getCard()
    {
        $card = new Payment\PaymentInstrumentCard();
        $card->setCardBrand('Visa');
        $card->setPan('4447');
        $card->setExpYear(date('Y') + 4);
        $card->setExpMonth(11);
        $card->setHolder('James Bond');
        return $card;
    }
}
