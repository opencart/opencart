<?php

namespace Cardinity\Method\Settlement;

use Cardinity\Method\MethodInterface;
use Symfony\Component\Validator\Constraints as Assert;

class Get implements MethodInterface
{
    private $paymentId;
    private $settlementId;

    public function __construct($paymentId, $settlementId)
    {
        $this->paymentId = $paymentId;
        $this->settlementId = $settlementId;
    }

    public function getAction()
    {
        return sprintf(
            'payments/%s/settlements/%s',
            $this->getPaymentId(),
            $this->getSettlementId()
        );
    }

    public function getMethod()
    {
        return MethodInterface::GET;
    }

    public function createResultObject()
    {
        return new Settlement();
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

    public function getSettlementId()
    {
        return $this->settlementId;
    }
}
