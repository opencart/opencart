<?php

namespace spec\Cardinity\Method\Payment;

use Cardinity\Method\Payment\AuthorizationInformation;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PaymentSpec extends ObjectBehavior
{
    function it_implements_result_object_behaviour()
    {
        $this->shouldImplement('Cardinity\Method\ResultObjectInterface');
    }

    function it_is_serializable()
    {
        $this->shouldImplement('\Serializable');

        $this->setId('foo');
        $this->setAmount(20.00);
        $this->setType(null);

        $info = new AuthorizationInformation();
        $info->setUrl('http://...');
        $info->setData('some_data');

        $this->setAuthorizationInformation($info);
        $this->serialize()->shouldReturn('{"id":"foo","amount":"20.00","authorization_information":{"url":"http:\/\/...","data":"some_data"}}');
    }

    function it_is_able_to_unserialize_authorization_information()
    {
        $json = '{"id":"foo.bar.123","amount":"20.00","authorization_information":{"url":"http:\/\/...","data":"some_data"}}';
        $this->unserialize($json);

        $this->getId()->shouldReturn('foo.bar.123');
        $this->getAmount()->shouldReturn(20.00);
        $this->getType()->shouldReturn(null);
        $this->getAuthorizationInformation()->shouldReturnAnInstanceOf('Cardinity\Method\Payment\AuthorizationInformation');
        $this->getAuthorizationInformation()->getUrl()->shouldReturn('http://...');
        $this->getAuthorizationInformation()->getData()->shouldReturn('some_data');
    }

    function it_is_able_to_unserialize_card_payment_instrument()
    {
        $json = '{"payment_method":"card","payment_instrument":{"card_brand":"Visa","pan":"4447","exp_year":2017,"exp_month":5,"holder":"John Smith"}}';
        $this->unserialize($json);

        $this->getPaymentMethod()->shouldReturn('card');
        $this->getPaymentInstrument()->shouldReturnAnInstanceOf('Cardinity\Method\Payment\PaymentInstrumentCard');
        $this->getPaymentInstrument()->getCardBrand()->shouldReturn('Visa');
        $this->getPaymentInstrument()->getExpYear()->shouldReturn(2017);
    }

    function it_is_able_to_unserialize_recurring_payment_instrument()
    {
        $json = '{"payment_method":"recurring","payment_instrument":{"payment_id":"ba3119f2-9a73"}}';
        $this->unserialize($json);

        $this->getPaymentMethod()->shouldReturn('recurring');
        $this->getPaymentInstrument()->shouldReturnAnInstanceOf('Cardinity\Method\Payment\PaymentInstrumentRecurring');
        $this->getPaymentInstrument()->getPaymentId()->shouldReturn('ba3119f2-9a73');
    }

    function it_handles_unexpected_values()
    {
        $json = '{"payment_instrument":{"payment_id":"ba3119f2-9a73"}}';
        $this->shouldThrow('Cardinity\Exception\Runtime')->duringUnserialize($json);
    }
}
