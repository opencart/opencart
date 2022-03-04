<?php

namespace Cardinity\Method\VoidPayment;

use Cardinity\Method\MethodInterface;
use Cardinity\Method\MethodResultCollectionInterface;
use Symfony\Component\Validator\Constraints as Assert;

class GetAll implements MethodResultCollectionInterface
{
    private $paymentId;

    public function __construct($paymentId)
    {
        $this->paymentId = $paymentId;
    }

    public function getAction()
    {
        return sprintf(
            'payments/%s/voids',
            $this->getPaymentId()
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
}
