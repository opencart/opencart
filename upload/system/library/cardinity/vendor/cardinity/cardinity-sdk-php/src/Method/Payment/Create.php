<?php

namespace Cardinity\Method\Payment;

use Cardinity\Method\MethodInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Cardinity\Method\Validators\CallbackUrlConstraint;


class Create implements MethodInterface
{
    const CARD = 'card';
    const RECURRING = 'recurring';

    private $attributes;


    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function getAction(): string
    {
        return 'payments';
    }

    public function getMethod(): string
    {
        return MethodInterface::POST;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function createResultObject(): Payment
    {
        return new Payment();
    }

    public function getValidationConstraints()
    {
        return new Assert\Collection([
            'amount' => new Assert\Regex([
                'pattern' => '/^\d{1,7}(\.\d{1,4})?$/',
                'match' => true,
                'message' => "{{ value }} should be a valid integer number or floating number with 1-4 decimal points."
            ]),
            'currency' => $this->buildElement('string', 1, ['min' => 3,'max' => 3]),
            'settle' => $this->buildElement('bool'),
            'order_id' => $this->buildElement('string', 0, ['min' => 2,'max' => 50]),
            'description' => $this->buildElement('string', 0, ['max' => 255]),
            'statement_descriptor_suffix' => $this->buildElement('string', 0, ['max' => 25]),
            'country' => $this->buildElement('string', 1, ['min' => 2,'max' => 2]),
            'payment_method' => new Assert\Required([
                new Assert\Type([
                    'type' => 'string',
                    'message' => 'The value {{ value }} is not a valid {{ type }}.'
                ]),
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
            'threeds2_data' => new Assert\Optional(
                $this->getThreeDS2DataConstraints()
            )
        ]);
    }

    private function getPaymentInstrumentConstraints($method): ?object
    {
        switch ($method) {
            case self::CARD:
                return new Assert\Collection([
                    'pan' => new Assert\Required([
                        new Assert\NotBlank(),
                        new Assert\Luhn()
                    ]),
                    'exp_year' => $this->buildElement(
                        'integer', 1,
                        ['min' => 4,'max' => 4],
                        new Assert\Range(['min' => date('Y')])
                    ),
                    'exp_month' => $this->buildElement('integer', 1),
                    'cvc' => $this->buildElement('string', 1, ['min' => 3, 'max' => 4]),
                    'holder' => $this->buildElement('string', 1, ['max' => 32]),
                ]);
            case self::RECURRING:
                return new Assert\Collection([
                    'payment_id' => $this->buildElement('string', 1),
                ]);
        }

        throw new \InvalidArgumentException(
            sprintf(
                'Payment instrument for payment method "%s" is not expected',
                $method
            )
        );
    }

    private function getThreeDS2DataConstraints(): object
    {
        return new Assert\Collection([
            'notification_url' => $this->getNotificationUrlConstraints(),
            'browser_info' => $this->getBrowserInfoConstraints(),
            'billing_address' => new Assert\Optional(
                $this->getAdressConstraints()
            ),
            'delivery_address' => new Assert\Optional(
                $this->getAdressConstraints()
            ),
            'cardholder_info' => new Assert\Optional(
                $this->getCardHolderInfoConstraints()
            ),
        ]);
    }

    public function getNotificationUrlConstraints()
    {
        return new Assert\Required([
            new Assert\NotBlank(),
            new Assert\Type([
                'type' => 'string',
                'message' => 'The value {{ value }} is not a valid {{ type }}.'
            ]),
            new CallbackUrlConstraint(),
            new Assert\Url([
                'message' => 'The protocol of {{ value }} should be "http" or "https".',
                'protocols' => ['http', 'https'],
            ]),
        ]);
    }

    public function getIpAddressConstraints()
    {
        return new Assert\Optional([
            new Assert\NotBlank(),
            new CallbackUrlConstraint(),
            new Assert\Type([
                'type' => 'string',
                'message' => 'The value {{ value }} is not a valid {{ type }}.'
            ]),
        ]);
    }

    private function getBrowserInfoConstraints()
    {
        return new Assert\Collection([
            'accept_header' => $this->buildElement('string', 1),
            'browser_language' => $this->buildElement('string', 1),
            'screen_width' => $this->buildElement('integer', 1),
            'screen_height' => $this->buildElement('integer', 1),
            'challenge_window_size' => $this->buildElement('string', 1),
            'user_agent' => $this->buildElement('string', 1),
            'color_depth' => $this->buildElement('integer', 1),
            'time_zone' => $this->buildElement('integer', 1),
            'ip_address' => $this->getIpAddressConstraints(),
            'javascript_enabled' => new Assert\Optional($this->buildElement('bool')),
            'java_enabled' => new Assert\Optional($this->buildElement('bool')),
        ]);
    }

    private function getAdressConstraints(): object
    {
        return new Assert\Collection([
            'address_line1' => $this->buildElement('string', 1, ['max' => 50]),
            'address_line2' => new Assert\Optional(
                $this->buildElement('string', 1, ['max' => 50])
            ),
            'address_line3' => new Assert\Optional(
                $this->buildElement('string', 0, ['max' => 50])
            ),
            'city' => $this->buildElement('string', 1, ['max' => 50]),
            'country' => $this->buildElement('string', 1, ['max' => 10]),
            'postal_code' => $this->buildElement('string', 1, ['max' => 16]),
            'state' => new Assert\Optional(
                $this->buildElement('string', 0, ['max' => 14])
            ),
        ]);
    }

    private function getCardHolderInfoConstraints(): object
    {
        return new Assert\Collection([
            'email_address' => new Assert\Optional(
                new Assert\Email([
                    'mode' => Assert\Email::VALIDATION_MODE_HTML5
                ])
            ),
            'mobile_phone_number' => new Assert\Optional($this->buildElement('string')),
            'work_phone_number' => new Assert\Optional($this->buildElement('string')),
            'home_phone_number' => new Assert\Optional($this->buildElement('string')),
        ]);
    }

    private function buildElement(
        string $typeValue,
        bool $isRequired = false,
        ?array $length = null,
        $args = null // TODO can it be null?
    ): object
    {
        $inside_array = $this->getInsideArray($typeValue);
        if ($isRequired) array_unshift($inside_array, new Assert\NotBlank());
        if ($length) array_push($inside_array, new Assert\Length($length));
        if ($args) array_push($inside_array, $args);

        return $isRequired
            ? new Assert\Required($inside_array)
            : new Assert\Optional($inside_array)
        ;
    }

    private function getInsideArray(string $typeValue): array
    {
        return [
            new Assert\Type([
                'type' => $typeValue,
                'message' => 'The value {{ value }} is not a valid {{ type }}.'
            ]),
        ];
    }
}
