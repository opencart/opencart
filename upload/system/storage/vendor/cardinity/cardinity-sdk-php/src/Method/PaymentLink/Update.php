<?php

namespace Cardinity\Method\PaymentLink;

use Cardinity\Method\MethodInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Cardinity\Method\Validators\CallbackUrlConstraint;


class Update implements MethodInterface
{
    private $paymentLinkId;
    private $attributes;

    public function __construct($paymentLinkId, array $attributes)
    {
        $this->attributes = $attributes;
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

    public function getMethod(): string
    {
        return MethodInterface::PATCH;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function createResultObject(): PaymentLink
    {
        return new PaymentLink();
    }

    public function getValidationConstraints()
    {
        return new Assert\Collection([
            'expiration_date' => new Assert\Optional([
                new Assert\Regex([
                    'pattern' => '/^(\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(\.\d+)?(Z|[\+\-]\d{2}:\d{2}))?$/',
                    'message' => 'Date Time string should follow ISO 8601. e.g: 2023-01-06T15:26:03.702Z',
                ]),
                new Assert\Regex([
                    'pattern' => '/Z$/',
                    'message' => 'Date Time should be in UTC timezone',
                ]),
            ]),
            'enabled' => new Assert\Optional([
                new Assert\Type(['type' => 'bool']),
            ]),
        ]);
    }
}
