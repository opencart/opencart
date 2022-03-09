<?php

namespace spec\Cardinity\Exception;

use Cardinity\Method\Error;
use Cardinity\Method\Payment\Payment;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RequestSpec extends ObjectBehavior
{
    private $result = [
        'errors' => [
            [
                'field' => 'currency',
                'rejected' => 'EGR',
                'message' => 'invalid or unsupported currency',
            ],
            [
                'field' => 'payment_instrument.exp_month',
                'rejected' => 13,
                'message' => 'must be between 1 and 12',
            ]
        ]
    ];

    private $error;

    function let(\RuntimeException $exception)
    {
        $this->error = new Error();
        $this->error->setErrors($this->result['errors']);

        $this->beConstructedWith(
            $exception,
            $this->error
        );
    }

    function it_should_extend_runtime()
    {
        $this->shouldHaveType('Cardinity\Exception\Runtime');
    }

    function it_stores_previous_exception(\RuntimeException $exception)
    {
        $this->getPrevious()->shouldReturn($exception);
    }

    function it_stores_result()
    {
        $this->getResult()->shouldReturn($this->error);
    }

    function it_should_have_message_containing_response_data()
    {
        $string = 'Response data: ' . serialize($this->error);
        $this->getMessage()->shouldEndWith($string);
    }

    function it_returns_erros_from_error_result_object()
    {
        $this->getErrors()->shouldReturn($this->result['errors']);
    }

    function it_returns_errors_as_string()
    {
        $this
            ->getErrorsAsString()
            ->shouldReturn("currency: invalid or unsupported currency ('EGR' given);
payment_instrument.exp_month: must be between 1 and 12 ('13' given);")
        ;
    }

        function it_returns_erros_from_payment_result_object(\RuntimeException $exception)
    {
        $msg = 'Payment error';

        $payment = new Payment();
        $payment->setError($msg);
        $this->beConstructedWith(
            $exception,
            $payment
        );

        $this->getErrors()->shouldReturn([['field' => 'status', 'message' => $msg]]);
        $this->getErrorsAsString()->shouldReturn("status: Payment error;");
    }

}
