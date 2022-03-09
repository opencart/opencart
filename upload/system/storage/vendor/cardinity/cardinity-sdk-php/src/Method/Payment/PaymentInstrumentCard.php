<?php

namespace Cardinity\Method\Payment;

use Cardinity\Method\ResultObject;

class PaymentInstrumentCard extends ResultObject implements PaymentInstrumentInterface
{
    /** @type string Card brand.
        Value assigned by Cardinity. */
    private $cardBrand;

    /** @type string Card number.
        When creating a payment merchant provide full card number. However while
        retrieving an existing payments only last 4 digits are returned. */
    private $pan;

    /** @type integer Expiration year. 4 digits, e.g. 2016. */
    private $expYear;

    /** @type integer Expiration month, e.g. 9. */
    private $expMonth;

    /** @type string Card holder’s name. Max length 32 characters. */
    private $holder;
  
    /**
     * Gets the value of cardBrand.
     * @return mixed
     */
    public function getCardBrand()
    {
        return $this->cardBrand;
    }
 
    /**
     * Sets the value of cardBrand.
     * @param mixed $cardBrand the card brand
     * @return void
     */
    public function setCardBrand($cardBrand)
    {
        $this->cardBrand = $cardBrand;
    }
 
    /**
     * Gets the value of pan.
     * @return mixed
     */
    public function getPan()
    {
        return $this->pan;
    }
 
    /**
     * Sets the value of pan.
     * @param mixed $pan the pan
     * @return void
     */
    public function setPan($pan)
    {
        $this->pan = $pan;
    }
 
    /**
     * Gets the value of expYear.
     * @return mixed
     */
    public function getExpYear()
    {
        return $this->expYear;
    }
 
    /**
     * Sets the value of expYear.
     * @param mixed $expYear the exp year
     * @return void
     */
    public function setExpYear($expYear)
    {
        $this->expYear = $expYear;
    }
 
    /**
     * Gets the value of expMonth.
     * @return mixed
     */
    public function getExpMonth()
    {
        return $this->expMonth;
    }
 
    /**
     * Sets the value of expMonth.
     * @param mixed $expMonth the exp month
     * @return void
     */
    public function setExpMonth($expMonth)
    {
        $this->expMonth = $expMonth;
    }
 
    /**
     * Gets the value of holder.
     * @return mixed
     */
    public function getHolder()
    {
        return $this->holder;
    }
 
    /**
     * Sets the value of holder.
     * @param mixed $holder the holder
     * @return void
     */
    public function setHolder($holder)
    {
        $this->holder = $holder;
    }
}
