<?php

namespace Cardinity\Method\Refund;

use Cardinity\Method\ResultObject;

class Refund extends ResultObject
{
    /** @type string ID of the refund.
        Value assigned by Cardinity. */
    private $id;

    /** @type float Amount refunded shown in #0.00 format. */
    private $amount;

    /** @type string Three-letter ISO currency code representing the currency in
        which the refund was made.
        Supported currencies: EUR, USD.
        Value assigned by Cardinity. */
    private $currency;

    /** @type string Can only be: refund.
        Value assigned by Cardinity. */
    private $type;

    /** @type string Refund creation time as defined in RFC 3339 Section 5.6.
        UTC timezone.
        Value assigned by Cardinity. */
    private $created;

    /** @type boolean Indicates whether a refund was made in live or testing
        mode.
        Value assigned by Cardinity. */
    private $live;

    /** @type string ID of the refunded payment.
        Value assigned by Cardinity. */
    private $parentId;

    /** @type string Refund status.
        Can be one of the following: approved, declined.
        Value assigned by Cardinity. */
    private $status;

    /** @type string Error message.
        Returned only if status is declined.
        Provides human readable information why the refund failed.
        Value assigned by Cardinity. */
    private $error;

    /** @type string Optional. Order ID provided by a merchant in initial
        payment. Must be between 2 and 50 characters [A-Za-z0-9'.-].
        Value assigned by Cardinity. */
    private $orderId;

    /** @type string Refund description provided by a merchant.
        Maximum length 255 characters. */
    private $description;
 
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
     * Gets the value of type.
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }
 
    /**
     * Sets the value of type.
     * @param mixed $type the type
     * @return void
     */
    public function setType($type)
    {
        $this->type = $type;
    }
 
    /**
     * Gets the value of created.
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }
 
    /**
     * Sets the value of created.
     * @param mixed $created the created
     * @return void
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }
 
    /**
     * Gets the value of live.
     * @return mixed
     */
    public function getLive()
    {
        return $this->live;
    }
 
    /**
     * Sets the value of live.
     * @param mixed $live the live
     * @return void
     */
    public function setLive($live)
    {
        $this->live = $live;
    }
 
    /**
     * Gets the value of parentId.
     * @return mixed
     */
    public function getParentId()
    {
        return $this->parentId;
    }
 
    /**
     * Sets the value of parentId.
     * @param mixed $parentId the parent id
     * @return void
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;
    }
 
    /**
     * Gets the value of status.
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }
 
    /**
     * Sets the value of status.
     * @param mixed $status the status
     * @return void
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }
 
    /**
     * Gets the value of error.
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }
 
    /**
     * Sets the value of error.
     * @param mixed $error the error
     * @return void
     */
    public function setError($error)
    {
        $this->error = $error;
    }
 
    /**
     * Gets the value of orderId.
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->orderId;
    }
 
    /**
     * Sets the value of orderId.
     * @param mixed $orderId the order id
     * @return void
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
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
}
