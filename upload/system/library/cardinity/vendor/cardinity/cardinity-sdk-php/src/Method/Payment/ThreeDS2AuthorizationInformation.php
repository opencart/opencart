<?php

namespace Cardinity\Method\Payment;

use Cardinity\Method\ResultObject;


class ThreeDS2AuthorizationInformation extends ResultObject
{
    /** @var STRING */
    private $acsUrl;

    /** @var STRING */
    private $cReq;


    /**
     * @return STRING
     */
    public function getAcsUrl()
    {
        return $this->acsUrl;
    }


    /**
     * @param STRING
     * @return VOID
     */
    public function setAcsUrl(string $acsUrl) : void
    {
        $this->acsUrl = $acsUrl;
    }


    /**
     * @return STRING
     */
    public function getCReq()
    {
        return $this->cReq;
    }


    /**
     * @param STRING
     * @return VOID
     */
    public function setCReq(string $cReq) : void
    {
        $this->cReq = $cReq;
    }
    
    public function getErrors()
    {
        
    }
}