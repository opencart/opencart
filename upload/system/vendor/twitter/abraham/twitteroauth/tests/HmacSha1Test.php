<?php

namespace Abraham\TwitterOAuth\Tests;

use Abraham\TwitterOAuth\HmacSha1;

class HmacSha1Test extends AbstractSignatureMethodTest
{
    protected $name = 'HMAC-SHA1';

    public function getClass()
    {
        return new HmacSha1();
    }

    public function signatureDataProvider()
    {
        return array(
            array('5CoEcoq7XoKFjwYCieQvuzadeUA=', $this->getRequest(), $this->getConsumer(), $this->getToken()),
            array(
                'EBw0gHngam3BTx8kfPfNNSyKem4=',
                $this->getRequest(),
                $this->getConsumer('key', 'secret'),
                $this->getToken()
            ),
            array(
                'kDsHFZzws2a5M6cAQjfpdNBo+v8=',
                $this->getRequest(),
                $this->getConsumer('key', 'secret'),
                $this->getToken('key', 'secret')
            ),
            array('EBw0gHngam3BTx8kfPfNNSyKem4=', $this->getRequest(), $this->getConsumer('key', 'secret'), null),
        );
    }
}