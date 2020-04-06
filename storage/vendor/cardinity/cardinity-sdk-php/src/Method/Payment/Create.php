<?php

namespace Cardinity\Method\Payment;

use Cardinity\Method\MethodInterface;
use Symfony\Component\Validator\Constraints as Assert;

class Create implements MethodInterface
{
    const CARD = 'card';
    const RECURRING = 'recurring';

    private $attributes;

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function getAction()
    {
        return 'payments';
    }

    public function getMethod()
    {
        return MethodInterface::POST;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function createResultObject()
    {
        return new Payment();
    }

    public function getValidationConstraints()
    {
        return new Assert\Collection([
            'amount' => new Assert\Required([
                new Assert\NotBlank(),
                new Assert\Type(['type' => 'float'])
            ]),
            'currency' => new Assert\Required([
                new Assert\NotBlank(),
                new Assert\Type(['type' => 'string']),
                new Assert\Length([
                    'min' => 3,
                    'max' => 3
                ]),
            ]),
            'settle' => new Assert\Optional([
                new Assert\Type(['type' => 'bool'])
            ]),
            'order_id' => new Assert\Optional([
                new Assert\Type(['type' => 'string'])
            ]),
            'description' => new Assert\Optional([
                new Assert\Type(['type' => 'string']),
                new Assert\Length([
                    'max' => 255
                ]),
            ]),
            'country' => new Assert\Required([
                new Assert\Type(['type' => 'string']),
                new Assert\Length([
                    'min' => 2,
                    'max' => 2
                ]),
            ]),
            'payment_method' => new Assert\Required([
                new Assert\Type(['type' => 'string']),
                new Assert\Choice([
                    'choices' => [
                        self::CARD,
                        self::RECURRING
                    ]
                ])
            ]),
            'payment_instrument' => $this->getPaymentInstrumentConstraints(
                $this->getAttributes()['payment_method']
            ),
        ]);
    }

    private function getPaymentInstrumentConstraints($method)
    {
        switch ($method) {
            case self::CARD:
                return new Assert\Collection([
                    'pan' => new Assert\Required([
                        new Assert\NotBlank(),
                        new Assert\Luhn()
                    ]),
                    'exp_year' => new Assert\Required([
                        new Assert\NotBlank(),
                        new Assert\Type(['type' => 'integer']),
                        new Assert\Length([
                            'min' => 4,
                            'max' => 4
                        ]),
                        new Assert\Range([
                            'min' => date('Y')
                        ]),
                    ]),
                    'exp_month' => new Assert\Required([
                        new Assert\NotBlank(),
                        new Assert\Type(['type' => 'integer']),
                    ]),
                    'cvc' => new Assert\Required([
                        new Assert\NotBlank(),
                        new Assert\Type(['type' => 'string']),
                    ]),
                    'holder' => new Assert\Required([
                        new Assert\NotBlank(),
                        new Assert\Type(['type' => 'string']),
                        new Assert\Length([
                            'max' => 32
                        ]),
                    ]),
                ]);
            case self::RECURRING:
                return new Assert\Collection([
                    'payment_id' => new Assert\Required([
                        new Assert\NotBlank(),
                        new Assert\Type(['type' => 'string']),
                    ])
                ]);
        }

        throw new \InvalidArgumentException(
            sprintf(
                'Payment instrument for payment method "%s" is not expected',
                $method
            )
        );
    }
}
