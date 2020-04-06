<?php

namespace Cardinity\Method\Payment;

use Cardinity\Method\ResultObject;

class AuthorizationInformation extends ResultObject
{
    /** @type string URL where customer should be redirected to complete
        a payment authorization.
        Value assigned by Cardinity. */
    private $url;

    /** @type string Data which must be passed along with the customer being
        redirected.
        Value assigned by Cardinity.*/
    private $data;
 
    /**
     * Gets the value of url.
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }
 
    /**
     * Sets the value of url.
     * @param mixed $url the url
     * @return void
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }
 
    /**
     * Gets the value of data.
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
 
    /**
     * Sets the value of data.
     * @param mixed $data the data
     * @return void
     */
    public function setData($data)
    {
        $this->data = $data;
    }
}
