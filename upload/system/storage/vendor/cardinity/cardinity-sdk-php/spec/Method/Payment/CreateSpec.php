<?php

namespace spec\Cardinity\Method\Payment;

use PhpSpec\ObjectBehavior;

class CreateSpec extends ObjectBehavior
{
    private $options;

    function let()
    {
        $browserInfo = [
            'accept_header' => 'Some header',
            'browser_language' => 'LT',
            'screen_width' => 390,
            'screen_height' => 400,
            'challenge_window_size' => '390x400',
            'user_agent' => 'super user agent',
            'color_depth' => 24,
            'time_zone' => -60,
            // 'ip_address' => '192.168.0.1',
        ];
        $billingAddress = [
            'address_line1' => 'first address line',
            'city' => 'balbieriskis',
            'country' => 'LT',
            'postal_code' => '0234'
        ];
        $threeds2Data['notification_url'] = 'http://localhost:8000/3dsv2_callback.php';
        $threeds2Data['browser_info'] = $browserInfo;

        $this->options = [
            'amount' => 12.99,
            'currency' => 'EUR',
            'settle' => true,
            'order_id' => 'ABC123',
            'description' => 'Description',
            'country' => 'LT',
            'payment_method' => 'card',
            'payment_instrument' => [
                'pan' => '123456789123',
                'exp_year' => '2014',
                'exp_month' => '12',
                'cvc' => '456',
                'holder' => 'Mr Tester',
            ],
            'threeds2_data' => $threeds2Data
        ];
        $this->beConstructedWith($this->options);
    }

    function it_is_initializable()
    {
        $this->shouldImplement('Cardinity\Method\MethodInterface');
    }

    function it_contains_loaded_options()
    {
        $this->getAttributes()->shouldReturn($this->options);
    }

    function it_has_action()
    {
        $this->getAction()->shouldReturn('payments');
    }

    function it_has_method()
    {
        $this->getMethod()->shouldReturn('POST');
    }

    function it_has_body()
    {
        $this->getAttributes()->shouldBeArray();
    }

    function it_creates_result_object()
    {
        $this->createResultObject()->shouldReturnAnInstanceOf('Cardinity\Method\Payment\Payment');
    }

    function it_has_validation_constraints()
    {
        $this->getValidationConstraints()->shouldReturnAnInstanceOf('Symfony\Component\Validator\Constraint');
    }
}
