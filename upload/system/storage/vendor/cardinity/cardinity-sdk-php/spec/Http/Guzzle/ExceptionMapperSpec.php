<?php

namespace spec\Cardinity\Http\Guzzle;

use Cardinity\Method\Error;
use Cardinity\Method\MethodInterface;
use Cardinity\Method\Payment\Payment;
use Cardinity\Method\ResultObjectMapperInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Message\Request;
use GuzzleHttp\Message\Response;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ExceptionMapperSpec extends ObjectBehavior
{
    function let(
        ClientException $exception,
        Request $request,
        Response $response,
        ResultObjectMapperInterface $resultMapper
    ) {
        $this->beConstructedWith($resultMapper);

        $exception->beConstructedWith([
            'Message',
            $request->getWrappedObject(),
            $response->getWrappedObject()
        ]);
    }

    function it_maps_expected_exception_code(
        ClientException $exception,
        Response $response,
        MethodInterface $method
    ) {
        $response->json()->willReturn([]);
        $response->getStatusCode()->willReturn(400);
        $exception->getResponse()->willReturn($response);

        $this
            ->get($exception, $method)
            ->shouldReturnAnInstanceOf('Cardinity\Exception\ValidationFailed')
        ;
    }

    function it_handles_unexpected_exception_code(
        ClientException $exception,
        Response $response,
        MethodInterface $method
    ) {
        $response->json()->willReturn([]);
        $response->getStatusCode()->willReturn(999);
        $exception->getResponse()->willReturn($response);
        
        $this
            ->get($exception, $method)
            ->shouldReturnAnInstanceOf('Cardinity\Exception\UnexpectedResponse')
        ;
    }

    function it_maps_error_response_to_error_result_object(
        ClientException $exception,
        Response $response,
        ResultObjectMapperInterface $resultMapper,
        MethodInterface $method
    ) {
        $result = ['errors' => ['error' => 'Error string']];
        $resultObject = new Error();

        $response->getStatusCode()->willReturn(400);
        $response->json()->willReturn($result);

        $method->createResultObject()->willReturn($resultObject);
        $exception->getResponse()->willReturn($response);

        $resultMapper
            ->map($result, $resultObject)
            ->shouldBeCalled()
            ->willReturn($resultObject)
        ;

        $this
            ->get($exception, $method)
            ->getResult()
            ->shouldReturn($resultObject)
        ;
    }

    function it_maps_declined_response_402_to_payment_result_object(
        ClientException $exception,
        Response $response,
        ResultObjectMapperInterface $resultMapper,
        MethodInterface $method
    ) {
        $result = ['error' => 'Error string'];
        $resultObject = new Payment();

        $response->getStatusCode()->willReturn(402);
        $response->json()->willReturn($result);

        $method->createResultObject()->willReturn($resultObject);
        $exception->getResponse()->willReturn($response);

        $resultMapper
            ->map($result, $resultObject)
            ->shouldBeCalled()
            ->willReturn($resultObject)
        ;

        $this
            ->get($exception, $method)
            ->getResult()
            ->shouldReturn($resultObject)
        ;
    }
}
