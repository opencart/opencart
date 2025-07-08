<?php

namespace Cardinity\Method\PaymentLink;

use Cardinity\Method\ResultObject;

class PaymentLink extends ResultObject
{
    /**
      * @type string ID of the payment link.
      * Value assigned by Cardinity.
      */
    private $id;

    /** @type string URL of the payment link.
      * Value assigned by Cardinity.
      */
    private $url;

    /**
      * @type float Amount charged shown in #0.00 format.
      */
    private $amount;

    /**
      * @type string Three-letter ISO currency code representing the currency in
      * which the charge was made.
      */
    private $currency;

    /**
      * @type string Country of a customer provided by a merchant.
      * ISO 3166-1 alpha-2 country code.
      */
    private $country;

    /**
      * @type string Payment Link description provided by a merchant.
      * Maximum length 255 characters.
      */
    private $description;

    /**
      * @type string Payment Link Expiration datetime as defined in RFC 3339 Section 5.6.
      * UTC timezone.
      * optional, if not defined default will be 7 days from now
      */
    private $expiration_date;

    /**
      * @type boolean Indicates whether the payment link can be used more than once
      * mode.
      * Value assigned by Cardinity.
      * Default value is false
      */
    private $multiple_use;

    /**
      * @type boolean Indicates whether the payment link is enabled
      * mode.
      * Value assigned by Cardinity.
      */
    private $enabled;


    /**
     * Gets the value of id.
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the value of id.
     * @param mixed $id the id
     * @return void
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Gets the value of amount.
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Sets the value of amount.
     * @param mixed $amount the amount
     * @return void
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * Gets the value of currency.
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Sets the value of currency.
     * @param mixed $currency the currency
     * @return void
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * Gets the value of enabled.
     * @return mixed
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Sets the value of enabled.
     * @param mixed $enabled the enabled
     * @return void
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }


    /**
     * Gets the value of multiple_use.
     * @return mixed
     */
    public function getMultipleUse()
    {
        return $this->multiple_use;
    }

    /**
     * Sets the value of multiple_use.
     * @param mixed $multiple_use the multiple_use
     * @return void
     */
    public function setMultipleUse($multiple_use)
    {
        $this->multiple_use = $multiple_use;
    }


    /**
     * Gets the value of expiration_date.
     * @return mixed
     */
    public function getExpirationDate()
    {
        return $this->expiration_date;
    }

       /**
     * Sets the value of expiration_date.
     * @param mixed $expiration_date the expiration_date
     * @return void
     */
    public function setExpirationDate($expiration_date)
    {
        $this->expiration_date = $expiration_date;
    }

    /**
     * Gets the value of description.
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the value of description.
     * @param mixed $description the description
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Gets the value of country.
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Sets the value of country.
     * @param mixed $country the country
     * @return void
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * Gets the value of country.
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Sets the value of country.
     * @param mixed $country the country
     * @return void
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }
}
