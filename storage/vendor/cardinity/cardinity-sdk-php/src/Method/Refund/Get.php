<?php

namespace Cardinity\Method\Refund;

use Cardinity\Method\MethodInterface;
use Symfony\Component\Validator\Constraints as Assert;

class Get implements MethodInterface
{
    private $paymentId;
    private $refundId;
    
    public function __construct($paymentId, $refundId)
    {
        $this->paymentId = $paymentId;
        $this->refundId = $refundId;
    }

    public function getAction()
    {
        return sprintf(
            'payments/%s/refunds/%s',
            $this->getPaymentId(),
            $this->getRefundId()
        );
    }

    public function getMethod()
    {
        return MethodInterface::GET;
    }

    public function createResultObject()
    {
        return new Refund();
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

    public function getRefundId()
    {
        return $this->refundId;
    }
}
