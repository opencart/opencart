<?php

namespace Cardinity\Method\Chargeback;

use Cardinity\Method\ResultObject;

class Chargeback extends ResultObject
{
    /**
      * @type string UUID of the Chargeback.
      * Value assigned by Cardinity.
      */
    private $id;


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
      * @type string Can only have value 'chargeback'
      */
    private $type;

    /**
      * @type string Chargeback Creation time RFC 3339 Section 5.6.
      * UTC timezone.
      */
    private $created;

    /**
      * @type boolean Indicates whether chargeback was created in live or sandbox
      */
    private $live;

    /**
      * @type string Parent UUID of the Chargeback.
      * Value assigned by Cardinity.
      */
    private $parent_id;

    /**
      * @type string Status of chargeback
      * Can only be 'approved' or 'declined'
      */
    private $status;

    /**
      * @type string Reason of chargeback
      * Chargeback reason (Only present if chargeback status is approved).
      * Format is "reason_code: reason_message"
      */
    private $reason;

    /**
      * @type string Description of chargeback
      */
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
     * Sets the value of type
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
     * Gets the value of id.
     * @return mixed
     */
    public function getParentId()
    {
        return $this->parent_id;
    }

    /**
     * Sets the value of id.
     * @param mixed $id the id of parent
     * @return void
     */
    public function setParentId($parent_id)
    {
        $this->parent_id = $parent_id;
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
     * Gets the value of reason.
     * @return mixed
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Sets the value of reason.
     * @param mixed $reason the reason
     * @return void
     */
    public function setReason($reason)
    {
        $this->reason = $reason;
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
