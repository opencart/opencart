<?php

namespace Cardinity\Method\PaymentLink;

use Cardinity\Method\MethodInterface;
use Symfony\Component\Validator\Constraints as Assert;

class Get implements MethodInterface
{
    private $paymentLinkId;

    public function __construct($paymentLinkId)
    {
        $this->paymentLinkId = $paymentLinkId;
    }

    public function getPaymentLinkId()
    {
        return $this->paymentLinkId;
    }

    public function getAction()
    {
        return sprintf('paymentLinks/%s', $this->getPaymentLinkId());
    }

    public function getMethod()
    {
        return MethodInterface::GET;
    }

    public function createResultObject()
    {
        return new PaymentLink();
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
