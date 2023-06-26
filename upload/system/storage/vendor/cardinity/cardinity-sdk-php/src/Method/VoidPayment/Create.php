<?php

namespace Cardinity\Method\VoidPayment;

use Cardinity\Method\MethodInterface;
use Symfony\Component\Validator\Constraints as Assert;

class Create implements MethodInterface
{
    private $paymentId;
    private $description;

    public function __construct($paymentId, $description = null)
    {
        $this->paymentId = $paymentId;
        $this->description = $description;
    }

    public function getAction()
    {
        return sprintf('payments/%s/voids', $this->paymentId);
    }

    public function getMethod()
    {
        return MethodInterface::POST;
    }

    public function getAttributes()
    {
        $return = [];

        if ($this->description !== null) {
            $return['description'] = $this->description;
        }

        return $return;
    }

    public function createResultObject()
    {
        return new VoidPayment();
    }

    public function getValidationConstraints()
    {
        return new Assert\Collection([
            'description' => new Assert\Optional([
                new Assert\Type(['type' => 'string']),
                new Assert\Length([
                    'max' => 255
                ]),
            ]),
        ]);
    }
}
