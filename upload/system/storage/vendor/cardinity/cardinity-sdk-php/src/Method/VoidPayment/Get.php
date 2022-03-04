<?php

namespace Cardinity\Method\VoidPayment;

use Cardinity\Method\MethodInterface;
use Symfony\Component\Validator\Constraints as Assert;

class Get implements MethodInterface
{
    private $paymentId;
    private $voidId;

    public function __construct($paymentId, $voidId)
    {
        $this->paymentId = $paymentId;
        $this->voidId = $voidId;
    }

    public function getAction()
    {
        return sprintf(
            'payments/%s/voids/%s',
            $this->getPaymentId(),
            $this->getvoidId()
        );
    }

    public function getMethod()
    {
        return MethodInterface::GET;
    }

    public function createResultObject()
    {
        return new VoidPayment();
    }

    public function getAttributes()
    {
        return [];
    }

    public function getValidationConstraints()
    {
        return new Assert\Collection([]);
    }

    public function getPaymentId()
    {
        return $this->paymentId;
    }

    public function getvoidId()
    {
        return $this->voidId;
    }
}
