<?php

namespace Cardinity\Method\Settlement;

use Cardinity\Method\MethodInterface;
use Symfony\Component\Validator\Constraints as Assert;

class Create implements MethodInterface
{
    private $paymentId;
    private $amount;
    private $description;
    public function __construct($paymentId, $amount, $description = null)
    {
        $this->paymentId = $paymentId;
        $this->amount = $amount;
        $this->description = $description;
    }

    public function getAction()
    {
        return sprintf('payments/%s/settlements', $this->paymentId);
    }

    public function getMethod()
    {
        return MethodInterface::POST;
    }

    public function getAttributes()
    {
        $return = [
            'amount' => $this->amount,
        ];

        if ($this->description !== null) {
            $return['description'] = $this->description;
        }

        return $return;
    }

    public function createResultObject()
    {
        return new Settlement();
    }

    public function getValidationConstraints()
    {
        return new Assert\Collection([
            'amount' => new Assert\Required([
                new Assert\NotBlank(),
                new Assert\Type(['type' => 'float'])
            ]),
            'description' => new Assert\Optional([
                new Assert\Type(['type' => 'string']),
                new Assert\Length([
                    'max' => 255
                ]),
            ]),
        ]);
    }
}
