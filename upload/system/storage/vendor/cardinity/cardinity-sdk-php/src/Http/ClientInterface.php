<?php

namespace Cardinity\Http;

use Cardinity\Method\MethodInterface;

interface ClientInterface
{
    /**
     * Send HTTP request
     * @param MethodInterface $method
     * @param string $requestMethod POST|GET|PATCH
     * @param string $url http URL
     * @param array $options query options. Query params goes under 'query' key.
     * @return array
     */
    public function sendRequest(MethodInterface $method, $requestMethod, $url, array $options = []);
}
