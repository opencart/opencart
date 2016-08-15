<?php

namespace Abraham\TwitterOAuth\Tests;

use Abraham\TwitterOAuth\Token;

class TokenTest extends \PHPUnit_Framework_TestCase {
    /**
     * @dataProvider tokenProvider
     */
    public function testToString($expected, $key, $secret)
    {
        $token = new Token($key, $secret);

        $this->assertEquals($expected, $token->__toString());
    }

    public function tokenProvider()
    {
        return array(
            array('oauth_token=key&oauth_token_secret=secret', 'key', 'secret'),
            array('oauth_token=key%2Bkey&oauth_token_secret=secret', 'key+key', 'secret'),
            array('oauth_token=key~key&oauth_token_secret=secret', 'key~key', 'secret'),
        );
    }
}