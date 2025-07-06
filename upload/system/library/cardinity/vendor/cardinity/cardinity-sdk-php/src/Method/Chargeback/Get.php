<?php

namespace Cardinity\Method\Chargeback;

use Cardinity\Method\MethodInterface;
use Symfony\Component\Validator\Constraints as Assert;

class Get implements MethodInterface
{
    private $payment_id;
    private $chargeback_id;

    public function __construct($payment_id, $chargeback_id)
    {
        $this->payment_id = $payment_id;
        $this->chargeback_id =$chargeback_id;
    }

    public function getChargebackId()
    {
        return $this->chargeback_id;
    }

    public function getPaymentId()
    {
        return $this->payment_id;
    }


    public function getAction()
    {
        return sprintf('payments/%s/chargebacks/%s', $this->payment_id, $this->chargeback_id);
    }

    public function getMethod()
    {
        return MethodInterface::GET;
    }

    public function createResultObject()
    {
        return new Chargeback();
    }

    public function getAttributes()
    {
        return [];
    }

    public function getValidationConstraints()
    {
        return new Assert\Collection([]);
    }
}
