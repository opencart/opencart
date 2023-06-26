<?php

namespace spec\Cardinity\Method;

use Cardinity\Method\MethodResultCollectionInterface;
use Cardinity\Method\Payment\AuthorizationInformation;
use Cardinity\Method\Payment\Payment;
use Cardinity\Method\Payment\PaymentInstrumentCard;
use Cardinity\Method\Payment\PaymentInstrumentRecurring;
use PhpSpec\ObjectBehavior;

class ResultObjectMapperSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldImplement('Cardinity\Method\ResultObjectMapper');
    }

    function it_maps_array_to_object(Payment $object)
    {
        $data = [
            'id' => 32,
            'type' => 'my_type',
        ];

        $object->setId(32)->shouldBeCalled();
        $object->setType('my_type')->shouldBeCalled();

        $this->map($data, $object);
    }

    function it_maps_card_payment_instrument(Payment $payment)
    {
        $data = [
            'payment_method' => 'card',
            'payment_instrument' => [
                'card_brand' => 'Visa',
                'pan' => '0067',
                'exp_year' => 2021,
                'exp_month' => 12,
                'holder' => 'Mike Dough'
            ],
        ];

        $instrument = new PaymentInstrumentCard();
        $instrument->setCardBrand('Visa');
        $instrument->setPan('0067');
        $instrument->setExpYear(2021);
        $instrument->setExpMonth(12);
        $instrument->setHolder('Mike Dough');

        $payment->setPaymentMethod('card')->shouldBeCalled();
        $payment->setPaymentInstrument($instrument)->shouldBeCalled();

        $this->map($data, $payment)->shouldReturn($payment);
    }

    function it_maps_recurring_payment_instrument(Payment $payment)
    {
        $data = [
            'payment_method' => 'recurring',
            'payment_instrument' => [
                'payment_id' => 'ba3119f2-9a73-11e4-89d3-123b93f75cba',
            ],
        ];

        $instrument = new PaymentInstrumentRecurring();
        $instrument->setPaymentId('ba3119f2-9a73-11e4-89d3-123b93f75cba');

        $payment->setPaymentMethod('recurring')->shouldBeCalled();
        $payment->setPaymentInstrument($instrument)->shouldBeCalled();

        $this->map($data, $payment);
    }

    function it_throws_exception_for_unknown_payment_method(Payment $payment)
    {
        $data = [
            'payment_method' => 'non_existing_method',
            'payment_instrument' => [],
        ];

        $this->shouldThrow('Cardinity\Exception\Runtime')->duringMap($data, $payment);
    }

    function it_maps_authorization_information(Payment $payment)
    {
        $data = [
            'authorization_information' => [
                'url' => 'https://authorization.url/auth',
                'data' => 'eJxdUl1vwj.......',
            ],
        ];

        $info = new AuthorizationInformation();
        $info->setUrl('https://authorization.url/auth');
        $info->setData('eJxdUl1vwj.......');

        $payment->setAuthorizationInformation($info)->shouldBeCalled();

        $this->map($data, $payment);
    }

    function it_maps_data_collection(MethodResultCollectionInterface $method, Payment $object)
    {
        $data = [
            ['id' => 32, 'type' => 'my_type'],
        ];

        $method->createResultObject()->shouldBeCalled()->willReturn($object);

        $object->setId(32)->shouldBeCalled();
        $object->setType('my_type')->shouldBeCalled();

        $this->mapCollection($data, $method);
    }
}
