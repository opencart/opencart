<?php
namespace Todaymade\Daux\Format\Confluence;

use PHPUnit\Framework\TestCase;

class ApiTest extends TestCase
{
    // this test supports upgrade Guzzle to version 6
    public function testClientOptions()
    {
        $api = new Api('http://test.com/', 'user', 'pass');
        $this->assertEquals('test.com', $api->getClient()->getConfig()['base_uri']->getHost());
    }
}
