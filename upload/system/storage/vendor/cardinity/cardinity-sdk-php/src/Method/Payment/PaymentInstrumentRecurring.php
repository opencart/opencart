<?php

namespace Cardinity\Method\Payment;

use Cardinity\Method\ResultObject;

class PaymentInstrumentRecurring extends ResultObject implements PaymentInstrumentInterface
{
    /** @type string Id of the approved payment in the past.
        Same card will be used to create a new payment. */
    private $paymentId;

    /**
     * Gets the value of paymentId.
     * @return mixed
     */
    public function getPaymentId()
    {
        return $this->paymentId;
    }

    /**
     * Sets the value of paymentId.
     * @param mixed $paymentId the payment id
     * @return void
     */
    public function setPaymentId($paymentId)
    {
        $this->paymentId = $paymentId;
    }
}
