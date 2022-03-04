<?php

namespace Cardinity\Method\Payment;

use Cardinity\Method\MethodInterface;
use Symfony\Component\Validator\Constraints as Assert;

class Get implements MethodInterface
{
    private $paymentId;

    public function __construct($paymentId)
    {
        $this->paymentId = $paymentId;
    }

    public function getPaymentId()
    {
        return $this->paymentId;
    }

    public function getAction()
    {
        return sprintf('payments/%s', $this->getPaymentId());
    }

    public function getMethod()
    {
        return MethodInterface::GET;
    }

    public function createResultObject()
    {
        return new Payment();
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
