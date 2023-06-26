<?php

namespace spec\Cardinity\Http\Guzzle;

use Cardinity\Http\ClientInterface;
use Cardinity\Http\Guzzle\ExceptionMapper;
use Cardinity\Method\MethodInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;
use PhpSpec\ObjectBehavior;

class ClientAdapterSpec extends ObjectBehavior
{
    function let(
        Client $client,
        ExceptionMapper $mapper
    ) {
        $this->beConstructedWith($client, $mapper);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Cardinity\Http\Guzzle\ClientAdapter');
    }

    function it_sends_post_and_returns_result(
        MethodInterface $method,
        Client $client,
        ResponseInterface $response
    ) {
        $response
            ->getBody()
            ->shouldBeCalled()
            ->willReturn(json_encode(['foo' => 'bar']))
        ;
        $client
            ->request('POST', 'https://api.cardinity.com/v1/', [])
            ->shouldBeCalled()
            ->willReturn($response)
        ;
        $this
            ->sendRequest($method, 'POST', 'https://api.cardinity.com/v1/', [])
            ->shouldReturn(['foo' => 'bar'])
        ;
    }

    function it_wraps_client_exceptions_with_ours(
        MethodInterface $method,
        ClientInterface $client,
        ExceptionMapper $mapper,
        ClientException $exception
    ) {
        $client
            ->request('POST', 'https://api.cardinity.com/v1/', [])
            ->willThrow($exception->getWrappedObject())
        ;
        $mapper
            ->get($exception->getWrappedObject(), $method)
            ->shouldBeCalled()
            ->willThrow('Cardinity\Exception\Request')
        ;
        $this
            ->shouldThrow('Cardinity\Exception\Request')
            ->duringSendRequest($method, 'POST', 'https://api.cardinity.com/v1/')
        ;
    }

    function it_handles_unexpected_exceptions(
        MethodInterface $method,
        ClientInterface $client,
        \Exception $exception
    )
    {
        $client
            ->request('POST', 'https://api.cardinity.com/v1/', [])
            ->willThrow($exception->getWrappedObject())
        ;
        $this
            ->shouldThrow('Cardinity\Exception\UnexpectedError')
            ->duringSendRequest($method, 'POST', 'https://api.cardinity.com/v1/')
        ;
    }
}
