<?php

namespace Cardinity\Tests;

use Cardinity\Client;
use Cardinity\Method\Payment;

class UnexpectedErrorTest
{

    /**
     * @return void
     */
    public function testUnexpectedError()
    {

        $faultyCient = Client::create([
            'consumerKey' => 'foo',
            'consumerSecret' => 'bar',
            'apiEndpoint' => "https://www.cardinity.com/"
        ]);

        $method = new Payment\Get('foobar');

        $this->expectException(\Cardinity\Exception\UnexpectedError::class);
        $faultyCient->call($method);
    }

}
