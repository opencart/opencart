<?php

namespace spec\Cardinity\Method\Payment;

use PhpSpec\ObjectBehavior;

class ThreeDS2AuthorizationInformationSpec extends ObjectBehavior
{
    private $tds2_data;
   
    function let()
    {
        $this->tds2_data['acs_url'] = 'http://mynotifactionurl.com';
        $this->tds2_data['creq'] = "asdf123xcxzcv";

        $this->setAcsUrl($this->tds2_data['acs_url']);
        $this->setCReq($this->tds2_data['creq']);
    }

    function it_is_initalizable(){
        $this->shouldImplement('Cardinity\Method\ResultObjectInterface');
    }

    function it_contains_acs_url()
    {        
        $this->getAcsUrl()->shouldReturn($this->tds2_data['acs_url']);
    }

    function it_contains_creq()
    {        
        $this->getCReq()->shouldReturn($this->tds2_data['creq']);
    }

   

   


}


