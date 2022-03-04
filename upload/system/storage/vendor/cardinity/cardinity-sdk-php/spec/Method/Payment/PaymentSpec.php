<?php

namespace spec\Cardinity\Method\Payment;

use Cardinity\Method\Payment\AuthorizationInformation;
use Cardinity\Method\Payment\ThreeDS2AuthorizationInformation;
use PhpSpec\ObjectBehavior;

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
        $json = '{"payment_method":"card","payment_instrument":{"card_brand":"Visa","pan":"4447","exp_year":2024,"exp_month":5,"holder":"John Smith"}}';
        $this->unserialize($json);

        $this->getPaymentMethod()->shouldReturn('card');
        $this->getPaymentInstrument()->shouldReturnAnInstanceOf('Cardinity\Method\Payment\PaymentInstrumentCard');
        $this->getPaymentInstrument()->getCardBrand()->shouldReturn('Visa');
        $this->getPaymentInstrument()->getExpYear()->shouldReturn(2024);
    }

    function it_is_able_to_unserialize_recurring_payment_instrument()
    {
        $json = '{"payment_method":"recurring","payment_instrument":{"payment_id":"ba3119f2-9a73"}}';
        $this->unserialize($json);

        $this->getPaymentMethod()->shouldReturn('recurring');
        $this->getPaymentInstrument()->shouldReturnAnInstanceOf('Cardinity\Method\Payment\PaymentInstrumentRecurring');
        $this->getPaymentInstrument()->getPaymentId()->shouldReturn('ba3119f2-9a73');
    }


    function it_is_able_to_take_threeds2_data()
    {

        $tds2Auth = new ThreeDS2AuthorizationInformation();
        $tds2Auth->setAcsUrl('https://acs.cardinity.com/v2/');
        $tds2Auth->setCreq('eyJyZXR1c...');

        $this->setThreeds2Data($tds2Auth);
        $this->getThreeds2Data()->shouldReturnAnInstanceOf('Cardinity\Method\Payment\ThreeDS2AuthorizationInformation');
        $this->getThreeds2Data()->shouldReturn($tds2Auth);

    }

    function it_is_able_to_serialize_tdsv2(){
        $json = '{"acs_url":"https:\/\/acs.cardinity.com\/v2\/","c_req":"eyJyZXR1c..."}';

        $tds2Auth = new ThreeDS2AuthorizationInformation();
        $tds2Auth->setAcsUrl('https://acs.cardinity.com/v2/');
        $tds2Auth->setCreq('eyJyZXR1c...');

        $this->setThreeds2Data($tds2Auth);
        $this->getThreeds2Data()->shouldReturnAnInstanceOf('Cardinity\Method\Payment\ThreeDS2AuthorizationInformation');
        $this->getThreeds2Data()->shouldReturn($tds2Auth);

        $this->getThreeds2Data()->serialize()->shouldReturn($json);
    }


    function it_handles_unexpected_values()
    {
        $json = '{"payment_instrument":{"payment_id":"ba3119f2-9a73"}}';
        $this->shouldThrow('Cardinity\Exception\Runtime')->duringUnserialize($json);
    }
}
