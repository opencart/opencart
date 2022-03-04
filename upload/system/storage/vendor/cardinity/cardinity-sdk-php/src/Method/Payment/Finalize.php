<?php

namespace Cardinity\Method\Payment;

use Cardinity\Method\MethodInterface;
use Symfony\Component\Validator\Constraints as Assert;

class Finalize implements MethodInterface
{
    private $paymentId;
    private $authorizeData;
    private $finalizeKey;

    /**
     * @param STRING payment ID of the Cardinity system
     * @param STRING authorize data 'cres' or 'authorize_data'
     * @param BOOL should it be 3D secure v2 ?
     */
    public function __construct(string $paymentId, string $authorizeData, $isV2=false)
    {
        $this->paymentId = $paymentId;
        $this->authorizeData = $authorizeData;
        $this->finalizeKey = $isV2 ? 'cres' : 'authorize_data';
    }

    public function getPaymentId()
    {
        return $this->paymentId;
    }

    public function getAuthorizeData()
    {
        return $this->authorizeData;
    }

    public function getAction()
    {
        return sprintf('payments/%s', $this->getPaymentId());
    }

    public function getMethod()
    {
        return MethodInterface::PATCH;
    }

    public function createResultObject()
    {
        return new Payment();
    }

    public function getAttributes()
    {
        return [
            $this->finalizeKey => $this->getAuthorizeData(),
            $this->paymentId => $this->getPaymentId(),
        ];
    }

    public function getValidationConstraints()
    {
        $type_params = [
            'type' => 'string',
            'message' => 'The value {{ value }} is not a valid {{ type }}.'
        ];
        return new Assert\Collection([
            'fields' => [
                $this->finalizeKey => new Assert\Required([
                    new Assert\NotBlank(["message"=>"$this->finalizeKey missing."]),
                    new Assert\Type($type_params)
                ]),
                $this->paymentId => new Assert\Required([
                    new Assert\NotBlank(["message"=>"paymentId missing."]),
                    new Assert\Type($type_params)
                ]),
            ],
        ]);
    }
}
