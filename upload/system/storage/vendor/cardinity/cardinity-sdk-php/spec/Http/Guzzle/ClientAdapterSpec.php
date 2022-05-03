<?php

namespace spec\Cardinity\Http\Guzzle;

use Cardinity\Exception;
use Cardinity\Http\ClientInterface;
use Cardinity\Http\Guzzle\ExceptionMapper;
use Cardinity\Method\MethodInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Message\ResponseInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ClientAdapterSpec extends ObjectBehavior
{
    function let(
        Client $client,
        ExceptionMapper $mapper,
        RequestInterface $request
    ) {
        $this->beConstructedWith($client, $mapper);

        $client
            ->createRequest('POST', 'https://api.cardinity.com/v1/', [])
            ->willReturn($request)
        ;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Cardinity\Http\Guzzle\ClientAdapter');
    }

    function it_sends_post_and_returns_result(
        MethodInterface $method,
        Client $client,
        RequestInterface $request,
        ResponseInterface $response
    ) {
        $response
            ->json()
            ->shouldBeCalled()
            ->willReturn(['foo' => 'bar'])
        ;
        $client
            ->send($request)
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
        RequestInterface $request,
        ExceptionMapper $mapper,
        ClientException $exception
    ) {
        $client
            ->send($request)
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
        RequestInterface $request,
        \Exception $exception
    )
    {
        $client
            ->send($request)
            ->willThrow($exception->getWrappedObject())
        ;
        $this
            ->shouldThrow('Cardinity\Exception\UnexpectedError')
            ->duringSendRequest($method, 'POST', 'https://api.cardinity.com/v1/')
        ;
    }
}
