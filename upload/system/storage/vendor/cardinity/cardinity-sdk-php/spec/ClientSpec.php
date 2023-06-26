<?php

namespace spec\Cardinity;

use Cardinity\Http\ClientInterface;
use Cardinity\Method\MethodInterface;
use Cardinity\Method\MethodResultCollectionInterface;
use Cardinity\Method\ValidatorInterface;
use Cardinity\Method\Payment\Payment;
use Cardinity\Method\ResultObjectMapperInterface;
use PhpSpec\ObjectBehavior;

class ClientSpec extends ObjectBehavior
{
    function let(
        ClientInterface $client,
        ValidatorInterface $validator,
        ResultObjectMapperInterface $mapper
    ) {
        $this->beConstructedWith(
            $client,
            $validator,
            $mapper
        );
    }

    function it_constructs_via_factory()
    {
        $this::create(['consumerKey' => '', 'consumerSecret' => ''])->shouldReturnAnInstanceOf('Cardinity\Client');
    }

    function it_maps_result_to_object_by_posting_json_body(
        MethodInterface $method,
        ClientInterface $client,
        ValidatorInterface $validator,
        ResultObjectMapperInterface $mapper
    ) {
        $result = ['id' => '3c4e8dcf'];
        $resultObject = new Payment();

        $method->getMethod()->willReturn('POST');
        $method->getAction()->willReturn('payment');
        $method->getAttributes()->willReturn([]);
        $method->createResultObject()->willReturn($resultObject);

        $validator->validate($method)->shouldBeCalled();
        $mapper->map($result, $resultObject)->shouldBeCalled()->willReturn($resultObject);

        $client
            ->sendRequest(
                $method,
                'POST',
                'payment',
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                    'body' => '{}',
                ]
            )
            ->shouldBeCalled()
            ->willReturn($result)
        ;

        $this->call($method)->shouldReturn($resultObject);
    }

    function it_maps_result_containing_collection_of_items(
        MethodResultCollectionInterface $method,
        ClientInterface $client,
        ValidatorInterface $validator,
        ResultObjectMapperInterface $mapper
    ) {
        $result = [
            ['id' => '3c4e8dcf']
        ];
        $resultObject = new Payment();

        $method->getMethod()->willReturn('POST');
        $method->getAction()->willReturn('payment');
        $method->getAttributes()->willReturn([]);
        $method->createResultObject()->willReturn($resultObject);

        $validator->validate($method)->shouldBeCalled();
        $mapper->mapCollection($result, $method)->shouldBeCalled()->willReturn([$resultObject]);

        $client
            ->sendRequest(
                $method,
                'POST',
                'payment',
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                    'body' => '{}',
                ]
            )
            ->shouldBeCalled()
            ->willReturn($result)
        ;

        $this->call($method)->shouldReturn([$resultObject]);
    }

    function it_converts_float_numbers_to_string_for_json_body(
        MethodInterface $method,
        ClientInterface $client,
        ValidatorInterface $validator,
        ResultObjectMapperInterface $mapper
    ) {
        $result = ['amount' => 50.00];
        $resultObject = new Payment();

        $method->getMethod()->willReturn('POST');
        $method->getAction()->willReturn('payment');
        $method->getAttributes()->willReturn(['amount' => 50.00]);
        $method->createResultObject()->willReturn($resultObject);

        $validator->validate($method)->shouldBeCalled();
        $mapper->map($result, $resultObject)->shouldBeCalled()->willReturn($resultObject);

        $client
            ->sendRequest(
                $method,
                'POST',
                'payment',
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                    'body' => '{"amount":"50.00"}',
                ]
            )
            ->shouldBeCalled()
            ->willReturn($result)
        ;

        $this->call($method)->shouldReturn($resultObject);
    }

    function it_gets_result_object_by_sending_get_query_params(
        MethodInterface $method,
        ClientInterface $client,
        ValidatorInterface $validator,
        ResultObjectMapperInterface $mapper
    ) {
        $result = ['amount' => 50.00];
        $resultObject = new Payment();

        $method->getMethod()->willReturn('GET');
        $method->getAction()->willReturn('payment');
        $method->getAttributes()->willReturn(['field' => 'value']);
        $method->createResultObject()->willReturn($resultObject);

        $validator->validate($method)->shouldBeCalled();
        $mapper->map($result, $resultObject)->shouldBeCalled()->willReturn($resultObject);

        $client
            ->sendRequest(
                $method,
                'GET',
                'payment',
                ['query' => ['field' => 'value']]
            )
            ->shouldBeCalled()
            ->willReturn($result)
        ;

        $this->call($method)->shouldReturn($resultObject);
    }

    function it_performs_request_without_validation(
        MethodInterface $method,
        ClientInterface $client,
        ResultObjectMapperInterface $mapper
    ) {
        $result = ['amount' => 50.00];
        $resultObject = new Payment();

        $method->getMethod()->willReturn('GET');
        $method->getAction()->willReturn('payment');
        $method->getAttributes()->willReturn(['field' => 'value']);
        $method->createResultObject()->willReturn($resultObject);

        $mapper->map($result, $resultObject)->shouldBeCalled()->willReturn($resultObject);

        $client
            ->sendRequest(
                $method,
                'GET',
                'payment',
                ['query' => ['field' => 'value']]
            )
            ->shouldBeCalled()
            ->willReturn($result)
        ;

        $this->callNoValidate($method)->shouldReturn($resultObject);
    }
}
