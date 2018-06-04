<?php

namespace Cardinity\Method\Payment;

use Cardinity\Method\MethodInterface;
use Cardinity\Method\MethodResultCollectionInterface;
use Symfony\Component\Validator\Constraints as Assert;

class GetAll implements MethodResultCollectionInterface
{
    private $limit;

    public function __construct($limit = 10)
    {
        $this->limit = $limit;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function getAction()
    {
        return 'payments';
    }

    public function getMethod()
    {
        return MethodInterface::GET;
    }

    public function createResultObject()
    {
        return new Payment();
    }

    public function getAttributes()
    {
        return [
            'limit' => $this->getLimit()
        ];
    }

    public function getValidationConstraints()
    {
        return new Assert\Collection([
            'limit' => new Assert\Optional([
                new Assert\NotNull(),
                new Assert\Type(['type' => 'integer']),
            ]),
        ]);
    }
}
